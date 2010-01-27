<?php
class VIH_Intranet_Controller_Fag_Gruppe_Edit extends k_Component
{
    private $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm('gruppe', 'POST', $this->url());
        $form->addElement('hidden', 'id');
        $form->addElement('text', 'navn', 'Navn');
        $form->addElement('textarea', 'beskrivelse', 'Beskrivelse', array('cols'=>80, 'rows' => 20));
        $form->addElement('checkbox', 'valgfag', 'Valgfag');
        $form->addElement('checkbox', 'vis_diplom', 'Vis på diplomet');
        $form->addElement('checkbox', 'show_description', 'Vis beskrivelse');
        $form->addElement('checkbox', 'published', 'Vis');
        $form->addElement('submit', null, 'Gem');

        return ($this->form = $form);
    }

    function renderHtml()
    {
        $faggruppe = new VIH_Model_Fag_Gruppe($this->context->name());

        if ($faggruppe->get('id') > 0) {
            $defaults = array('id' => $faggruppe->get('id'),
                              'navn' => $faggruppe->get('navn'),
                              'beskrivelse' => $faggruppe->get('beskrivelse'),
                              'valgfag' => $faggruppe->get('valgfag'),
                              'vis_diplom' => $faggruppe->get('vis_diplom'),
                              'show_description' => $faggruppe->get('show_description'),
                              'published' => $faggruppe->get('published'));
            $this->getForm()->setDefaults($defaults);
        }

        $this->document->setTitle('Rediger faggrupper');
        $this->options = array(
            $this->context->url() => 'Tilbage'
        );
        return $this->getForm()->toHTML();
    }

    function postForm()
    {
        $faggruppe = new VIH_Model_Fag_Gruppe($this->context->name());
        if ($this->getForm()->validate()) {
            if ($this->body('show_description')) {
                $this->body('show_description') = 0;
            }
            if ($this->body('published')) {
                $this->body('published') = 0;
            }
            if ($id = $faggruppe->save($this->body())) {
                return new k_SeeOther($this->context->url());
            }
        }

        return $this->render();
    }
}
