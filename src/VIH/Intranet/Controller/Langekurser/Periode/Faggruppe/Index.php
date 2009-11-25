<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Index extends k_Component
{
    public $map = array('create' => 'VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Create');

    function renderHtml()
    {
        $this->document->setTitle('Faggrupper på perioden ' . $this->context->getModel()->getName());

        $this->document->options = array(
            $this->url('create') => 'Opret faggruppe',
            $this->url('../') => 'Luk'
        );
        $doctrine = $this->registry->get('doctrine');
        $groups = Doctrine::getTable('VIH_Model_Course_SubjectGroup')->findByPeriodId($this->getPeriodId());

        $data = array('period' => $this->context->getModel(), 'faggrupper' => $groups);

        return $this->render('VIH/Intranet/view/langekurser/periode/faggrupper.tpl.php', $data);

    }

    function getPeriodId()
    {
        return $this->context->name();
    }

    function forward($name)
    {
        return 'VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Show'';
    }
}