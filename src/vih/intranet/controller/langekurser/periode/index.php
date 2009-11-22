<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_LangeKurser_Periode_Index extends k_Controller
{
    public $map = array('create' => 'VIH_Intranet_Controller_LangeKurser_Periode_Create');

    function GET()
    {
        $this->document->title = 'Perioder';
        $this->document->options = array(
            $this->url('create') => 'Opret',
            $this->url('../') => 'Tilbage til kursus'
        ); 

        $doctrine = $this->registry->get('doctrine');
        $periods = Doctrine::getTable('VIH_Model_Course_Period')->findByCourseId($this->getLangtKursusId());

        //$perioder = VIH_Model_LangtKursus_Periode::getFromKursusId($this->registry->get('database'), $this->getLangtKursusId());
        $data = array('perioder' => $periods);

        return $this->render('vih/intranet/view/langekurser/perioder-tpl.php', $data);

    }

    function getLangtKursusId()
    {
        return $this->context->name;
    }

    function getDatasource()
    {
        return $this->registry->get('table:langtkursus_periode');;
    }


    function forward($name)
    {
        if ($name == 'create') {
            $next = new VIH_Intranet_Controller_LangeKurser_Periode_Create($this, $name);
        } else {
            $next = new VIH_Intranet_Controller_LangeKurser_Periode_Show($this, $name);
        }
        return $next->handleRequest();
    }
}