<?php
class VIH_Intranet_Controller_KorteKurser_Tilmeldinger extends k_Controller
{
    function GET()
    {
        $kursus = new VIH_Model_KortKursus((int)$this->context->name);

        $tilmeldinger = $kursus->getTilmeldinger();

        $this->document->title = 'Tilmeldinger til ' . $kursus->getKursusNavn();
        $this->document->options = array($this->url('/kortekurser') => 'Kurser',
                                         $this->context->url('deltagere') => 'Deltagere');

        $data = array('tilmeldinger' => $tilmeldinger,
                      'vis_besked' => 'ja',
                      'caption' => 'Tilmeldinger');

        return $this->render('vih/intranet/view/kortekurser/tilmeldinger-tpl.php', $data);
    }

    function getKursus()
    {
        return new VIH_Model_KortKursus((int)$this->context->name);
    }

}