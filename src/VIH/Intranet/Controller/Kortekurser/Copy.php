<?php
class VIH_Intranet_Controller_Kortekurser_Copy extends k_Component
{
    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name());
        $new_kursus = new VIH_Model_KortKursus();
        if ($id = $new_kursus->copy($kursus)) {
            return new k_SeeOther($this->context->url('../' . $id));
        }

        throw new Exception('Could not copy course');
    }
}