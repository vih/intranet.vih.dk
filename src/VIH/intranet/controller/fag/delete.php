<?php
class VIH_Intranet_Controller_Fag_Delete extends k_Controller
{
    function GET()
    {
        $fag = new VIH_Model_Fag($this->context->name);
        if ($fag->delete()) {
            throw new k_http_Redirect($this->context->url('../'));
        }
    }

}
