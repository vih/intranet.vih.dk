<?php
class VIH_Intranet_Controller_KorteKurser_Begyndere extends k_Controller
{
    function GET()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name);
        if ($kursus->get('gruppe_id') != 1) {
            echo '';
            exit;
        }
        $begyndere = $kursus->getBegyndere();

        throw new k_http_Response(200, $begyndere);
    }
}