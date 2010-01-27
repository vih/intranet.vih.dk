<?php
class VIH_Intranet_Controller_Kortekurser_Venteliste_Edit extends k_Component
{
    private $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm('venteliste', 'POST', $this->url());
        $form->addElement('hidden', 'id');
        $form->addElement('hidden', 'kursus_id');
        $form->addElement('text', 'navn', 'Navn');
        $form->addElement('text', 'antal', 'Antal');
        $form->addElement('text', 'telefonnummer', 'Telefon');
        $form->addElement('text', 'arbejdstelefon', 'Arbejdstelefon');
        $form->addElement('text', 'email', 'E-mail');
        $form->addElement('textarea', 'besked', 'Besked');
        $form->addElement('submit', NULL, 'Gem');

        return ($this->form = $form);
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus($this->context->getKursusId());
        $venteliste = new VIH_Model_Venteliste(1, $kursus->get('id'), $this->context->name());

        $this->getForm()->setDefaults(array(
            'id' => $venteliste->get('id'),
            'kursus_id' => $venteliste->get('kursus_id'),
            'navn' => $venteliste->adresse->get('navn'),
            'antal' => $venteliste->get('antal'),
            'telefonnummer' => $venteliste->adresse->get('telefon'),
            'arbejdstelefon' => $venteliste->adresse->get('arbejdstelefon'),
            'email' => $venteliste->adresse->get('email'),
            'besked' => $venteliste->get('besked')
        ));

        $this->document->setTitle('Rediger');
        return $this->getForm()->toHTML();

    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $kursus = new VIH_Model_KortKursus($this->body('kursus_id'));
            $venteliste = new VIH_Model_Venteliste(1, $kursus->get('id'), $this->body('id'));
            if (!$venteliste->save($this->body())) {
                throw new Excpetion('Kan ikke gemme');
            }
            return new k_SeeOther($this->context->url('../'));
        }
    }
}

?>