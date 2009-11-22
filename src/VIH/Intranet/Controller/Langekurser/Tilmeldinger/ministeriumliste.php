<?php
class VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Ministeriumliste extends k_Controller
{
    function GET()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name);
        $list = new VIH_List_Ministerium();
        $list->update($kursus, $kursus->getTilmeldinger());

        throw new k_http_Response(200, $list->fetch());

    }
}
