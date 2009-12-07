<?php
class VIH_Intranet_Controller_Fag_Gruppe_Show extends k_Controller
{
    public $map = array('edit' => 'VIH_Intranet_Controller_Fag_Gruppe_Edit',
                        'delete' => 'VIH_Intranet_Controller_Fag_Gruppe_Delete');

    function GET()
    {
        $faggruppe = new VIH_Model_Fag_Gruppe($this->name);

        $this->document->title = 'Faggruppe: ' . $faggruppe->get('navn');
        $this->document->options = array(
            $this->context->url('../') => 'Fagoversigt',
            $this->context->url() => 'Alle faggrupperne',
            $this->url('edit') => 'Ret'
        );
        return '<p>'.$faggruppe->get('beskrivelse').'</p>';
    }

    function forward($name)
    {
        if ($name == 'edit') {
            $next = new VIH_Intranet_Controller_Fag_Gruppe_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'delete') {
            $next = new VIH_Intranet_Controller_Fag_Gruppe_Delete($this, $name);
            return $next->handleRequest();
        }
    }
}
