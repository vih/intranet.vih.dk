<?php
class VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Index extends k_Controller
{
    private $form;

    function getContent($tilmeldinger)
    {
        $data = array('caption' => '5 nyeste tilmeldinger',
                      'tilmeldinger' => $tilmeldinger);

        $this->document->title = 'Lange Kurser';
        $this->document->options = array(
            $this->url('/langekurser') => 'Vis kurser',
            $this->url('/protokol') => 'Protokol',
            $this->url('/fag') => 'Fag',
            $this->url('exportcsv') => 'Exporter adresseliste som CSV',
            $this->url('restance') => 'Restance'
        
        );

        return $this->render('vih/intranet/view/langekurser/tilmeldinger-tpl.php', $data) . $this->getForm()->toHTML();
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

    function GET()
    {
        $tilmeldinger = VIH_Model_LangtKursus_Tilmelding::getList('nyeste', NULL, 5);
        return $this->getContent($tilmeldinger);
    }

    function POST()
    {
        if ($this->getForm()->validate()) {
            $tilmeldinger = VIH_Model_LangtKursus_Tilmelding::search($this->POST['search']);
            return $this->getContent($tilmeldinger);
        } else {
            return $this->GET();
        }

    }

    function forward($name)
    {
        if ($name == 'exportcsv') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_ExportCSV($this, $name);
        }  elseif ($name == 'restance') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Restance($this, $name);
        } else {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Show($this, $name);
        }
        return $next->handleRequest();
    }
}
