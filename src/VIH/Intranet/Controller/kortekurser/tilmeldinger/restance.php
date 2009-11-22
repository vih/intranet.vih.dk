<?php
class VIH_Intranet_Controller_KorteKurser_Tilmeldinger_Restance extends k_Controller {

    function GET()
    {
        $this->document->title = 'Tilmeldinger i restance';

        $data = array('tilmeldinger' => VIH_Model_KortKursus_Tilmelding::getList('restance', 400));

        return $this->render('vih/intranet/view/kortekurser/tilmeldinger-tpl.php', $data);
    }
}