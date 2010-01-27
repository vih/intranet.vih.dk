<?php
class VIH_Intranet_Controller_Faciliteter_Delete extends k_Component
{
    function renderHtml()
    {
        $facilitet = new VIH_Model_Facilitet($this->context->name());
        if ($facilitet->delete()) {
            return new k_SeeOther($this->context->url('../'));
        }
    }

}
