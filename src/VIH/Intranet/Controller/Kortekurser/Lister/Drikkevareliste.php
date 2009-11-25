<?php
class VIH_Intranet_Controller_Kortekurser_Lister_Drikkevareliste extends k_Component
{
    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus((int)$this->context->name());
        $deltagere = $kursus->getDeltagere();

        $data = array('kursus' => $kursus, 'deltagere' => $deltagere);

        return $this->render('VIH/Intranet/view/kortekurser/lister/drikkevareliste.tpl.php', $data);

    }
}