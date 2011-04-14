<?php
class VIH_Intranet_Controller_Protokol_Show extends k_Component
{
    protected $db;
    protected $form;

    function __construct(DB_common $db)
    {
        $this->db = $db;
    }

    function renderHtml()
    {
        return 'Intentionally left blank';
    }

    function renderHtmlEdit()
    {
        $row = array();

        $res = $this->db->query('SELECT * FROM langtkursus_tilmelding_protokol_item WHERE id = ' . (int)$this->name());

        if (PEAR::isError($res)) {
            throw new Exception($res->getMessage());
        }

        $res->fetchInto($row, DB_FETCHMODE_ASSOC);

        $this->getForm()->setDefaults(array('text' => $row['text'],
                                     'date_start' => $row['date_start'],
                                     'date_end' => $row['date_end'],
                                     'elev_id' => $row['tilmelding_id'],
                                     'type' => $row['type_key'],
                                     'id' => $this->name()));

        $elev_id = $this->context->context->name();

        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->context->name());

        $this->document->setTitle('Indtast ' . $tilmelding->get('navn'));
        return $this->getForm()->toHTML();
    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $date_start = $this->getForm()->exportValue('date_start');
            $date_end = $this->getForm()->exportValue('date_end');

            $fields = array('date_created', 'date_updated', 'date_start', 'date_end', 'tilmelding_id', 'text', 'type_key');
            $values = array('NOW()',
                            'NOW()',
                            $date_start['Y'] . '-' . $date_start['m'] . '-' . $date_start['d'] . ' ' . $date_start['H'] . ':' . $date_start['i'],
                            $date_end['Y'] . '-' . $date_end['m'] . '-' . $date_end['d'] . ' ' . $date_end['H'] . ':' . $date_end['i'],
                            $this->getForm()->exportValue('elev_id'),
                            $this->body('text'),
                            $this->getform()->exportValue('type'));

            $sth = $this->db->autoPrepare('langtkursus_tilmelding_protokol_item', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $this->name());

            $res = $this->db->execute($sth, $values);

            if (PEAR::isError($res)) {
                throw new Exception($res->getMessage());
            }

            return new k_SeeOther($this->context->url('../'));

        }

        return $this->render();
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $options = array('format' => 'd m Y H i',
                         'optionIncrement' => array('i' => 15),
                         'minYear' => date('Y') - 2,
                         'maxYear' => date('Y') + 2);

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

    function renderHtmlDelete()
    {
        $res = $this->db->query('DELETE FROM langtkursus_tilmelding_protokol_item WHERE id = ' . (int)$this->name());
        return new k_SeeOther($this->url('../../'));
    }
}
