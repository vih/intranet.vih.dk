<?php
class VIH_Intranet_Controller_Ansatte_Show extends k_Controller
{
    public $map = array('edit' => 'VIH_Intranet_Controller_Ansatte_Edit',
                        'delete' => 'VIH_Intranet_Controller_Ansatte_Delete');

    public $form;

    function getForm()
    {
       if ($this->form) {
           return $this->form;
       }

        $form = new HTML_QuickForm('ansatte', 'POST', $this->url());
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');

        return ($this->form = $form);
    }

    function GET()
    {
        $ansat = new VIH_Model_Ansat($this->name);
        $file = new VIH_FileHandler($ansat->get('pic_id'));
        $file->loadInstance('small');

        $this->document->title = 'Ansat: ' . $ansat->get('navn');
        $this->document->options = array($this->url('edit') => 'Ret');

        return $file->getImageHtml(). $this->getForm()->toHTML();
    }

    function POST()
    {
        $ansat = new VIH_Model_Ansat($this->name);

        if ($this->getForm()->validate()) {
            $file = new VIH_FileHandler;
            $id = $file->upload('userfile');
            if($id) {
                $ansat->addPicture($file->get('id'));
            }

            throw new k_http_Redirect($this->url());
        }
    }

    function forward($name)
    {
        if ($name == 'edit') {
            $next = new VIH_Intranet_Controller_Ansatte_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'delete') {
            $next = new VIH_Intranet_Controller_Ansatte_Delete($this, $name);
            return $next->handleRequest();
        } else {
            return self::GET();
        }
    }
}
