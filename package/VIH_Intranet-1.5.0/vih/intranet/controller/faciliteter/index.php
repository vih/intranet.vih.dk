<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Faciliteter_Index extends k_Controller
{
    function GET()
    {
        $this->document->title = 'Faciliteter';
        $this->document->options = array($this->url('edit') => 'Opret');

        $data = array('faciliteter' => VIH_Model_Facilitet::getList('all'));

        return $this->render(dirname(__FILE__) . '/../../view/faciliteter/faciliteter-tpl.php', $data);
    }

    function forward($name)
    {
        $next = new VIH_Intranet_Controller_Faciliteter_Show($this, $name);
        return $next->handleRequest();
    }
}
