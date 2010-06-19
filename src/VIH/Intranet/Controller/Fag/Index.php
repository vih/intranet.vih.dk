<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Fag_Index extends k_Component
{
    protected $template;
    private $form;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        if ($name == 'faggrupper') {
            return 'VIH_Intranet_Controller_Fag_Gruppe_Index';
        } else {
            return 'VIH_Intranet_Controller_Fag_Show';
        }
    }

    function renderHtml()
    {
        $this->document->setTitle('Fag');
        $this->document->addOption('Opret', $this->url('create'));
        $this->document->addOption('Faggrupper', $this->url('faggrupper'));

        $data = array('list' => VIH_Model_Fag::getList());

        $tpl = $this->template->create('fag/liste');
        return $tpl->render($this, $data);
    }


    function renderHtmlCreate()
    {
        $this->document->setTitle('Opret fag');

        return $this->getForm()->toHTML();
    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $fag = new VIH_Model_Fag();
            $input = $this->body();
            $input['navn'] = vih_handle_microsoft($input['navn']);
            $input['beskrivelse'] = vih_handle_microsoft($input['beskrivelse']);
            $input['kort_beskrivelse'] = vih_handle_microsoft($input['kort_beskrivelse']);
            $input['udvidet_beskrivelse'] = vih_handle_microsoft($input['udvidet_beskrivelse']);
            if (!isset($input['published'])) {
                $input['published'] = 0;
            }

            if ($id = $fag->save($input)) {
                if ($this->body('underviser')) {
                    $fag->addUnderviser($this->body('underviser'));
                }
                return new k_SeeOther($this->url($fag->get('id')));
            }
        }
        return $this->render();
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $faggruppe = VIH_Model_Fag_Gruppe::getList();

        foreach($faggruppe AS $grp) {
            $faggruppelist[$grp->get('id')] = $grp->get('navn');
        }

        $undervisere = VIH_Model_Ansat::getList('lærere');

        $form = new HTML_QuickForm('fag', 'POST', $this->url());
        $form->addElement('hidden', 'id');
        $form->addElement('text', 'navn', 'Navn');
        $form->addElement('select', 'faggruppe_id', 'Faggruppe', $faggruppelist);
        $form->addElement('text', 'identifier', 'Identifier');
        $form->addElement('textarea', 'kort_beskrivelse', 'Kort beskrivelse', array('cols' => 80, 'rows' => 5));
        $form->addElement('textarea', 'beskrivelse', 'Beskrivelse', array('cols' => 80, 'rows' => 20));
        $form->addElement('textarea', 'udvidet_beskrivelse', 'Udvidet beskrivelse', array('cols' => 80, 'rows' => 20));
        $form->addElement('header', null, 'Til søgemaskinerne');
        $form->addElement('text', 'title', 'Titel');
        $form->addElement('textarea', 'description', 'Beskrivelse');
        $form->addElement('textarea', 'keywords', 'Nøgleord');
        foreach ($undervisere AS $underviser) {
            $underviserlist[] = HTML_QuickForm::createElement('checkbox', $underviser->get('id'), null, $underviser->get('navn'));
        }
        $form->addGroup($underviserlist, 'underviser', 'Underviser', '<br />');
        $form->addElement('checkbox', 'published', 'Udgivet');
        $form->addElement('submit', null, 'Gem');
        return ($this->form = $form);
    }

}