<?php
class VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Elevugerliste extends k_Controller
{
    function GET()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name);

        $data = array('kursus' => $kursus, 'tilmeldinger' => $kursus->getTilmeldinger());
        return $this->render('vih/intranet/view/langekurser/elevuger.tpl.php', $data);
    }
}


?>