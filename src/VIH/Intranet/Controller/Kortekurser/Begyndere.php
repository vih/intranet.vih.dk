<?php
class VIH_Intranet_Controller_Kortekurser_Begyndere extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name());
        if ($kursus->get('gruppe_id') != 1) {
            echo '';
            exit;
        }
        $begyndere = $kursus->getBegyndere();

        throw new k_http_Response(200, $begyndere);
    }
}