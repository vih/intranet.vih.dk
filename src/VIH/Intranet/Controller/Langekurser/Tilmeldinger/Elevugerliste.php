<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Elevugerliste extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name());

        $data = array('kursus' => $kursus, 'tilmeldinger' => $kursus->getTilmeldinger());
        return $this->render('VIH/Intranet/view/langekurser/elevuger.tpl.php', $data);
    }
}