<?php
class VIH_Intranet_Controller_LangeKurser_Delete extends k_Controller
{
    function GET()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name);
        if ($kursus->delete()) {
            throw new k_http_Redirect($this->context->url('../'));
        }
    }
}