<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Fag_Index extends k_Controller
{
    function GET()
    {
        $this->document->title = 'Fag';
        $this->document->options = array($this->url('create') => 'Opret',
                                         $this->url('faggrupper') => 'Faggrupper'); 

        $data = array('list' => VIH_Model_Fag::getList());

        return $this->render(dirname(__FILE__) . '/../../view/fag/liste-tpl.php', $data);
    }

    function forward($name)
    {
        if ($name == 'faggrupper') {
            $next = new VIH_Intranet_Controller_Fag_Gruppe_Index($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'create') {
            $next = new VIH_Intranet_Controller_Fag_Edit($this, $name);
            return $next->handleRequest();
        }
        $next = new VIH_Intranet_Controller_Fag_Show($this, $name);
        return $next->handleRequest();
    }
}