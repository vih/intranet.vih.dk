<?php
class VIH_Intranet_Controller_Nyheder_Show extends k_Controller
{
    public $map = array('edit'   => 'VIH_Intranet_Controller_Nyheder_Edit',
                        'delete' => 'VIH_Intranet_Controller_Nyheder_Delete');

    private $form;

    function getForm()
    {
        if ($this->form) return $this->form;
        $this->form = new HTML_QuickForm('nyhed', 'post', $this->url('./'));
        $this->form->addElement('hidden', 'id');
        $this->form->addElement('file', 'userfile', 'Fil');
        $this->form->addElement('submit', null, 'Upload');
        
        return $this->form;
    }

    function GET()
    {
        $nyhed = new VIH_News($this->name);

        if (!empty($this->GET['sletbillede']) AND is_numeric($this->GET['sletbillede'])) {
            $nyhed->deletePicture($this->GET['sletbillede']);
        }

        $pictures = $nyhed->getPictures();
        $pic_html = '';
        foreach($pictures AS $pic) {
            $file = new VIH_FileHandler($pic['file_id']);
            if ($file->get('id')) {
                $file->loadInstance('small');
            }
            $pic_html .= '<div>' . $file->getImageHtml() . '<br /><a href="'. $this->url('./') . '?sletbillede='.$pic['file_id'] . '">Slet</a></div>';
        }

        $this->document->title = 'Nyhed: ' . $nyhed->get('overskrift');
        $this->document->options = array($this->url('edit') => 'Ret'); 
        // $tpl->set('title', 'Nyhed');
        return '<div>'.vih_autoop($nyhed->get('tekst')).'</div> ' . $this->getForm()->toHTML() . $pic_html . $nyhed->get('date_updated');
    }

    function POST()
    {
        $nyhed = new VIH_News($this->name);
        if ($this->getForm()->validate()) {
            $file = new VIH_FileHandler;
            if($file->upload('userfile')) {
                $nyhed->addPicture($file->get('id'));
            }
        }
        throw new k_http_Redirect($this->url());
    }

    function forward($name)
    {
        if ($name == 'edit') {
            $next = new VIH_Intranet_Controller_Nyheder_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'delete') {
            $next = new VIH_Intranet_Controller_Nyheder_Delete($this, $name);
            return $next->handleRequest();
        } else {
            return self::GET();
        }
    }
}
