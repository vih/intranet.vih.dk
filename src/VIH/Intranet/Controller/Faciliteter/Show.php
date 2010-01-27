<?php
class VIH_Intranet_Controller_Faciliteter_Show extends k_Component
{
    public $map = array('edit' => 'VIH_Intranet_Controller_Faciliteter_Edit',
                        'delete' => 'VIH_Intranet_Controller_Faciliteter_Delete');

    private $form;

    function map($name)
    {
        return $this->map[$name];
    }

    function getForm()
    {
       if ($this->form) {
           return $this->form;
       }

        $form = new HTML_QuickForm('faciliteter', 'POST', $this->url());
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');

        return ($this->form = $form);
    }

    function renderHtml()
    {
        $facilitet = new VIH_Model_Facilitet($this->name());
        if (is_numeric($this->query('sletbillede'))) {
            if (!$facilitet->deletePicture($this->query('sletbillede'))) {
                trigger_error('Kan ikke slette billedet', E_USER_ERROR);
            }
            $facilitet->load();
        }

        $file = new VIH_FileHandler($facilitet->get('pic_id'));

        $extra_html = '';

        if ($file->get('id') > 0) {
            $file->loadInstance('small');
            $extra_html = $file->getImageHtml();
            if (!empty($extra_html)) {
                $extra_html .= ' <br /><a href="'.$this->url('/faciliteter/' . $this->name(), array('sletbillede' => $facilitet->get('pic_id'))).'">slet billede</a>';
            }
        }
        if (empty($extra_html)) {
            $extra_html = $this->getForm()->toHTML();
        }

        $this->document->setTitle($facilitet->get('navn'));
        $this->document->options = array($this->url('edit') => 'Ret');

        return '<div>'.nl2br($facilitet->get('beskrivelse')).'</div>   ' . $extra_html;
    }

    function postForm()
    {
        $facilitet = new VIH_Model_Facilitet($this->name());
        if ($this->getForm()->validate()) {
            $file = new VIH_FileHandler;
            if($file->upload('userfile')) {
                $facilitet->addPicture($file->get('id'));
                return new k_SeeOther($this->url());
            }
        }
    }
}
