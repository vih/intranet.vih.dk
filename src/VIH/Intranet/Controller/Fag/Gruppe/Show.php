<?php
class VIH_Intranet_Controller_Fag_Gruppe_Show extends k_Component
{
    public $map = array('edit' => 'VIH_Intranet_Controller_Fag_Gruppe_Edit',
                        'delete' => 'VIH_Intranet_Controller_Fag_Gruppe_Delete');

    function map($name)
    {
        return $this->map[$name];
    }

    function renderHtml()
    {
        $faggruppe = new VIH_Model_Fag_Gruppe($this->name());

        $this->document->setTitle('Faggruppe: ' . $faggruppe->get('navn'));
        $this->document->options = array(
            $this->context->url('../') => 'Fagoversigt',
            $this->context->url() => 'Alle faggrupperne',
            $this->url('edit') => 'Ret'
        );
        return '<p>'.$faggruppe->get('beskrivelse').'</p>';
    }
}
