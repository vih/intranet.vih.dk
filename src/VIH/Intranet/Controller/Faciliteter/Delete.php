<?php
class VIH_Intranet_Controller_Faciliteter_Delete extends k_Controller
{
    function GET()
    {
        $facilitet = new VIH_Model_Facilitet($this->context->name);
        if ($facilitet->delete()) {
            throw new k_http_Redirect($this->context->url('../'));
        }
    }

}
