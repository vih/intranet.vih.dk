<?php
class VIH_Intranet_Controller_KorteKurser_Venteliste_Edit extends k_Controller
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

    function GET()
    {
        $kursus = new VIH_Model_KortKursus($this->context->getKursusId());
        $venteliste = new VIH_Model_Venteliste(1, $kursus->get('id'), $this->context->name);

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

        $this->document->title = 'Rediger';
        return $this->getForm()->toHTML();

    }

    function POST()
    {
        if ($this->getForm()->validate()) {
            $kursus = new VIH_Model_KortKursus($this->POST['kursus_id']);
            $venteliste = new VIH_Model_Venteliste(1, $kursus->get('id'), $this->POST['id']);
            if (!$venteliste->save($this->POST->getArrayCopy())) {
                throw new Excpetion('Kan ikke gemme');
            }
            throw new k_http_Redirect($this->context->url('../'));
        }
    }
}

?>