<?php
class VIH_Intranet_Controller_KorteKurser_Tilmeldinger_Delete extends k_Controller
{
    function GET()
    {
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->context->name);
        if ($tilmelding->delete()) {
            throw new k_http_Redirect($this->context->url('../'));
        }
    }
}
