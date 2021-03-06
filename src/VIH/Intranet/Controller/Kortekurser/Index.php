<?php
class VIH_Intranet_Controller_Kortekurser_Index extends k_Component
{
    private $form;
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        if ($name == 'tilmeldinger') {
            return 'VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Index';
        } elseif ($name == 'create') {
            return 'VIH_Intranet_Controller_Kortekurser_Edit';
        }

        return 'VIH_Intranet_Controller_Kortekurser_Kursus';
    }

    function renderHtml()
    {
        if ($this->getForm()->validate()) {
            if ($this->query('filter') == 'old') {
                $kurser = VIH_Model_KortKursus::getList('old');
            } elseif($this->query('filter') == 'golf') {
                $kurser = VIH_Model_KortKursus::getList('intranet', 'golf');
            } else {
                $kurser = VIH_Model_KortKursus::getList('intranet');
            }
        } else {
            $kurser = VIH_Model_KortKursus::getList('intranet');
        }

        $this->document->setTitle('Korte kurser');
        $this->document->addOption('Opret', $this->url('create'));

        $data = array('caption' => 'Korte kurser',
                      'kurser' => $kurser);

        $tpl = $this->template->create('kortekurser/kurser');
        return $this->getForm()->toHTML() . $tpl->render($this, $data);
    }

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
}