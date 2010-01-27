<?php
class VIH_Intranet_Controller_Kortekurser_Venteliste_Delete extends k_Component
{
    function renderHtml()
    {
        $venteliste = new VIH_Model_Venteliste(1, (int)$this->context->getKursusId(), $this->context->name());
        if (!$venteliste->delete()) {
            throw new Exeption('Kunne ikke slette ventelisten');
        } else {
            return new k_SeeOther($this->context->url('../'));
        }

    }
}
