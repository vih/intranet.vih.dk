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
        $kursus = new VIH_Model_KortKursus($this->context->name());

        $venteliste = new VIH_Model_Venteliste(1, $this->context->getCourse()->get('id'));

        $liste = $venteliste->getList();

        $this->document->setTitle('Venteliste til ' . $venteliste->get('kursusnavn'));

        $data = array('venteliste' => $liste);

        $tpl = $this->template->create('kortekurser/venteliste');
        return '<p>Listen er sorteret med de, der været længst på venteliste øverst</p>
        ' . $tpl->render($this, $data);
    }
}