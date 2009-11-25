<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Index extends k_Component
{
    private $form;
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function getContent($tilmeldinger)
    {
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

        return $this->render('VIH/Intranet/view/langekurser/tilmeldinger-tpl.php', $data) . $this->getForm()->toHTML();
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $form = new HTML_QuickForm('search', 'POST', $this->url());
        $form->addElement('text', 'search');
        $form->addElement('submit', null, 'Søg');
        return ($this->form = $form);
    }

    function renderHtml()
    {
        $tilmeldinger = VIH_Model_LangtKursus_Tilmelding::getList('nyeste', NULL, 5);
        return $this->getContent($tilmeldinger);
    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $tilmeldinger = VIH_Model_LangtKursus_Tilmelding::search($this->POST['search']);
            return $this->getContent($tilmeldinger);
        } else {
            return $this->GET();
        }

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
