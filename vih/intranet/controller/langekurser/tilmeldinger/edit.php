<?php
class VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Edit extends k_Controller
{
    private $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name);

        foreach (VIH_Model_LangtKursus::getList('alle') AS $kursus) {
            $kurser[$kursus->get('id')] = $kursus->getKursusNavn();
        }

        $form = new HTML_QuickForm('tilemdling', 'POST', $this->url());
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
        $form->addElement('date', 'dato_start', 'Startdato');
        $form->addElement('date', 'dato_slut', 'Slutdato');
        $form->addElement('header', null, 'Priser');
        $form->addElement('text', 'pris_tilmeldingsgebyr', 'Tilmeldingsgebyr');
        $form->addElement('text', 'pris_uge', 'Ugepris');
        $form->addElement('text', 'pris_materiale', 'Materialer');
        $form->addElement('text', 'pris_noegledepositum', 'Nøgledepositum');
        $form->addElement('text', 'pris_rejsedepositum', 'Rejsedepositum');
        $form->addElement('text', 'pris_rejserest', 'Rejserestpris');
        $form->addElement('text', 'pris_rejselinje', 'Rejselinjepris');
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

    function GET()
    {

        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name);

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
            'pris_noegledepositum' => $tilmelding->get('pris_noegledepositum'),
            'pris_rejsedepositum' => $tilmelding->get('pris_rejsedepositum'),
            'pris_rejserest' => $tilmelding->get('pris_rejserest'),
            'pris_rejselinje' => $tilmelding->get('pris_rejselinje'),
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

        $this->document->title = 'Tilmelding';
        return $this->getForm()->toHTML();

    }

    function POST()
    {
        if ($this->getForm()->validate()) {
            $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name);
            $input = $this->POST->getArrayCopy();

            $input['dato_start'] = $input['dato_start']['Y'] . '-' . $input['dato_start']['M'] . '-' . $input['dato_start']['d'];
            $input['dato_slut'] = $input['dato_slut']['Y'] . '-' . $input['dato_slut']['M'] . '-' . $input['dato_slut']['d'];

            if ($id = $tilmelding->save($input)) {
                if (!$tilmelding->savePriser($input)) {
                    trigger_error('Kunne ikke opdatere priserne', E_USER_ERROR);
                }
                throw new k_http_Redirect($this->context->url());
            } else {
                trigger_error('Kunne ikke gemme oplysningerne om tilmeldingen', E_USER_ERROR);
            }
        } else {
            return $this->getForm()->toHTML();
        }

    }

}
