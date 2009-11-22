<?php
class VIH_Intranet_Controller_Betaling_Show extends k_Controller
{
    public $map = array('capture' => 'VIH_Intranet_Controller_Betaling_Capture',
                        'reverse' => 'VIH_Intranet_Controller_Betaling_Reverse');

    function GET()
    {
        return $this->name;
    }

    function forward($name)
    {
        if ($name == 'capture') {
            $next = new VIH_Intranet_Controller_Betaling_Capture($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'reverse') {
            $next = new VIH_Intranet_Controller_Betaling_Reverse($this, $name);
            return $next->handleRequest();
        }
    }
}
