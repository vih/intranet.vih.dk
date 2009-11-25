<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Faciliteter_Index extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        if (is_numeric($name)) {
            return 'VIH_Intranet_Controller_Faciliteter_Show';
        } elseif ($name == 'edit') {
            return 'VIH_Intranet_Controller_Faciliteter_Edit';
        }

    }

    function renderHtml()
    {
        $this->document->setTitle('Faciliteter');
        $this->document->options = array($this->url('edit') => 'Opret');

        $data = array('faciliteter' => VIH_Model_Facilitet::getList('all'));

        $tpl = $this->template->create('faciliteter/faciliteter');
        return $tpl->render($this, $data);
    }
}
