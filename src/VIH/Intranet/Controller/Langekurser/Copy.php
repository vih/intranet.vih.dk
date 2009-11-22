<?php
class VIH_Intranet_Controller_Langekurser_Copy extends k_Controller
{
    function GET()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name);
        if ($id = $kursus->copy()) {
            throw new k_http_Redirect($this->context->url('../' . $id));
        }
    }
}
