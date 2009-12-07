<?php
class VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Restance extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $this->document->setTitle('Tilmeldinger i restance');

        $data = array('tilmeldinger' => VIH_Model_KortKursus_Tilmelding::getList('restance', 400));

        $tpl = $this->template->create('kortekurser/tilmeldinger');
        return $this->render($this, $data);
    }
}