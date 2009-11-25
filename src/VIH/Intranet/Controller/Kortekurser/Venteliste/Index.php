<?php
class VIH_Intranet_Controller_Kortekurser_Venteliste_Index extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }
    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name());

        $venteliste = new VIH_Model_Venteliste(1, $kursus->get('id'));
        if(intval($venteliste->get('kursus_id')) == 0) {
            trigger_error("Ugyldigt kursus", E_USER_ERROR);
        }
        $liste = $venteliste->getList();

        $this->document->setTitle('Venteliste til ' . $venteliste->get('kursusnavn'));

        $data = array('venteliste' => $liste);

        return '<p>Listen er sorteret med de, der været længst på venteliste øverst</p>
        ' . $this->render('VIH/Intranet/view/kortekurser/venteliste-tpl.php', $data);
    }

    function getKursusId()
    {
        return $this->context->name();
    }

    function map($name)
    {
        return 'VIH_Intranet_Controller_Kortekurser_Venteliste_Show';
    }

}

