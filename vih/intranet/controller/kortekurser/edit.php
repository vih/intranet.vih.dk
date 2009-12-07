<?php
class VIH_Intranet_Controller_KorteKurser_Edit extends k_Controller
{
    private $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $kursus = new VIH_Model_KortKursus;

        $ansat = new VIH_Model_Ansat;
        $ansatte = $ansat->getList('lærere');

        foreach ($ansatte AS $ansat) {
            $ansatte_list[$ansat->get('id')] = $ansat->get('navn');
        }

        $form = new HTML_QuickForm('kortekurser', 'POST', $this->url());
        $form->addElement('hidden', 'id');
        $form->addElement('header', null, 'Kursusoplysninger');
        $form->addElement('text', 'navn', 'Kursusnavn');
        $form->addElement('header', null, 'Termin');
        $form->addElement('text', 'uge', 'Uge(r)');
        $form->addElement('date', 'dato_start', 'Startdato', 'd-m-Y');
        $form->addElement('date', 'dato_slut', 'Slutdato', 'd-m-Y');
        $form->addElement('select', 'ansat_id', 'Kursusleder', $ansatte_list);
        $form->addElement('select', 'gruppe_id', 'Gruppe', $kursus->gruppe);
        $form->addElement('text', 'begyndere', 'Begyndere');
        //$form->addElement('select', 'indkvartering_key', 'Indkvartering', $kursus->indkvartering);
        $form->addElement('header', null, 'Indkvartering');

        $gateway = new VIH_Model_KortKursus_Indkvartering($kursus);

        foreach ($gateway->getTypes() as $key => $indkvartering) {
            $form->addElement('checkbox', 'indkvartering['.$key.'][chosen]', $indkvartering);
            $form->addElement('text', 'indkvartering['.$key.'][price]', 'Pris');
        }
        $form->addElement('header', null, 'Tal om kurset');
        $form->addElement('text', 'pladser', 'Antal pladser');
        $form->addElement('text', 'vaerelser', 'Antal værelser');
        $form->addElement('text', 'minimumsalder', 'Minimumsalder');
        $form->addElement('header', null, 'Priser');
        $form->addElement('text', 'pris', 'Pris');
        $form->addElement('text', 'pris_boern', 'Børnepris');
        $form->addElement('text', 'pris_depositum', 'Depositum');
        $form->addElement('text', 'pris_afbestillingsforsikring', 'Afbestillingsforsikring');
        $form->addElement('header', null, 'Beskrivelse');
        $form->addElement('textarea', 'beskrivelse', 'Beskrivelse', array('rows'=>20, 'cols'=>80));
        $form->addElement('checkbox', 'tilmeldingsmulighed', '', 'Tilmeldingsmulighed');
        $form->addElement('checkbox', 'nyhed', '', 'Marker som nyhed');
        $form->addElement('checkbox', 'published', '', 'Udgivet');
        $form->addElement('header', null, 'Til søgemaskinerne');
        $form->addElement('textarea', 'title', 'Title');
        $form->addElement('textarea', 'description', 'Beskrivelse');
        $form->addElement('textarea', 'keywords', 'Nøgleord');
        $form->addElement('submit', null, 'Gem');
        return ($this->form = $form);
    }

    function GET()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name);

        $gateway = new VIH_Model_KortKursus_Indkvartering($kursus);

        foreach ($gateway->getActive() as $active) {
            $indkvartering[$active['indkvartering_key']]['chosen'] = 1;
            $indkvartering[$active['indkvartering_key']]['price'] = $active['price'];
        }

        $this->getForm()->setDefaults(array(
            'id' => $kursus->get('id'),
            'navn' => $kursus->get('navn'),
            'uge' => $kursus->get('uge'),
            'nyhed' => $kursus->get('nyhed'),
            'dato_start' => $kursus->get('dato_start'),
            'dato_slut' => $kursus->get('dato_slut'),
            'ansat_id' => $kursus->get('ansat_id'),
            'indkvartering' => $indkvartering,
            'begyndere' => $kursus->get('begyndere'),
            'pladser' => $kursus->get('pladser'),
            'vaerelser' => $kursus->get('vaerelser'),
            'minimumsalder' => $kursus->get('minimumsalder'),
            'pris' => $kursus->get('pris'),
            'pris_boern' => $kursus->get('pris_boern'),
            'pris_depositum' => $kursus->get('pris_depositum'),
            'pris_afbestillingsforsikring' => $kursus->get('pris_afbestillingsforsikring'),
            'beskrivelse' => $kursus->get('beskrivelse'),
            'tilmeldingsmulighed' => $kursus->get('tilmeldingsmulighed'),
            'published' => $kursus->get('published'),
            'title' => $kursus->get('title'),
            'description' => $kursus->get('description'),
            'keywords' => $kursus->get('keywords'),
            'gruppe_id' =>  $kursus->get('gruppe_id')
        ));

        $this->document->title = 'Rediger kursus';
        $this->document->navigation = array($this->context->url('../', array('filter' => $kursus->get('gruppe_id')))  => 'Tilbage til kurser');

        return $this->getForm()->toHTML();
    }

    function POST()
    {
        if ($this->getForm()->validate()) {
            $kursus = new VIH_Model_KortKursus($this->context->name);
            $values = $this->POST->getArrayCopy();

            $values['dato_start'] = $values['dato_start']['Y'] . '-' . $values['dato_start']['M'] . '-' . $values['dato_start']['d'];
            $values['dato_slut'] = $values['dato_slut']['Y'] . '-' . $values['dato_slut']['M'] . '-' . $values['dato_slut']['d'];
            $values['beskrivelse'] = vih_handle_microsoft($values['beskrivelse']);
            if (empty($values['tilmeldingsmulighed'])) $values['tilmeldingsmulighed'] = 0;
            if (empty($values['published'])) $values['published'] = 0;
            if (empty($values['nyhed'])) $values['nyhed'] = 0;

            if ($id = $kursus->save($values)) {
                $gateway = new VIH_Model_KortKursus_Indkvartering($kursus);
                $gateway->flushAll();
                foreach ($values['indkvartering'] as $key => $indkvartering) {
                    if (isset($indkvartering['chosen'])) {
                        $gateway->activate($key, $indkvartering['price']);
                    }
                }
                throw new k_http_Redirect($this->url('../'));
            }
        }

    }
}
