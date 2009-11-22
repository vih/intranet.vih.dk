<?php
class VIH_Intranet_Controller_KorteKurser_Venteliste_Index extends k_Controller
{
    function GET()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name);

        $venteliste = new VIH_Model_Venteliste(1, $kursus->get('id'));
        if(intval($venteliste->get('kursus_id')) == 0) {
            trigger_error("Ugyldigt kursus", E_USER_ERROR);
        }
        $liste = $venteliste->getList();

        $this->document->title = 'Venteliste til ' . $venteliste->get('kursusnavn');

        $data = array('venteliste' => $liste);

        return '<p>Listen er sorteret med de, der været længst på venteliste øverst</p>
        ' . $this->render('vih/intranet/view/kortekurser/venteliste-tpl.php', $data);
    }

    function getKursusId()
    {
        return $this->context->name;
    }

    function forward($name)
    {
        $next = new VIH_Intranet_Controller_KorteKurser_Venteliste_Show($this, $name);
        return $next->handleRequest();
    }

}

