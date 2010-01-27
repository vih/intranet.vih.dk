<?php
class VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Delete extends k_Component
{
    function renderHtml()
    {
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->context->name());
        if ($tilmelding->delete()) {
            return new k_SeeOther($this->context->url('../'));
        }
    }
}
