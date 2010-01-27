<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Index extends k_Component
{
    private $form;
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $form = new HTML_QuickForm('search', 'GET', $this->url());
        $form->addElement('text', 'search');
        $form->addElement('submit', null, 'Søg');
        return ($this->form = $form);
    }

    function renderHtml()
    {
        if ($this->query('search') AND $this->getForm()->validate()) {
            $tilmeldinger = VIH_Model_LangtKursus_Tilmelding::search($this->body('search'));
        } else {
            $tilmeldinger = VIH_Model_LangtKursus_Tilmelding::getList('nyeste', NULL, 5);
        }

        $data = array('caption' => '5 nyeste tilmeldinger',
                      'tilmeldinger' => $tilmeldinger);

        $this->document->setTitle('Lange Kurser');
        $this->document->options = array(
            $this->url('/langekurser') => 'Vis kurser',
            $this->url('/protokol') => 'Protokol',
            $this->url('/fag') => 'Fag',
            $this->url('exportcsv') => 'Exporter adresseliste som CSV',
            $this->url('restance') => 'Restance'

        );

        $tpl = $this->template->create('langekurser/tilmeldinger');
        return $tpl->render($this, $data) . $this->getForm()->toHTML();

    }

    function map($name)
    {
        if ($name == 'exportcsv') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_ExportCSV';
        }  elseif ($name == 'restance') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Restance';
        } else {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Show';
        }
    }
}
