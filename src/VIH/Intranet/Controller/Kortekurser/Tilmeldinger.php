<?php
class VIH_Intranet_Controller_Kortekurser_Tilmeldinger extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        return 'VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Show';
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus((int)$this->context->name());
        $tilmeldinger = $kursus->getTilmeldinger();

        $this->document->setTitle('Tilmeldinger til ' . $kursus->getKursusNavn());
        $this->document->addOption('Kurser', $this->url('../../'));
        $this->document->addOption('Deltagere', $this->context->url('deltagere'));

        $data = array('tilmeldinger' => $tilmeldinger,
                      'vis_besked' => 'ja',
                      'caption' => 'Tilmeldinger');

        $tpl = $this->template->create('kortekurser/tilmeldinger');
        return $tpl->render($this, $data);
    }

    function getKursus()
    {
        return new VIH_Model_KortKursus((int)$this->context->name());
    }

}