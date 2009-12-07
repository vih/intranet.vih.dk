<?php
class VIH_Intranet_Controller_KorteKurser_Lister_Ministerium extends k_Controller
{
    function GET()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name);

        $data = array('kursus' => $kursus,
                      'deltagere' => $kursus->getDeltagere());

        return $this->render('vih/list/templates/ministerium.tpl.php', $data);
    }
}