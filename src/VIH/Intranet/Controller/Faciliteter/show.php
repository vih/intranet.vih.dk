<?php
class VIH_Intranet_Controller_Faciliteter_Show extends k_Controller
{
    public $map = array('edit' => 'VIH_Intranet_Controller_Faciliteter_Edit',
                        'delete' => 'VIH_Intranet_Controller_Faciliteter_Delete');

    private $form;

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

    function GET()
    {
        $facilitet = new VIH_Model_Facilitet($this->name);
        if (!empty($this->GET['sletbillede']) AND is_numeric($this->GET['sletbillede'])) {
            if (!$facilitet->deletePicture($this->GET['sletbillede'])) {
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
                $extra_html .= ' <br /><a href="'.$this->url('/faciliteter/' . $this->name, array('sletbillede' => $facilitet->get('pic_id'))).'">slet billede</a>';
            }
        }
        if (empty($extra_html)) {
            $extra_html = $this->getForm()->toHTML();
        }

        $this->document->title = $facilitet->get('navn');
        $this->document->options = array($this->url('edit') => 'Ret'); 

        return '<div>'.nl2br($facilitet->get('beskrivelse')).'</div>   ' . $extra_html;
    }

    function POST()
    {
        $facilitet = new VIH_Model_Facilitet($this->name);
        if ($this->getForm()->validate()) {
            $file = new VIH_FileHandler;
            if($file->upload('userfile')) {
                $facilitet->addPicture($file->get('id'));
                throw new k_http_Redirect($this->url());
            }
        }

    }

    function forward($name)
    {
        if ($name == 'edit') {
            $next = new VIH_Intranet_Controller_Faciliteter_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'delete') {
            $next = new VIH_Intranet_Controller_Faciliteter_Delete($this, $name);
            return $next->handleRequest();
        } else {
            return self::GET();
        }
    }
}
