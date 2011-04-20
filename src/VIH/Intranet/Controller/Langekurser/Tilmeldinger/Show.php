<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Show extends k_Component
{
    protected $templates;
    protected $form;
    protected $tilmelding;

    function __construct(k_TemplateFactory $templates)
    {
        $this->templates = $templates;
    }

    function map($name)
    {
        if ($name == 'rater') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Rater';
        } elseif ($name == 'fag') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Fag';
        } elseif ($name == 'brev') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Brev';
        } elseif ($name == 'diplom') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Pdfdiplom';
        }
    }

    function dispatch()
    {
        $tilmelding = $this->getTilmelding();
        if ($tilmelding->get('id') == 0) {
            throw new k_PageNotFound();
        }

        return parent::dispatch();
    }

    function renderHtml()
    {
        $tilmelding = $this->getTilmelding();

        if ($this->query('get_prices')) {
            if (!$tilmelding->getPriserFromKursus()) {
                throw new Exception('Priser kunne ikke hentes');
            } else {
                return new k_SeeOther($this->url());
            }
        }

        $historik = new VIH_Model_Historik('langekurser', $tilmelding->get("id"));
        $betalinger = new VIH_Model_Betaling('langekurser', $tilmelding->get("id"));

        $rater = $tilmelding->getRater();

        if ($this->query('action') == 'sendemail') {
            if ($tilmelding->sendEmail()) {
                if (!$historik->save(array('type' => 'kode', 'comment' => 'Kode sendt med e-mail'))) {
                    throw new Exception('Historikken kunne ikke gemmes');
                }
            } else {
                throw new Exception('E-mailen kunne ikke sendes');
            }
        } elseif ($this->query('action') == 'opretrater') {
            if (!$tilmelding->opretRater()) {
                throw new Exception('Raterne kunne ikke oprettes');
            } else {
                return new k_SeeOther($this->url());
            }
        } elseif($this->query('registrer_betaling')) {
            if($betalinger->save(array('type' => 'giro', 'amount' => $this->query('beloeb')))) {
                $betalinger->setStatus('approved');
            } else {
                throw new Exception("Betalingen kunne ikke gemmes. Det kan skyldes et ugyldigt beløb");
            }
        } elseif ($this->query('slet_historik_id')) {
            $historik = new VIH_Model_Historik(intval($this->query('slet_historik_id')));
            $historik->delete();
        }

        $tilmelding->loadBetaling();

        $this->document->setTitle('Tilmelding #' . $tilmelding->get('id'));
        $this->document->addOption('Til kursus', $this->url('../../' . $tilmelding->kursus->get('id')));
        $this->document->addOption('Tilmeldinger', $this->url('../../'.$tilmelding->kursus->get('id') . '/tilmeldinger'));
        $this->document->addOption('Ret', $this->url('edit'));
        $this->document->addOption('Delete', $this->url(null, array('delete')));
        $this->document->addOption('Protokol', $this->url('../../../protokol/holdliste/' . $tilmelding->get('id')));
        $this->document->addOption('Brev', $this->url('brev'));
        $this->document->addOption('Fag', $this->url('fag'));
        $this->document->addOption('Diplom', $this->url('diplom'));
        $this->document->addOption('Kundens side', LANGEKURSER_LOGIN_URI . $tilmelding->get('code'));

        $opl_data = array('tilmelding' => $tilmelding);

        $pris_data = array('tilmelding' => $tilmelding);

        $betal_data = array('betalinger' => $betalinger->getList('not_approved'),
                            'caption' => 'Betalinger');

        $hist_data = array('tilmelding' => $tilmelding,
                           'historik' => $historik->getList());

        $opl_tpl = $this->templates->create('langekurser/tilmelding/oplysninger');
        $pris_tpl = $this->templates->create('langekurser/tilmelding/prisoversigt');
        $betal_tpl = $this->templates->create('tilmelding/betalinger');
        $his_tpl = $this->templates->create('tilmelding/historik');

        $data = array('tilmelding' => $tilmelding,
                      'oplysninger' => $opl_tpl->render($this, $opl_data),
                      'prisoversigt' => $pris_tpl->render($this, $pris_data),
                      'betalinger' => $betal_tpl->render($this, $betal_data),
                      'historik' => $his_tpl->render($this, $hist_data));

        // rater
        if (count($rater) > 0) {
            $rater_tpl = $this->templates->create('langekurser/tilmelding/rater');
            $rater_data = array('tilmelding' => $tilmelding);
            $data['rater'] = $rater_tpl->render($this, $rater_data);
        } else {
            if ($tilmelding->kursus->antalRater() > 0) {
                $data['rater'] = '<p><a href="'.$this->url(null, array('get_prices' => $tilmelding->get('id'))).'">Hent priserne fra kurset</a>. Der er endnu ikke oprettet nogen rater <a href="'.$this->url(null, array('action' => 'opretrater')) . '">Opret &rarr;</a></p>';
            } else {
                $data['rater'] = '<p>Der er endnu ikke oprettet rater på selve kurset. Dem skal du lige oprette først <a href="'.$this->url('../../'.$tilmelding->getKursus()->get('id').'/rater').'">Opret &rarr;</a></p>';
            }
        }

        $data['message'] = '';

        $tpl = $this->templates->create('langekurser/tilmelding');
        return $tpl->render($this, $data);
    }

    function renderHtmlEdit()
    {
        $tilmelding = $this->getTilmelding();

        $this->getForm()->setDefaults(array(
            'id' => $tilmelding->get('id'),
            'kursus_id' => $tilmelding->get('kursus_id'),
            'vaerelse' => $tilmelding->get('vaerelse'),
            'navn' => $tilmelding->get('navn'),
            'adresse' => $tilmelding->get('adresse'),
            'cpr' => $tilmelding->get('cpr'),
            'telefonnummer' => $tilmelding->get('telefon'),
            'postnr' => $tilmelding->get('postnr'),
            'postby' => $tilmelding->get('postby'),
            'nationalitet' => $tilmelding->get('nationalitet'),
            'kommune' => $tilmelding->get('kommune'),
            'email' => $tilmelding->get('email'),
            'kontakt_navn' => $tilmelding->get('kontakt_navn'),
            'kontakt_adresse' => $tilmelding->get('kontakt_adresse'),
            'kontakt_postnr' => $tilmelding->get('kontakt_postnr'),
            'kontakt_postby' => $tilmelding->get('kontakt_postby'),
            'kontakt_telefon' => $tilmelding->get('kontakt_telefon'),
            'kontakt_arbejdstelefon' => $tilmelding->get('kontakt_arbejdstelefon'),
            'kontakt_email' => $tilmelding->get('kontakt_email'),
            'ryger' => $tilmelding->get('ryger'),
            'betaling' => $tilmelding->get('betaling_key'),
            'uddannelse' =>$tilmelding->get('uddannelse_key'),
            'besked' =>$tilmelding->get('besked'),
            'ugeantal' => $tilmelding->get('ugeantal'),
            'dato_start' => $tilmelding->get('dato_start'),
            'dato_slut' => $tilmelding->get('dato_slut'),
            'pris_uge' => $tilmelding->get('pris_uge'),
            'pris_tilmeldingsgebyr' => $tilmelding->get('pris_tilmeldingsgebyr'),
            'pris_materiale' => $tilmelding->get('pris_materiale'),
            'pris_rejsedepositum' => $tilmelding->get('pris_rejsedepositum'),
            'pris_afbrudt_ophold' => $tilmelding->get('pris_afbrudt_ophold'),
            'kompetencestotte' => $tilmelding->get('kompetencestotte'),
            'elevstotte' => $tilmelding->get('elevstotte'),
            'ugeantal_elevstotte' => $tilmelding->get('ugeantal_elevstotte'),
            'statsstotte' => $tilmelding->get('statsstotte'),
            'kommunestotte' => $tilmelding->get('kommunestotte'),
            'tekst_diplom' => $tilmelding->get('tekst_diplom'),
            'aktiveret_tillaeg' => $tilmelding->get('aktiveret_tillaeg'),
            'sex' => $tilmelding->get('sex')
        ));

        $this->document->setTitle('Tilmelding');
        return $this->getForm()->toHTML();
    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $tilmelding = $this->getTilmelding();
            $input = $this->body();

            $input['dato_start'] = $input['dato_start']['Y'] . '-' . $input['dato_start']['M'] . '-' . $input['dato_start']['d'];
            $input['dato_slut'] = $input['dato_slut']['Y'] . '-' . $input['dato_slut']['M'] . '-' . $input['dato_slut']['d'];

            if ($id = $tilmelding->save($input)) {
                if (!$tilmelding->savePriser($input)) {
                    throw new Exception('Kunne ikke opdatere priserne');
                }
                return new k_SeeOther($this->context->url());
            } else {
                throw new Exception('Kunne ikke gemme oplysningerne om tilmeldingen');
            }
        } else {
            return $this->getForm()->toHTML();
        }
    }

    function getForm()
    {
        $date_options = array('minYear' => date('Y') - 10, 'maxYear' => date('Y') + 5);

        if ($this->form) {
            return $this->form;
        }

        $tilmelding = $this->getTilmelding();

        foreach (VIH_Model_LangtKursus::getList('alle') AS $kursus) {
            $kurser[$kursus->get('id')] = $kursus->getKursusNavn();
        }

        $form = new HTML_QuickForm('tilmelding', 'POST', $this->url());
        $form->addElement('hidden', 'id');
        $form->addElement('header', null, 'Kursus');
        $form->addElement('select', 'kursus_id', 'Kursus', $kurser);
        $form->addElement('header', null, 'Navn og adresse');
        $form->addElement('text', 'vaerelse', 'Værelse');
        $form->addElement('text', 'navn', 'Navn');
        $form->addElement('text', 'adresse', 'Adresse');
        $form->addElement('text', 'postnr', 'Postnummer');
        $form->addElement('text', 'postby', 'Postby');
        $form->addElement('text', 'cpr', 'Cpr-nummer');
        $form->addElement('text', 'telefonnummer', 'Telefonnummer');
        $form->addElement('text', 'kommune', 'Bopælskommune');
        $form->addElement('text', 'nationalitet', 'Nationalitet');
        $form->addElement('text', 'email', 'E-mail');

        foreach ($tilmelding->sex AS $key=>$value) {
            $radio[] = &HTML_QuickForm::createElement('radio', null, null, $value, $key);
        }
        $form->addGroup($radio, 'sex', 'Køn');

        $form->addElement('header', null, 'Nærmeste pårørende - hvem skal vi rette henvendelse til ved sygdom');
        $form->addElement('text', 'kontakt_navn', 'Navn');
        $form->addElement('text', 'kontakt_adresse', 'Adresse');
        $form->addElement('text', 'kontakt_postnr', 'Postnummer');
        $form->addElement('text', 'kontakt_postby', 'Postby');
        $form->addElement('text', 'kontakt_telefon', 'Telefon');
        $form->addElement('text', 'kontakt_arbejdstelefon', 'Arbejdstelefon');
        $form->addElement('text', 'kontakt_email', 'E-mail');
        $form->addElement('header', null, 'Hvordan er din uddannelsesmæssige baggrund?');
        foreach ($tilmelding->uddannelse AS $key=>$value) {
            $udd[] = &HTML_QuickForm::createElement('radio', null, null, $value, $key);
        }
        $form->addGroup($udd, 'uddannelse', 'Uddannelse');
        $form->addElement('header', null, 'Hvordan betaler du?');
        foreach ($tilmelding->betaling AS $key=>$value) {
            $bet[] = &HTML_QuickForm::createElement('radio', null, null, $value, $key);
        }
        $form->addGroup($bet, 'betaling', 'Betaling');
        $form->addElement('header', null, 'Besked til Vejle Idrætshøjskole');
        $form->addElement('textarea', 'besked', 'Er der andet vi bør vide?');
        $form->addElement('textarea', 'tekst_diplom', 'Tekst til diplomet');
        $form->addElement('header', null, 'Termin');
        $form->addElement('text', 'ugeantal', 'Ugeantal');
        $form->addElement('date', 'dato_start', 'Startdato', $date_options);
        $form->addElement('date', 'dato_slut', 'Slutdato', $date_options);
        $form->addElement('header', null, 'Priser');
        $form->addElement('text', 'pris_tilmeldingsgebyr', 'Tilmeldingsgebyr');
        $form->addElement('text', 'pris_uge', 'Ugepris');
        $form->addElement('text', 'pris_materiale', 'Materialer');
        $form->addElement('text', 'pris_rejsedepositum', 'Rejsedepositum');
        $form->addElement('header', null, 'Støtte');
        $form->addElement('text', 'elevstotte', 'Elevstøtte');
        $form->addElement('text', 'ugeantal_elevstotte', 'Elevstøtte antal uger');
        $form->addElement('text', 'kompetencestotte', 'Kompetencestøtte');
        $form->addElement('text', 'statsstotte', 'Indvandrerstøtte');
        $form->addElement('text', 'kommunestotte', 'Kommunestøtte');
        $form->addElement('text', 'aktiveret_tillaeg', 'Aktiveret tillæg');
        $form->addElement('header', null, 'Afbrudt ophold');
        $form->addElement('text', 'pris_afbrudt_ophold', 'Ekstra pris');
        $form->addElement('submit', null, 'Gem');

        $form->applyFilter('__ALL__', 'trim');
        $form->applyFilter('__ALL__', 'strip_tags');

        $form->addRule('id', 'Tilmeldingen skal have et id', 'numeric');
        $form->addRule('kursus_id', 'Du skal vælge et kursus', 'required');
        $form->addRule('kursus_id', 'Du skal vælge et kursus', 'numeric');
        $form->addRule('navn', 'Du skal skrive et navn', 'required');
        $form->addRule('adresse', 'Du skal skrive en adresse', 'required');
        $form->addRule('postnr', 'Postnummer', 'required');
        $form->addRule('postby', 'Postby', 'required');
        $form->addRule('telefonnummer', 'Telefonnummer', 'required');
        $form->addRule('email', 'Du har ikke skrevet en gyldig e-mail', 'email');
        $form->addRule('kommune', 'Du har ikke skrevet en kommune', 'required');
        $form->addRule('nationalitet', 'Du har ikke skrevet en nationalitet', 'required');
        $form->addRule('cpr', 'Du skal skrive et cpr-nummer', 'required');
        $form->addRule('kontakt_navn', 'Du har ikke skrevet et gyldigt kontaktnavn', 'required');
        $form->addRule('kontakt_adresse', 'Du har ikke skrevet et gyldig kontaktadresse', 'required');
        $form->addRule('kontakt_postnr', 'Du har ikke skrevet en kontaktpostnummer', 'required');
        $form->addRule('kontakt_postby', 'Du har ikke skrevet en kontaktpostby', 'required');
        $form->addRule('kontakt_telefon', 'Du har ikke skrevet et nummer under telefon', 'required');
        $form->addRule('kontakt_arbejdstelefon', 'Du har ikke skrevet et nummer under arbejdstelefon', 'required');
        $form->addRule('kontakt_email', 'Du har ikke skrevet en gyldig kontakte-mail', 'email');

        $form->addGroupRule('uddannelse', 'Du skal vælge din uddannelsesmæssige baggrund', 'required', null);
        $form->addGroupRule('betaling', 'Du skal vælge, hvordan du betaler', 'required', null);

        return ($this->form = $form);
    }

    function renderHtmlDelete()
    {
        $tilmelding = $this->getTilmelding();
        if (!$tilmelding->delete()) {
            throw new Exception('Tilmeldingen kunne ikke slettes');
        } else {
            return new k_SeeOther($this->url('../'));
        }
    }

    function getTilmelding()
    {
        if ($this->tilmelding) {
            return $this->tilmelding;
        }
        return $this->tilmelding =  new VIH_Model_LangtKursus_Tilmelding($this->name());
    }
}