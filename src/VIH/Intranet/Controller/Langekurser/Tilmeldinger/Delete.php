<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Delete extends k_Component
{
    function renderHtml()
    {
        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name());
        if (!$tilmelding->delete()) {
            trigger_error('Tilmeldingen kunne ikke slettes', E_USER_ERROR);
        } else {
            return new k_SeeOther($this->context->url('../'));
        }
    }
}
