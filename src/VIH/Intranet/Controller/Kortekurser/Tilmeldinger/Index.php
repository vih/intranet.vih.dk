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
        $form = new HTML_QuickForm('search', 'get', $this->url());
        $form->addElement('text', 'search');
        $form->addElement('submit', null, 'Søg');
        return ($this->form = $form);
    }

    function renderHtml()
    {

        $this->document->setTitle('Korte kurser');
        $this->document->addOption('Se de korte kurser', $this->url('../'));
        $this->document->addOption('Se liste over folk i restance', $this->url('restance'));


        if ($this->getForm()->validate()) {
            $tilmeldinger = VIH_Model_KortKursus_Tilmelding::search($this->query('search'));
            $data = array('caption' => 'Søgning',
                      'tilmeldinger' => $tilmeldinger);

        } else {
            $tilmeldinger = VIH_Model_KortKursus_Tilmelding::getList();
            $data = array('caption' => '5 nyeste tilmeldinger',
                      'tilmeldinger' => $tilmeldinger);
        }


        $tpl = $this->template->create('kortekurser/tilmeldinger');
        return $tpl->render($this, $data) . $this->getForm()->toHtml();
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