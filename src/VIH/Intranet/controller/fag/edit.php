<?php
class VIH_Intranet_Controller_Fag_Edit extends k_Controller
{
    private $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $faggruppe = VIH_Model_Fag_Gruppe::getList();

        foreach($faggruppe AS $grp) {
            $faggruppelist[$grp->get('id')] = $grp->get('navn');
        }

        $undervisere = VIH_Model_Ansat::getList('lærere');

        $form = new HTML_QuickForm('fag', 'POST', $this->url());
        $form->addElement('hidden', 'id');
        $form->addElement('text', 'navn', 'Navn');
        $form->addElement('select', 'faggruppe_id', 'Faggruppe', $faggruppelist);
        $form->addElement('text', 'identifier', 'Identifier');
        $form->addElement('textarea', 'kort_beskrivelse', 'Kort beskrivelse', array('cols' => 80, 'rows' => 5));
        $form->addElement('textarea', 'beskrivelse', 'Beskrivelse', array('cols' => 80, 'rows' => 20));
        $form->addElement('textarea', 'udvidet_beskrivelse', 'Udvidet beskrivelse', array('cols' => 80, 'rows' => 20));
        $form->addElement('header', null, 'Til søgemaskinerne');
        $form->addElement('text', 'title', 'Titel');
        $form->addElement('textarea', 'description', 'Beskrivelse');
        $form->addElement('textarea', 'keywords', 'Nøgleord');
        foreach ($undervisere AS $underviser) {
            $underviserlist[] = HTML_QuickForm::createElement('checkbox', $underviser->get('id'), null, $underviser->get('navn'));
        }
        $form->addGroup($underviserlist, 'underviser', 'Underviser', '<br />');
        $form->addElement('checkbox', 'published', 'Udgivet');
        $form->addElement('submit', null, 'Gem');
        return ($this->form = $form);
    }


    function GET()
    {
        $fag = VIH_Model_Fag::getList();
        foreach($fag AS $f) {
            $faglist[$f->get('id')] = $f->get('navn');
        }

        $fag = new VIH_Model_Fag($this->context->name);

        if ($fag->get('id') > 0) {
            $undervisere = $fag->getUndervisere();
            foreach ($undervisere AS $underviser) {
                $underviser_selected[$underviser->get('id')] = true;
            }
            $defaults = array('id' => $fag->get('id'),
                              'navn' => $fag->get('navn'),
                              'identifier' => $fag->get('identifier'),
                              'title' => $fag->get('title'),
                              'description' => $fag->get('description'),
                              'keywords' => $fag->get('keywords'),
                              'beskrivelse' => $fag->get('beskrivelse'),
                              'kort_beskrivelse' => $fag->get('kort_beskrivelse'),
                              'udvidet_beskrivelse' => $fag->get('udvidet_beskrivelse'),
                              'published' => $fag->get('published'),
                              'faggruppe_id' => $fag->get('faggruppe_id'),
                              'underviser' => $underviser_selected);

            $this->getForm()->setDefaults($defaults);

        }
        $this->document->title = 'Rediger fag';

        return $this->getForm()->toHTML();
    }

    function POST()
    {
        if ($this->getForm()->validate()) {
            $fag = new VIH_Model_Fag($this->context->name);
            $input = $this->POST->getArrayCopy();
            $input['navn'] = vih_handle_microsoft($input['navn']);
            $input['beskrivelse'] = vih_handle_microsoft($input['beskrivelse']);
            $input['kort_beskrivelse'] = vih_handle_microsoft($input['kort_beskrivelse']);
            $input['udvidet_beskrivelse'] = vih_handle_microsoft($input['udvidet_beskrivelse']);
            if (!isset($input['published'])) {
                $input['published'] = 0;
            }
            
            if ($id = $fag->save($input)) {
                if (!empty($this->POST['underviser'])) {
                    $fag->addUnderviser($this->POST['underviser']);
                }
                throw new k_http_Redirect($this->url('/fag/' . $fag->get('id')));
            }
        }
    }
}
