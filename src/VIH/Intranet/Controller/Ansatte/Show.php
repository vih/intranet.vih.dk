<?php
class VIH_Intranet_Controller_Ansatte_Show extends k_Component
{
    protected $file_form;
    protected $form;
    protected $ansat;

    function dispatch()
    {
        $this->ansat = $this->getAnsat();
        if ($this->getAnsat()->get('id') == 0) {
            throw new k_PageNotFound();
        }
        return parent::dispatch();
    }

    function renderHtml()
    {
        $file = new VIH_FileHandler($this->ansat->get('pic_id'));
        $file->loadInstance('small');

        $this->document->setTitle('Ansat: ' . $this->ansat->get('navn'));
        $this->document->addOption('Ret', $this->url(null, array('edit')));

        return $file->getImageHtml(). $this->getFileForm()->toHTML();
    }

    function postMultipart()
    {
        if ($this->getForm()->validate()) {
            $file = new VIH_FileHandler;
            $id = $file->upload('userfile');

            if ($id) {
                $this->ansat->addPicture($file->get('id'));
            }

            return new k_SeeOther($this->url());
        }

        return $this->render();
    }

    function renderHtmlEdit()
    {
        $fag = VIH_Model_Fag::getList();

            $birthday = explode('-', $this->ansat->get('date_birthday'));
            $birthday['M'] = $birthday[1];
            $birthday['Y'] = $birthday[0];
            $birthday['d'] = $birthday[2];

            $date_ansat = explode('-', $this->ansat->get('date_ansat'));
            $date_ansat['M'] = $date_ansat[1];
            $date_ansat['Y'] = $date_ansat[0];
            $date_ansat['d'] = $date_ansat[2];

            $this->context->getForm()->setDefaults(array(
                                     'navn' => $this->ansat->get('navn'),
                                     'funktion_id' => $this->ansat->get('funktion_id'),
                                     'adresse' => $this->ansat->get('adresse'),
                                     'postnr' => $this->ansat->get('postnr'),
                                     'postby' => $this->ansat->get('postby'),
                                     'date_birthday' => $birthday,
                                     'date_ansat' => $date_ansat,
                                     'beskrivelse' => $this->ansat->get('beskrivelse'),
                                     'titel' => $this->ansat->get('titel'),
                                     'extra_info' => $this->ansat->get('extra_info'),
                                     'email' => $this->ansat->get('email'),
                                     'telefon' => $this->ansat->get('telefon'),
                                     'mobil' => $this->ansat->get('mobil'),
                                     'website' => $this->ansat->get('website'),
                                     'published' => $this->ansat->get('published')));

            if ($this->ansat->get('date_stoppet') == '0000-00-00') {
                $this->context->getForm()->setDefaults(array('date_stoppet' => ''));
            } else {
                $this->context->getForm()->setDefaults(array('date_stoppet' => $this->ansat->get('date_stoppet')));
            }

        $this->document->setTitle('Rediger underviser');
        return $this->getForm()->toHTML();
    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $input = $this->body();
            $input['date_stoppet'] = $this->body('date_stoppet');
            $input['date_stoppet'] = $input['date_stoppet']['Y'] . '-' . $input['date_stoppet']['M'] . '-' . $input['date_stoppet']['d'];

            $input['date_ansat'] = $this->body('date_ansat');
            $input['date_ansat'] = $input['date_ansat']['Y'] . '-' . $input['date_ansat']['M'] . '-' . $input['date_ansat']['d'];

            $input['date_birthday'] = $this->body('date_birthday');
            $input['date_birthday'] = $input['date_birthday']['Y'] . '-' . $input['date_birthday']['M'] . '-' . $input['date_birthday']['d'];
            if ($id = $this->ansat->save($input)) {
                return new k_SeeOther($this->url('../'));
            }
        }

        return $this->render();
    }

    function renderHtmlDelete()
    {
        if ($this->ansat->delete()) {
            return new k_SeeOther($this->url('../'));
        }
    }

    function getFileForm()
    {
        if ($this->file_form) {
            return $this->file_form;
        }

        $form = new HTML_QuickForm('ansatte', 'POST', $this->url());
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');

        return ($this->file_form = $form);
    }

    private function getForm()
    {
        return $this->context->getForm();
    }

    function getAnsat()
    {
        if (is_object($this->ansat)) {
            return $this->ansat;
        }
        return $this->ansat = new VIH_Model_Ansat($this->name());
    }
}