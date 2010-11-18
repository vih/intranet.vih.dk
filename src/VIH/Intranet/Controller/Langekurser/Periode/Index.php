<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Langekurser_Periode_Index extends k_Component
{
    protected $template;
    protected $doctrine;

    function __construct(k_TemplateFactory $template, Doctrine_Connection_Common $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->template = $template;
    }

    function map($name)
    {
        if ($name == 'create') {
            return 'VIH_Intranet_Controller_Langekurser_Periode_Create';
        }
        return 'VIH_Intranet_Controller_Langekurser_Periode_Show';
    }

    function renderHtml()
    {
        $this->document->setTitle('Perioder');
        $this->document->addOption('Opret', $this->url('create'));
        $this->document->addOption('Tilbage til kursus', $this->url('../'));

        $periods = Doctrine::getTable('VIH_Model_Course_Period')->findByCourseId($this->getLangtKursusId());

        //$perioder = VIH_Model_LangtKursus_Periode::getFromKursusId($this->registry->get('database'), $this->getLangtKursusId());
        $data = array('perioder' => $periods);

        $tpl = $this->template->create('langekurser/perioder');
        return $tpl->render($this, $data);
    }

    function getLangtKursusId()
    {
        return $this->context->name();
    }
}