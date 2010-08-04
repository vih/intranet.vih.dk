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

        return ($this->form = $this->context->getForm());
    }

    function renderHtmlDelete()
    {
        $res = $this->db->query('DELETE FROM langtkursus_tilmelding_protokol_item WHERE id = ' . (int)$this->name());

        return new k_SeeOther($this->url('../../'));
    }
}