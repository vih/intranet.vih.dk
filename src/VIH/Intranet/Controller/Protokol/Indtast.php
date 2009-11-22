<?php
class VIH_Intranet_Controller_Protokol_Indtast extends k_Controller
{
    private $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $options = array('format' => 'd m Y H i',
                         'optionIncrement' => array('i' => 15));

        $form = new HTML_QuickForm('protokol', 'POST', $this->url());
        $form->addElement('hidden', 'elev_id');
        $form->addElement('hidden', 'id');
        $form->addElement('date', 'date_start', 'Startdato:', $options);
        $form->addElement('date', 'date_end', 'Slutdato:', $options);

        $radio[0] =& HTML_QuickForm::createElement('radio', null, null, 'fri', '1');
        $radio[1] =& HTML_QuickForm::createElement('radio', null, null, 'syg', '2');
        $radio[2] =& HTML_QuickForm::createElement('radio', null, null, 'fraværende', '3');
        $radio[5] =& HTML_QuickForm::createElement('radio', null, null, 'henstilling', '6');
        $radio[3] =& HTML_QuickForm::createElement('radio', null, null, 'mundtlig advarsel', '4');
        $radio[4] =& HTML_QuickForm::createElement('radio', null, null, 'skriftlig advarsel', '5');
        $radio[6] =& HTML_QuickForm::createElement('radio', null, null, 'hjemsendt', '7');
        $radio[7] =& HTML_QuickForm::createElement('radio', null, null, 'andet', '8');
        $form->addGroup($radio, 'type', 'Type:', ' ');
        $form->addElement('textarea', 'text', '');
        $form->addElement('submit', null, 'Send');

        $form->addRule('date_start', 'Husk dato', 'required', null, 'client');
        $form->addRule('date_end', 'Husk dato', 'required', null, 'client');
        $form->addRule('type', 'Husk type', 'required', null, 'client');
        $form->addRule('text', 'Tekst', 'required', null, 'client');

        return ($this->form = $form);

    }

    function GET()
    {
        $db = $this->registry->get('database:pear');
        $row = array();

        if (!empty($this->GET['id'])) {
            // hent selve rækken og sæt defaults
            $res = $db->query('SELECT * FROM langtkursus_tilmelding_protokol_item WHERE id = ' . (int)$_GET['id']);

            if (PEAR::isError($res)) {
                die($res->getMessage());
            }

            $res->fetchInto($row, DB_FETCHMODE_ASSOC);

            $this->getForm()->setDefaults(array('text' => $row['text'],
                                     'date_start' => $row['date_start'],
                                     'date_end' => $row['date_end'],
                                     'elev_id' => $row['tilmelding_id'],
                                     'type' => $row['type_key'],
                                     'id' => $this->GET['id']));

            $elev_id = $this->context->name;
        } else {
            $this->getForm()->setDefaults(array('elev_id' => (int)$this->context->name,
                                     'date_start' => array('Y' => date('Y'),
                                                           'm' => date('m'),
                                                           'd' => date('d'),
                                                           'H' => 8),
                                     'date_end' => array('Y' => date('Y'),
                                                         'm' => date('m'),
                                                         'd' => date('d'),
                                                         'H' => 8,
                                                         'i' => 30)));
        }

        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name);


        $this->document->title = 'Indtast ' . $tilmelding->get('navn');
        return $this->getForm()->toHTML();

    }

    function POST()
    {
        $db = $this->registry->get('database:pear');

        if ($this->getForm()->validate()) {
            $date_start = $this->getForm()->exportValue('date_start');
            $date_end = $this->getForm()->exportValue('date_end');


            $fields = array('date_created', 'date_updated', 'date_start', 'date_end', 'tilmelding_id', 'text', 'type_key');
            $values = array('NOW()',
                            'NOW()',
                            $date_start['Y'] . '-' . $date_start['m'] . '-' . $date_start['d'] . ' ' . $date_start['H'] . ':' . $date_start['i'],
                            $date_end['Y'] . '-' . $date_end['m'] . '-' . $date_end['d'] . ' ' . $date_end['H'] . ':' . $date_end['i'],
                            $this->getForm()->exportValue('elev_id'),
                            $this->POST['text'],
                            $this->getform()->exportValue('type'));

            if (!empty($_POST['id'])) {
                $sth = $db->autoPrepare('langtkursus_tilmelding_protokol_item', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $_POST['id']);
            } else {
                $sth = $db->autoPrepare('langtkursus_tilmelding_protokol_item', $fields, DB_AUTOQUERY_INSERT);
            }

            $res = $db->execute($sth, $values);

            if (PEAR::isError($res)) {
                echo $res->getMessage();
            }

            throw new k_http_Redirect($this->context->url('../'));

        }

    }

    function forward($name)
    {
        $next = new VIH_Intranet_Controller_Protokol_Show($this, $name);
        $next->handleRequest();
    }
}

