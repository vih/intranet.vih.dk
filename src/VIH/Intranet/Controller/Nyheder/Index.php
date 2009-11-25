<?php
class VIH_Intranet_Controller_Nyheder_Index extends k_Component
{
    public $map = array('create' => 'vih_intranet_controller_news_edit');
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }
    function renderHtml()
    {
        $this->document->setTitle('Nyheder');
        $this->document->options = array($this->url('create') => 'Opret');

        $data = array('nyheder' => VIH_News::getList('', 100));

        return $this->render('VIH/Intranet/view/nyheder/nyheder-tpl.php', $data);

    }

    function map($name)
    {
        if ($name == 'create') {
            return 'VIH_Intranet_Controller_Nyheder_Edit';
        }
        return 'VIH_Intranet_Controller_Nyheder_Show';
    }
}