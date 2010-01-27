<?php
class VIH_Intranet_Controller_Langekurser_Copy extends k_Component
{
    function renderHtml()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name());
        if ($id = $kursus->copy()) {
            return new k_SeeOther($this->context->url('../' . $id));
        }
    }
}
