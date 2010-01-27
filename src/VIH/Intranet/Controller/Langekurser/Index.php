<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Langekurser_Index extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $kurser = VIH_Model_LangtKursus::getList('intranet');

        $this->document->setTitle('Lange Kurser');
        $this->document->options = array($this->url('/fag') => 'Fag', $this->url('create') => 'Opret kursus');

        $data = array('caption' => 'Lange kurser',
                     'kurser' => $kurser);

        $tpl = $this->template->create('langekurser/kurser');
        return $tpl->render(dirname(__FILE__) . '/../../view/langekurser/kurser', $data);
    }

    function map($name)
    {
        if ($name == 'create') {
            return 'VIH_Intranet_Controller_Langekurser_Edit';
        } elseif ($name == 'tilmeldinger') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Index';
        } else {
            return 'VIH_Intranet_Controller_Langekurser_Show';
        }
    }
}