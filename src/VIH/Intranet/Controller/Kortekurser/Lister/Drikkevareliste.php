<?php
class VIH_Intranet_Controller_Kortekurser_Lister_Drikkevareliste extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $data = array('kursus' => $this->context->getCourse(), 'deltagere' => $this->context->getCourse()->getDeltagere());

        $tpl = $this->template->create('VIH/Intranet/view/kortekurser/lister/drikkevareliste');
        return $this->render($this, $data);
    }
}
