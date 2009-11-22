<?php
class VIH_Intranet_Controller_KorteKurser_Venteliste_Delete extends k_Controller
{
    function GET()
    {
        $venteliste = new VIH_Model_Venteliste(1, (int)$this->context->getKursusId(), $this->context->name);
        if (!$venteliste->delete()) {
            throw new Exeption('Kunne ikke slette ventelisten');
        } else {
            throw new k_http_Redirect($this->context->url('../'));
        }

    }
}
