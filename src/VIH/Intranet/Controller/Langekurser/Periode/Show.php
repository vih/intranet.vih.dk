<?php
class VIH_Intranet_Controller_Langekurser_Periode_Show extends k_Component
{
    public $map = array('edit'   => 'VIH_Intranet_Controller_Langekurser_Periode_Edit',
                        'delete' => 'VIH_Intranet_Controller_Langekurser_Periode_Delete',
                        'faggruppe' => 'VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Index');

    protected $doctrine;
    protected $template;

    function __construct(Doctrine_Connection_Common $doctrine, k_TemplateFactory $template)
    {
        $this->doctrine = $doctrine;
        $this->template = $template;
    }

    function getLangtKursusId()
    {
        return $this->context->name();
    }

    protected function map($name)
    {
        return $this->map[$name];
    }

    function getModel()
    {
        return Doctrine::getTable('VIH_Model_Course_Period')->findOneById($this->name());
    }

    function getSubjectGroup()
    {
        return Doctrine::getTable('VIH_Model_Course_SubjectGroup')->findByPeriodId($this->name());
    }

    function renderHtml()
    {
        $periode = $this->getModel();
        $this->document->setTitle($this->getModel()->getName() . $this->getModel()->getDateStart()->format('%d-%m-%Y') . ' til ' . $this->getModel()->getDateEnd()->format('%d-%m-%Y'));
        $this->document->addOption('Opret faggruppe', $this->url('faggruppe/create'));
        $this->document->addOption('Luk', $this->url('../'));

        $tpl = $this->template->create('langekurser/periode/show');
        return $tpl->render($this, array('periode' => $periode, 'faggrupper' => $this->getSubjectGroup()));
    }

}
