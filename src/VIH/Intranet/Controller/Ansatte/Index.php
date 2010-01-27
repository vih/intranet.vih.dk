<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Ansatte_Index extends k_Component
{
    protected $template;
    protected $form;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $ansat = new VIH_Model_Ansat;
        $ansatte = $ansat->getList();

        $this->document->setTitle('Ansatte');
        //$this->document->options = array($this->url(null, array('create')) => 'Opret');

        $data = array('list' => $ansatte);

        $template = $this->template->create('ansatte/list');
        return $template->render($this, $data);
    }

    function getAnsat()
    {
        return new VIH_Model_Ansat();
    }

    function renderHtmlCreate()
    {
        return $this->getForm()->toHtml();
    }

    function map($name)
    {
        return 'VIH_Intranet_Controller_Ansatte_Show';
    }

    public function getForm()
    {
        if (is_object($this->form)) {
            return $this->form;
        }

        $date_options = array('minYear' => date('Y') - 70,
                              'maxYear' => date('Y'),
                              'addEmptyOption' => 'true',
                              'emptyOptionValue' => '',
                              'emptyOptionText' => 'Vælg');

        $this->form = new HTML_QuickForm('fag', 'POST', $this->url(null, array($this->subview())));
        $this->form->addElement('text', 'navn', 'Navn');
        $this->form->addElement('text', 'adresse', 'Adresse');
        $this->form->addElement('text', 'postnr', 'Postnr');
        $this->form->addElement('text', 'postby', 'Postby');
        $this->form->addElement('date', 'date_birthday', 'Fødselsdag', $date_options);
        $this->form->addElement('date', 'date_ansat', 'Ansat', $date_options);
        $this->form->addElement('date', 'date_stoppet', 'Stoppet', $date_options);
        $this->form->addElement('text', 'titel', 'Titel');
        $this->form->addElement('select', 'funktion_id', 'Funktion', $this->getAnsat()->funktion);
        $this->form->addElement('text', 'email', 'E-mail');
        $this->form->addElement('text', 'website', 'Hjemmeside');
        $this->form->addElement('text', 'telefon', 'Telefon');
        $this->form->addElement('text', 'mobil', 'Mobil');
        $this->form->addElement('textarea', 'beskrivelse', 'Beskrivelse', array('cols' => 80, 'rows' => 20));
        $this->form->addElement('textarea', 'extra_info', 'Ekstra oplysninger', array('cols' => 80, 'rows' => 20));
        $this->form->addElement('checkbox', 'published', 'Udgivet');
        $this->form->addElement('submit', null, 'Gem');

        return ($this->form);
    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $ansat = new VIH_Model_Ansat($this->name());
            $input = $this->body();
            $input['date_stoppet'] = $this->body('date_stoppet');
            $input['date_stoppet'] = $input['date_stoppet']['Y'] . '-' . $input['date_stoppet']['M'] . '-' . $input['date_stoppet']['d'];

            $input['date_ansat'] = $this->body('date_ansat');
            $input['date_ansat'] = $input['date_ansat']['Y'] . '-' . $input['date_ansat']['M'] . '-' . $input['date_ansat']['d'];

            $input['date_birthday'] = $this->body('date_birthday');
            $input['date_birthday'] = $input['date_birthday']['Y'] . '-' . $input['date_birthday']['M'] . '-' . $input['date_birthday']['d'];
            if ($id = $ansat->save($input)) {
                return new k_SeeOther($this->url($id));
            }

        }

        return $this->render();
    }
}
