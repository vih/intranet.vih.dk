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
        $this->document->setTitle($this->context->getCourse()->get('navn'));

        $data = array('kursus' => $this->context->getCourse(), 'deltagere' => $this->context->getCourse()->getDeltagere());

        $tpl = $this->template->create('VIH/Intranet/view/kortekurser/lister/deltagerliste');
        return $tpl->render($this, $data);
    }
}
