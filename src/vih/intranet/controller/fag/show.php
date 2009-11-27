<?php
class VIH_Intranet_Controller_Fag_Show extends k_Controller
{
    public $map = array('edit' => 'VIH_Intranet_Controller_Fag_Edit',
                        'delete' => 'VIH_Intranet_Controller_Fag_Delete');

    private $form;


    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm('fag', 'POST', $this->url());
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');

        return ($this->form = $form);

    }

    function GET()
    {
        $db = $this->registry->get('database:pear');

        if (!empty($_GET['sletbillede']) AND is_numeric($_GET['sletbillede'])) {
            $fields = array('date_updated', 'pic_id');
            $values = array('NOW()', 0);

            $sth = $db->autoPrepare('langtkursus_fag', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $this->name);
            $res = $db->execute($sth, $values);

            if (PEAR::isError($res)) {
                echo $res->getMessage();
            }
        }

        $fag = new VIH_Model_Fag($this->name);

        $file = new VIH_FileHandler($fag->get('pic_id'));
        if ($file->get('id') > 0) {
            $file->loadInstance('small');
            $extra_html = $file->getImageHtml();
            if (!empty($extra_html)) {
                $extra_html .= ' <br /><a href="?sletbillede='.$fag->get('pic_id').'">slet billede</a>';
            }
        }
        if (empty($extra_html)) {
            $extra_html = $this->getForm()->toHTML();
        }

        $this->document->title = 'Fag: ' . $fag->get('navn');
        $this->document->options = array($this->url('/langekurser') => 'Kurser',
                                         $this->context->url() => 'Fagoversigt',
                                         $this->url('edit') => 'Ret');

        return '<div>'.vih_autoop($fag->get('beskrivelse')).'</div>' . $extra_html;
    }

    function POST()
    {
        $db = $this->registry->get('database:pear');
        if ($this->getForm()->validate()) {
            $file = new VIH_FileHandler;
            if($file->upload('userfile')) {
                $fields = array('date_updated', 'pic_id');
                $values = array('NOW()', $file->get('id'));
                $sth = $db->autoPrepare('langtkursus_fag', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $this->name);
                $res = $db->execute($sth, $values);

                if (PEAR::isError($res)) {
                    echo $res->getMessage();
                }

                throw new k_http_Redirect($this->url());
            }
        }

    }

    function forward($name)
    {
        if ($name == 'edit') {
            $next = new VIH_Intranet_Controller_Fag_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'delete') {
            $next = new VIH_Intranet_Controller_Fag_Delete($this, $name);
            return $next->handleRequest();
        } else {
            return self::GET();
        }
    }
}
