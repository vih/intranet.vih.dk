<?php
class VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Delete extends k_Controller
{
    function GET()
    {
        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name);
        if (!$tilmelding->delete()) {
            trigger_error('Tilmeldingen kunne ikke slettes', E_USER_ERROR);
        } else {
            throw new k_http_Redirect($this->context->url('../'));
        }
    }
}
