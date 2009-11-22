<?php
class VIH_Intranet_Controller_Nyheder_Delete extends k_Controller
{
    function GET()
    {
        $nyhed= new VIH_News($this->context->name);
        if ($nyhed->delete()) {
            throw new k_http_Redirect($this->context->url('../'));
        }
    }

}
