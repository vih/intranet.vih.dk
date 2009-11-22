<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Restance extends k_Controller
{
    function GET()
    {
        $this->document->title = 'Tilmeldinger i restance';
        $data = array('tilmeldinger' => VIH_Model_LangtKursus_Tilmelding::getList('forfaldne'));
        return $this->render('VIH/Intranet/view/langekurser/tilmeldinger-tpl.php', $data);
    }
}