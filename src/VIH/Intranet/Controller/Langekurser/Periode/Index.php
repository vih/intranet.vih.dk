<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Langekurser_Periode_Index extends k_Component
{
    function renderHtml()
    {
        $this->document->setTitle('Perioder');
        $this->document->options = array(
            $this->url('create') => 'Opret',
            $this->url('../') => 'Tilbage til kursus'
        );

        $doctrine = $this->registry->get('doctrine');
        $periods = Doctrine::getTable('VIH_Model_Course_Period')->findByCourseId($this->getLangtKursusId());

        //$perioder = VIH_Model_LangtKursus_Periode::getFromKursusId($this->registry->get('database'), $this->getLangtKursusId());
        $data = array('perioder' => $periods);

        return $this->render('VIH/Intranet/view/langekurser/perioder-tpl.php', $data);

    }

    function getLangtKursusId()
    {
        return $this->context->name();
    }

    function getDatasource()
    {
        return $this->registry->get('table:langtkursus_periode');;
    }

    function map($name)
    {
        return 'VIH_Intranet_Controller_Langekurser_Periode_Show'';
    }
}