<?php
class VIH_Intranet_Controller_KorteKurser_Tilmeldinger_Index extends k_Controller
{
    private $form;

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

    function getContent($tilmeldinger)
    {
        $this->document->title = 'Korte kurser';
        $this->document->options = array($this->url('/kortekurser/') => 'Se de korte kurser',
                                         $this->url('restance') => 'Se liste over folk i restance');

        $data = array('caption' => '5 nyeste tilmeldinger',
                      'tilmeldinger' => $tilmeldinger);

        return $this->render('vih/intranet/view/kortekurser/tilmeldinger-tpl.php', $data) . $this->getForm()->toHTML();
    }

    function GET()
    {
        $tilmeldinger = VIH_Model_KortKursus_Tilmelding::getList();
        return $this->getContent($tilmeldinger);
    }

    function POST()
    {
        if ($this->getForm()->validate()) {
            $tilmeldinger = VIH_Model_KortKursus_Tilmelding::search($this->POST['search']);
            return $this->getContent($tilmeldinger);
        } else {
            return $this->getForm()->toHTML();
        }
    }

    function forward($name)
    {
        if ($name == 'udsendte_pdf') {
            $next = new VIH_Intranet_Controller_KorteKurser_Tilmeldinger_Pdf($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'restance') {
            $next = new VIH_Intranet_Controller_KorteKurser_Tilmeldinger_Restance($this, $name);
            return $next->handleRequest();
        }
        $next = new VIH_Intranet_Controller_KorteKurser_Tilmeldinger_Show($this, $name);
        return $next->handleRequest();
    }

}