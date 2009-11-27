<?php
class VIH_Intranet_Controller_Protokol_Show extends k_Controller
{
    function forward($name)
    {
        if ($name == 'delete') {
            $next = new VIH_Intranet_Controller_Protokol_Delete($this, $name);
            return $next->handleRequest();
        }
    }
}