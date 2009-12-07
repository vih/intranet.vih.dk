<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Langekurser_Index extends k_Component
{
    public $map = array('periode' => 'VIH_Intranet_Controller_Langekurser_Periode_Index',
                        'tilmeldinger' => 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Index');

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
        return $this->render(dirname(__FILE__) . '/../../view/langekurser/kurser-tpl.php', $data);
    }

    function forward($name)
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