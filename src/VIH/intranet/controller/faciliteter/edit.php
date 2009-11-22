<?php
class VIH_Intranet_Controller_Faciliteter_Edit extends k_Controller
{
    private $form;

    function getForm()
    {
        if ($this->form) return $this->form;
        
        $facilitet = new VIH_Model_Facilitet;

        $this->form = new HTML_QuickForm('fag', 'POST', $this->url('./'));

        $this->form->addElement('text', 'navn', 'Navn');
        $this->form->addElement('textarea', 'beskrivelse', 'Beskrivelse', array('cols' => 80, 'rows' => 20));
        $this->form->addElement('select', 'kategori_id', 'Kategori', $facilitet->kategori);
        $this->form->addElement('text', 'title', 'Titel');
        $this->form->addElement('text', 'identifier', 'Identifier');
        $this->form->addElement('textarea', 'description', 'Description');
        $this->form->addElement('textarea', 'keywords', 'Nøgleord');
        $this->form->addElement('checkbox', 'published', 'Udgivet');
        $this->form->addElement('submit', null, 'Gem');
        return $this->form;
    }

    function GET()
    {
        
        $this->document->title = 'Rediger Facilitet';
        
        if (!empty($this->context->name)) {
            $facilitet = new VIH_Model_Facilitet($this->context->name);

            $this->getForm()->setDefaults(array(
                                           'navn' => $facilitet->get('navn'),
                                           'kategori_id' => $facilitet->get('kategori_id'),
                                           'beskrivelse' => $facilitet->get('beskrivelse'),
                                           'title' => $facilitet->get('title'),
                                           'keywords' => $facilitet->get('keywords'),
                                           'description' => $facilitet->get('description'),
                                           'identifier' => $facilitet->get('identifier'),
                                           'published' => $facilitet->get('published')));
        } else {
            $facilitet = new VIH_Model_Facilitet;
        }

        return $this->getForm()->toHTML();
    }

    function POST()
    {
        if ($this->getForm()->validate()) {
            $facilitet = new VIH_Model_Facilitet($this->context->name);
            //$input = $this->form->exportValues();
            $input = $this->POST->getArrayCopy();

            if ($id = $facilitet->save($input)) {
                throw new k_http_Redirect($this->context->url());
            }
        }
    }
}
