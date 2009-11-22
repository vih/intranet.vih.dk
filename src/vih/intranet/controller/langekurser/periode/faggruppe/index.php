<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_LangeKurser_Periode_Faggruppe_Index extends k_Controller
{
    public $map = array('create' => 'VIH_Intranet_Controller_LangeKurser_Periode_Faggruppe_Create');

    function GET()
    {
        $this->document->title = 'Faggrupper på perioden ' . $this->context->getModel()->getName();

        $this->document->options = array(
            $this->url('create') => 'Opret faggruppe',
            $this->url('../') => 'Luk'
        );
        $doctrine = $this->registry->get('doctrine');
        $groups = Doctrine::getTable('VIH_Model_Course_SubjectGroup')->findByPeriodId($this->getPeriodId());

        $data = array('period' => $this->context->getModel(), 'faggrupper' => $groups);

        return $this->render('vih/intranet/view/langekurser/periode/faggrupper.tpl.php', $data);

    }

    function getPeriodId()
    {
        return $this->context->name;
    }

    function forward($name)
    {
        if ($name == 'create') {
            $next = new VIH_Intranet_Controller_LangeKurser_Periode_Faggruppe_Create($this, $name);
        } else {
            $next = new VIH_Intranet_Controller_LangeKurser_Periode_Faggruppe_Show($this, $name);
        }
        return $next->handleRequest();
    }
}