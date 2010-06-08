<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Restance extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $this->document->setTitle('Tilmeldinger i restance');
        $data = array('tilmeldinger' => VIH_Model_LangtKursus_Tilmelding::getList('forfaldne'));
        $tpl = $this->template->create('VIH/Intranet/view/langekurser/tilmeldinger');
        return $this->render($this, $data);
    }
}