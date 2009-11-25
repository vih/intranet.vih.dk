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
        $this->document->options = array(
            $this->url('create') => 'Opret',
            $this->url('../') => 'Tilbage til fag'
        );

        $data = array('faggrupper' => VIH_Model_Fag_Gruppe::getList());

        $tpl = $this->template->create('fag/faggrupper');
        $tpl->render($this, $data);
    }
}