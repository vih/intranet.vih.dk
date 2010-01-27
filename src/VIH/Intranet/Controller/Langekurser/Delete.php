<?php
class VIH_Intranet_Controller_Langekurser_Delete extends k_Component
{
    function renderHtml()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name());
        if ($kursus->delete()) {
            return new k_SeeOther($this->context->url('../'));
        }
    }
}