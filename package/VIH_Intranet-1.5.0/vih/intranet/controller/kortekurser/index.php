<?php
class VIH_Intranet_Controller_KorteKurser_Index extends k_Controller
{
    private $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $form = new HTML_QuickForm('korte', 'GET', $this->url());
        $form->addElement('select', 'filter', 'Filter', array('alle'=>'alle','golf' => 'golf', 'old' => 'gamle'));
        $form->addElement('submit', 'submit', 'Afsted');
        return ($this->form = $form);
    }

    function getContent($kurser)
    {
        $this->document->title = 'Korte kurser';
        $this->document->options = array($this->url('create') => 'Opret');

        $data = array('caption' => 'Korte kurser',
                      'kurser' => $kurser);

        return $this->getForm()->toHTML() . $this->render('vih/intranet/view/kortekurser/kurser-tpl.php', $data);
    }

    function GET()
    {
        if ($this->getForm()->validate()) {
            if ($this->getForm()->exportValue('filter') == 'old') {
                $kurser = VIH_Model_KortKursus::getList('old');
            } elseif($this->getForm()->exportValue('filter') == 'golf') {
                $kurser = VIH_Model_KortKursus::getList('intranet', 'golf');
            } else {
                $kurser = VIH_Model_KortKursus::getList('intranet');
            }
            return $this->getContent($kurser);
        }

        $kurser = VIH_Model_KortKursus::getList('intranet');
        return $this->getContent($kurser);
    }

    function forward($name)
    {
        if ($name == 'tilmeldinger') {
            $next = new VIH_Intranet_Controller_KorteKurser_Tilmeldinger_Index($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'create') {
            $next = new VIH_Intranet_Controller_KorteKurser_Edit($this, $name);
            return $next->handleRequest();
        }

        $next = new VIH_Intranet_Controller_KorteKurser_Kursus($this, $name);
        return $next->handleRequest();
    }
}