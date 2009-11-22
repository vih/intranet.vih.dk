<?php
class VIH_Intranet_Controller_KorteKurser_Venteliste_Show extends k_Controller
{
    function forward($name)
    {
        if ($name == 'delete') {
            $next = new VIH_Intranet_Controller_KorteKurser_Venteliste_Delete($this, $name);
        } elseif ($name == 'edit') {
            $next = new VIH_Intranet_Controller_KorteKurser_Venteliste_Edit($this, $name);
        }
        return $next->handleRequest();
    }

    function getKursusId()
    {
        return $this->context->getKursusId();
    }
}