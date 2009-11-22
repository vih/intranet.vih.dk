<?php
class VIH_Intranet_Controller_KorteKurser_Copy extends k_Controller
{
    function GET()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name);
        $new_kursus = new VIH_Model_KortKursus();
        if ($id = $new_kursus->copy($kursus)) {
            throw new k_http_Redirect($this->context->url('../' . $id));
        }

        throw new Exception('Could not copy course');
    }
}