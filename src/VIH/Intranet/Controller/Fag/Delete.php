<?php
class VIH_Intranet_Controller_Fag_Delete extends k_Component
{
    function renderHtml()
    {
        $fag = new VIH_Model_Fag($this->context->name());
        if ($fag->delete()) {
            return new k_SeeOther($this->context->url('../'));
        }
    }

}
