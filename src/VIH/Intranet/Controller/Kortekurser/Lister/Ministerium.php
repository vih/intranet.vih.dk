<?php
class VIH_Intranet_Controller_Kortekurser_Lister_Ministerium extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name());

        $data = array(
            'kursus' => $kursus,
            'deltagere' => $kursus->getDeltagere());

        $tpl = $this->template->create('list/ministerium');
        return new k_HttpResponsen(200, $tpl->render($this, $data));
    }
}
