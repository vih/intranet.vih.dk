<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_LangeKurser_Index extends k_Controller
{
    public $map = array('periode' => 'VIH_Intranet_Controller_LangeKurser_Periode_Index',
                        'tilmeldinger' => 'VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Index',);

    function GET()
    {
        $kurser = VIH_Model_LangtKursus::getList('intranet');

        $this->document->title = 'Lange Kurser';
        $this->document->options = array($this->url('/fag') => 'Fag', $this->url('create') => 'Opret kursus');

        $data = array('caption' => 'Lange kurser',
                     'kurser' => $kurser);

        return $this->render(dirname(__FILE__) . '/../../view/langekurser/kurser-tpl.php', $data);
    }

    function forward($name)
    {
        if ($name == 'create') {
            $next = new VIH_Intranet_Controller_Langekurser_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'tilmeldinger') {
            $next = new VIH_Intranet_Controller_Langekurser_Tilmeldinger_Index($this, $name);
            return $next->handleRequest();
        } else {
            $next = new VIH_Intranet_Controller_Langekurser_Show($this, $name);
            return $next->handleRequest();
        }
    }
}