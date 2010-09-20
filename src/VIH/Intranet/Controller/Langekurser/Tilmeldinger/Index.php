<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Index extends k_Component
{
    private $form;
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
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

    function renderHtml()
    {
        if ($this->query('search') AND $this->getForm()->validate()) {
            $tilmeldinger = VIH_Model_LangtKursus_Tilmelding::search($this->query('search'));
        } else {
            $tilmeldinger = VIH_Model_LangtKursus_Tilmelding::getList('nyeste', NULL, 5);
        }

        $data = array('caption' => '5 nyeste tilmeldinger',
                      'tilmeldinger' => $tilmeldinger);

        $this->document->setTitle('Lange Kurser');
        $this->document->addOption('Vis kurser', $this->url('../'));
        $this->document->addOption('Protokol', $this->url('../../protokol'));
        $this->document->addOption('Fag', $this->url('../../fag'));
        $this->document->addOption('Exporter adresseliste som CSV', $this->url('exportcsv'));
        $this->document->addOption('Restance', $this->url('restance'));
        $this->document->addOption('Excel', $this->url(null . '.xls'));

        $tpl = $this->template->create('langekurser/tilmeldinger');
        return $tpl->render($this, $data) . $this->getForm()->toHTML();

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
}
