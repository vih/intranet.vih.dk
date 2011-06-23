<?php
class VIH_Intranet_Controller_Kortekurser_Venteliste_Index extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        return 'VIH_Intranet_Controller_Kortekurser_Venteliste_Show';
    }

    function renderHtml()
    {
        $venteliste = new VIH_Model_Venteliste(1, $this->context->getCourse()->get('id'));
        $this->document->setTitle('Venteliste til ' . $venteliste->get('kursusnavn'));

        $data = array('venteliste' => $venteliste->getList());

        $tpl = $this->template->create('kortekurser/venteliste');
        return '<p>Listen er sorteret med de, der været længst på venteliste øverst</p>
        ' . $tpl->render($this, $data);
    }
}