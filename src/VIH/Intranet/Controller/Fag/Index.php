<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Fag_Index extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        if ($name == 'faggrupper') {
            return 'VIH_Intranet_Controller_Fag_Gruppe_Index';
        } else {
            return 'VIH_Intranet_Controller_Fag_Show';
        }
    }

    function renderHtml()
    {
        $this->document->setTitle('Fag');
        $this->document->options = array($this->url('create') => 'Opret',
                                         $this->url('faggrupper') => 'Faggrupper');

        $data = array('list' => VIH_Model_Fag::getList());

        $tpl = $this->template->create('fag/liste');
        return $tpl->render($this, $data);
    }

}