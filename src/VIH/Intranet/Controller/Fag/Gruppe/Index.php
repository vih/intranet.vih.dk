<?php
/**
 * Controller for the intranet
 */

class VIH_Intranet_Controller_Fag_Gruppe_Index extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        return 'VIH_Intranet_Controller_Fag_Gruppe_Show';
    }

    function renderHtml()
    {
        $this->document->setTitle('Faggrupper');
        $this->document->addOption('Opret', $this->url('create'));
        $this->document->addOption('Tilbage til fag', $this->url('../'));

        $data = array('faggrupper' => VIH_Model_Fag_Gruppe::getList());

        $tpl = $this->template->create('fag/faggrupper');
        $tpl->render($this, $data);
    }
}