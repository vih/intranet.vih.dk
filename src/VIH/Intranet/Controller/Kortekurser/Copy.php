<?php
class VIH_Intranet_Controller_Kortekurser_Copy extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name());
        $new_kursus = new VIH_Model_KortKursus();
        if ($id = $new_kursus->copy($kursus)) {
            throw new k_SeeOther($this->context->url('../' . $id));
        }

        throw new Exception('Could not copy course');
    }
}