<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Index extends k_Component
{
    protected $doctrine;
    protected $template;

    function map($name)
    {
        if ($name == 'create') {
            return 'VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Create';
        }
        return 'VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Show';
    }

    function __construct(Doctrine_Connection_Common $doctrine, k_TemplateFactory $template)
    {
        $this->doctrine = $doctrine;
        $this->template = $template;
    }

    function renderHtml()
    {
        $this->document->setTitle('Faggrupper på perioden ' . $this->context->getModel()->getName());

        $this->document->addOption('Luk', $this->url('../'));
        $this->document->addOption('Opret faggruppe', $this->url('create'));
;
        $groups = Doctrine::getTable('VIH_Model_Course_SubjectGroup')->findByPeriodId($this->getPeriodId());

        $data = array('period' => $this->context->getModel(), 'faggrupper' => $groups);

        $tpl = $this->template->create('VIH/Intranet/view/langekurser/periode/faggrupper');
        return $tpl->render($this, $data);
    }

    function getPeriodId()
    {
        return $this->context->name();
    }
}