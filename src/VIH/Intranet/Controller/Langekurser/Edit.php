<?php
class VIH_Intranet_Controller_Langekurser_Edit extends k_Component
{
    private $form;

    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $date_options = array('minYear' => date('Y') - 10, 'maxYear' => date('Y') + 5);

        $liste_ansvarlige = array();
        $ansvarlige = VIH_Model_Ansat::getList('lærere');
        foreach ($ansvarlige AS $ansvarlig) {
            $liste_ansvarlige[$ansvarlig->get('id')] = $ansvarlig->get('navn');
        }

        $this->form = new HTML_QuickForm('langekurser', 'POST', $this->url('./'));
        $this->form->addElement('header', null, 'Oplysninger om kurset');
        $this->form->addElement('text', 'navn', 'Navn');
        $this->form->addElement('text', 'shorturl', 'Søgestreng');
        $this->form->addElement('textarea', 'tekst_diplom', 'Tekst til diplomet');
        $this->form->addElement('select', 'ansat_id', 'Ansvarlig', $liste_ansvarlige);
        $this->form->addElement('textarea', 'beskrivelse', 'Beskrivelse', array('rows' => 20, 'cols' => 80));
        $this->form->addElement('header', null, 'Termin');
        $this->form->addElement('text', 'ugeantal', 'Uger');
        $this->form->addElement('date', 'dato_start', 'Startdato', $date_options);
        $this->form->addElement('date', 'dato_slut', 'Slutdato', $date_options);
        $this->form->addElement('header', null, 'Søgemaskinerne');
        $this->form->addElement('text', 'title', 'Titel');
        $this->form->addElement('textarea', 'description', 'Kort beskrivelse');
        $this->form->addElement('textarea', 'keywords', 'Nøgleord');
        $this->form->addElement('header', null, 'Priser');
        $this->form->addElement('text', 'pris_uge', 'Ugepris');
        $this->form->addElement('text', 'pris_materiale', 'Materialepris');
        $this->form->addElement('text', 'pris_rejsedepositum', 'Rejsedepositum');
        $this->form->addElement('text', 'pris_rejserest', 'Restbeløb til rejse');
        $this->form->addElement('text', 'pris_rejselinje', 'Rejselinje'); // afrikalinjen
        $this->form->addElement('text', 'pris_noegledepositum', 'Nøgledepositum'); // afrikalinjen
        $this->form->addElement('text', 'pris_tilmeldingsgebyr', 'Tilmeldingsgebyr'); // afrikalinjen
        $this->form->addElement('checkbox', 'published', 'Udgivet');
        $this->form->addElement('submit', null, 'Gem');

        return $this->form;
    }

    function renderHtml()
    {
        if (is_numeric($this->context->name()) AND $this->name() == 'edit') {
            $kursus = new VIH_Model_LangtKursus($this->context->name());
            $defaults = array('id' => $kursus->get('id'),
                              'navn' => $kursus->get('navn'),
                              'shorturl' => $kursus->get('shorturl'),
                              'belong_to_id' => $kursus->get('belong_to_id'),
                              'tekst_diplom' => $kursus->get('tekst_diplom'),
                              'ugeantal' => $kursus->get('ugeantal'),
                              'dato_start' => $kursus->get('dato_start'),
                              'dato_slut' => $kursus->get('dato_slut'),
                              'ansat_id' => $kursus->get('ansat_id'),
                              'title' => $kursus->get('title'),
                              'keywords' => $kursus->get('keywords'),
                              'description' => $kursus->get('description'),
                              'manchet' => $kursus->get('manchet'),
                              'beskrivelse' => $kursus->get('beskrivelse'),
                              'pris_uge' => $kursus->get('pris_uge'),
                              'pris_materiale' => $kursus->get('pris_materiale'),
                              'pris_rejsedepositum' => $kursus->get('pris_rejsedepositum'),
                              'pris_rejserest' => $kursus->get('pris_rejserest'),
                              'pris_rejselinje' => $kursus->get('pris_rejselinje'), // afrikalinjen
                              'pris_noegledepositum' => $kursus->get('pris_noegledepositum'),
                              'pris_tilmeldingsgebyr' => $kursus->get('pris_tilmeldingsgebyr'),
                              'published' => $kursus->get('published')
            );
            $this->getForm()->setDefaults($defaults);
        } else {
            $kursus_id = 0;
            $this->getForm()->setDefaults(array('pris_tilmeldingsgebyr' => LANGEKURSER_STANDARDPRISER_TILMELDINGSGEBYR));
        }

        $this->document->setTitle('Rediger kursus');
        $this->document->options = array($this->url('../') => 'Luk uden at gemme');
        return $this->getForm()->toHTML();
    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $kursus = new VIH_Model_LangtKursus($this->context->name());
            $var = $this->body();
            $var["dato_start"] = $var["dato_start"]['Y']."-".$var["dato_start"]['M']."-".$var["dato_start"]['d'];
            $var["dato_slut"] = $var["dato_slut"]['Y']."-".$var["dato_slut"]['M']."-".$var["dato_slut"]['d'];
            $var['navn'] = vih_handle_microsoft($var['navn']);
            $var['beskrivelse'] = vih_handle_microsoft($var['beskrivelse']);
            $var['title'] = vih_handle_microsoft($var['title']);
            if (!isset($var['published'])) {
                $var['published'] = 0;
            }
            if ($id = $kursus->save($var)) {
                return new k_SeeOther($this->url('../'));
            }
        }
    }
}
