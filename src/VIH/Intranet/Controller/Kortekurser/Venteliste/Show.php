<?php
class VIH_Intranet_Controller_Kortekurser_Venteliste_Show extends k_Component
{
    protected $form;

    function renderHtmlEdit()
    {
        $venteliste = $this->getVenteliste();

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
            if (!$this->getVenteliste()->save($this->body())) {
                throw new Exception('Kan ikke gemme');
            }
            return new k_SeeOther($this->context->url('../'));
        }
        return $this->render();
    }

    function renderHtmlDelete()
    {
        if (!$this->getVenteliste()->delete()) {
            throw new Exeption('Kunne ikke slette ventelisten');
        }
        return new k_SeeOther($this->url('../'));
    }

    function getVenteliste()
    {
        $kursus = $this->context->context->getCourse();
        $venteliste = new VIH_Model_Venteliste(1, $kursus->get('id'), $this->body('id'));
        return $venteliste;
    }

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
}