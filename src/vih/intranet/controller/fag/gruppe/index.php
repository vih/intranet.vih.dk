<?php
/**
 * Controller for the intranet
 */

class VIH_Intranet_Controller_Fag_Gruppe_Index extends k_Controller
{
    function GET()
    {
        $this->document->title = 'Faggrupper';
        $this->document->options = array(
            $this->url('create') => 'Opret',
            $this->url('../') => 'Tilbage til fag'
        );

        $data = array('faggrupper' => VIH_Model_Fag_Gruppe::getList());

        return $this->render('vih/intranet/view/fag/faggrupper-tpl.php', $data);
    }

 function forward($name)
    {
        if ($name == 'create') {
            $next = new VIH_Intranet_Controller_Fag_Gruppe_Edit($this, $name);
            return $next->handleRequest();

        }
        $next = new VIH_Intranet_Controller_Fag_Gruppe_Show($this, $name);
        return $next->handleRequest();
    }
}