<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Ministeriumliste extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name());
        $list = new VIH_List_Ministerium();
        $list->update($kursus, $kursus->getTilmeldinger());

        throw new k_http_Response(200, $list->fetch());

    }
}
