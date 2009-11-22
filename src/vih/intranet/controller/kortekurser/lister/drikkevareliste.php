<?php
class VIH_Intranet_Controller_KorteKurser_Lister_Drikkevareliste extends k_Controller
{
    function GET()
    {
        $kursus = new VIH_Model_KortKursus((int)$this->context->name);
        $deltagere = $kursus->getDeltagere();

        $data = array('kursus' => $kursus, 'deltagere' => $deltagere);

        return $this->render('vih/intranet/view/kortekurser/lister/drikkevareliste.tpl.php', $data);

    }
}