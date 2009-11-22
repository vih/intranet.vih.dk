<?php
class VIH_Intranet_Controller_Ansatte_Edit extends k_Controller
{
    private $form;

    private function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        
        $ansat = new VIH_Model_Ansat($this->context->name);

        $date_options = array('minYear' => date('Y') - 70,
                              'maxYear' => date('Y'),
                              'addEmptyOption' => 'true',
                              'emptyOptionValue' => '',
                              'emptyOptionText' => 'Vælg');

        $this->form = new HTML_QuickForm('fag', 'POST', $this->url('./'));
        $this->form->addElement('text', 'navn', 'Navn');
        $this->form->addElement('text', 'adresse', 'Adresse');
        $this->form->addElement('text', 'postnr', 'Postnr');
        $this->form->addElement('text', 'postby', 'Postby');
        $this->form->addElement('date', 'date_birthday', 'Fødselsdag', $date_options);
        $this->form->addElement('date', 'date_ansat', 'Ansat', $date_options);
        $this->form->addElement('date', 'date_stoppet', 'Stoppet', $date_options);
        $this->form->addElement('text', 'titel', 'Titel');
        $this->form->addElement('select', 'funktion_id', 'Funktion', $ansat->funktion);
        $this->form->addElement('text', 'email', 'E-mail');
        $this->form->addElement('text', 'website', 'Hjemmeside');
        $this->form->addElement('text', 'telefon', 'Telefon');
        $this->form->addElement('text', 'mobil', 'Mobil');
        $this->form->addElement('textarea', 'beskrivelse', 'Beskrivelse', array('cols' => 80, 'rows' => 20));
        $this->form->addElement('textarea', 'extra_info', 'Ekstra oplysninger', array('cols' => 80, 'rows' => 20));
        $this->form->addElement('checkbox', 'published', 'Udgivet');
        $this->form->addElement('submit', null, 'Gem');

        return ($this->form);        
    }

    function GET()
    {
        $ansat = new VIH_Model_Ansat($this->context->name);
        $fag = VIH_Model_Fag::getList();

        if ($ansat->get('id')) {

            $birthday = explode('-', $ansat->get('date_birthday'));
            $birthday['M'] = $birthday[1];
            $birthday['Y'] = $birthday[0];
            $birthday['d'] = $birthday[2];

            $date_ansat = explode('-', $ansat->get('date_ansat'));
            $date_ansat['M'] = $date_ansat[1];
            $date_ansat['Y'] = $date_ansat[0];
            $date_ansat['d'] = $date_ansat[2];

            $this->getForm()->setDefaults(array(
                                     'navn' => $ansat->get('navn'),
                                     'funktion_id' => $ansat->get('funktion_id'),
                                     'adresse' => $ansat->get('adresse'),
                                     'postnr' => $ansat->get('postnr'),
                                     'postby' => $ansat->get('postby'),
                                     'date_birthday' => $birthday,
                                     'date_ansat' => $date_ansat,
                                     'beskrivelse' => $ansat->get('beskrivelse'),
                                     'titel' => $ansat->get('titel'),
                                     'extra_info' => $ansat->get('extra_info'),
                                     'email' => $ansat->get('email'),
                                     'telefon' => $ansat->get('telefon'),
                                     'mobil' => $ansat->get('mobil'),
                                     'website' => $ansat->get('website'),
                                     'published' => $ansat->get('published')));

            if ($ansat->get('date_stoppet') == '0000-00-00') {
                $this->form->setDefaults(array('date_stoppet' => ''));
            } else {
                $this->form->setDefaults(array('date_stoppet' => $ansat->get('date_stoppet')));
            }
        }

        $this->document->title = 'Rediger underviser';
        return $this->getForm()->toHTML();

    }

    function POST()
    {
        if ($this->getForm()->validate()) {
            $ansat = new VIH_Model_Ansat($this->context->name);
            $input = $this->POST->getArrayCopy();
            $input['date_stoppet'] = $this->POST['date_stoppet'];
            $input['date_stoppet'] = $input['date_stoppet']['Y'] . '-' . $input['date_stoppet']['M'] . '-' . $input['date_stoppet']['d'];

            $input['date_ansat'] = $this->POST['date_ansat'];
            $input['date_ansat'] = $input['date_ansat']['Y'] . '-' . $input['date_ansat']['M'] . '-' . $input['date_ansat']['d'];

            $input['date_birthday'] = $this->POST['date_birthday'];
            $input['date_birthday'] = $input['date_birthday']['Y'] . '-' . $input['date_birthday']['M'] . '-' . $input['date_birthday']['d'];
            if ($id = $ansat->save($input)) {
                throw new k_http_Redirect($this->url('/ansatte'));
            }

        }
        
        $this->title = 'Rediger Underviser'; 
        return $this->getForm()->toHTML();
    }
}
