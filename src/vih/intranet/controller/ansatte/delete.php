<?php
class VIH_Intranet_Controller_Ansatte_Delete extends k_Controller
{
    function GET()
    {
        $ansat = new VIH_Model_Ansat($this->context->name);
        if ($ansat->delete()) {
            throw new k_http_Redirect($this->context->url('../'));
        }
    }

}
