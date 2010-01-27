<?php
class VIH_Intranet_Controller_Nyheder_Delete extends k_Component
{
    function renderHtml()
    {
        $nyhed= new VIH_News($this->context->name());
        if ($nyhed->delete()) {
            return new k_SeeOther($this->context->url('../'));
        }
    }

}
