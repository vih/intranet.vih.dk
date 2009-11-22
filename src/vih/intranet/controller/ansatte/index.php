<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Ansatte_Index extends k_Controller
{
    function GET()
    {
        $ansat = new VIH_Model_Ansat;
        $ansatte = $ansat->getList();

        $this->document->title = 'Ansatte';
        $this->document->options = array($this->url('create') => 'Opret');

        $data = array('list' => $ansatte);

        return $this->render('vih/intranet/view/ansatte/list-tpl.php', $data);

    }

    function forward($name)
    {
        if ($name == 'create') {
            $next = new VIH_Intranet_Controller_Ansatte_Edit($this, $name);
            return $next->handleRequest();
        }
        $next = new VIH_Intranet_Controller_Ansatte_Show($this, $name);
        return $next->handleRequest();
    }
}
