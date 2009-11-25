<?php
class VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Index extends k_Component
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
        $form = new HTML_QuickForm('search', 'POST', $this->url());
        $form->addElement('text', 'search');
        $form->addElement('submit', null, 'Søg');
        return ($this->form = $form);
    }

    function getContent($tilmeldinger)
    {
        $this->document->setTitle('Korte kurser');
        $this->document->options = array($this->url('/kortekurser/') => 'Se de korte kurser',
                                         $this->url('restance') => 'Se liste over folk i restance');

        $data = array('caption' => '5 nyeste tilmeldinger',
                      'tilmeldinger' => $tilmeldinger);

        $tpl = $this->template->create('kortekurser/tilmeldinger');
        return $tpl->render($this, $data);
    }

    function renderHtml()
    {
        $tilmeldinger = VIH_Model_KortKursus_Tilmelding::getList();
        return $this->getContent($tilmeldinger);
    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $tilmeldinger = VIH_Model_KortKursus_Tilmelding::search($this->POST['search']);
            return $this->getContent($tilmeldinger);
        } else {
            return $this->getForm()->toHTML();
        }
    }

    function map($name)
    {
        if ($name == 'udsendte_pdf') {
            return 'VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Pdf';
        } elseif ($name == 'restance') {
            return 'VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Restance';
        }
        return 'VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Show';
    }

}