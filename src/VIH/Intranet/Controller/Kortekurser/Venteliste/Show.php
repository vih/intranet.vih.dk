<?php
class VIH_Intranet_Controller_Kortekurser_Venteliste_Show extends k_Component
{
    function getKursusId()
    {
        return $this->context->getKursusId();
    }

    function renderHtmlDelete()
    {
        $venteliste = new VIH_Model_Venteliste(1, (int)$this->context->getKursusId(), $this->name());
        if (!$venteliste->delete()) {
            throw new Exeption('Kunne ikke slette ventelisten');
        } else {
            return new k_SeeOther($this->url('../'));
        }
    }
}