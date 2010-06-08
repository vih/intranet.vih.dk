<?php
class VIH_Intranet_Controller_Kortekurser_Lister_Deltagerliste extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus((int)$this->context->name());
        $deltagere = $kursus->getDeltagere();

        $data = array('kursus' => $kursus, 'deltagere' => $deltagere);

        $tpl = $this->template->create('VIH/Intranet/view/kortekurser/lister/deltagerliste');
        return $tpl->render($this, $data);
    }
}