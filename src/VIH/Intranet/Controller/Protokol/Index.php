<?php
class VIH_Intranet_Controller_Protokol_Index extends k_Component
{
    protected $form;
    protected $template;
    protected $db;

    function __construct(k_TemplateFactory $template, DB_common $db)
    {
        $this->template = $template;
        $this->db = $db;
    }

    function map($name)
    {
        return 'VIH_Intranet_Controller_Protokol_Holdliste';
    }

    function getTypeKeys()
    {
        return $type_key = array(1 => 'fri', // fri
                          2 => 'syg', // syg
                          3 => 'fra', // frav�r
                          4 => 'mun', // mundtlig advars       el
                          5 => 'skr', // skriftlig advarsel
                          6 => 'hen', // henstilling
                          7 => 'hje',  // hjemsendt
                          8 => 'and');

    }

    function renderHtml()
    {
        $type_key = $this->getTypeKeys();

        if ($this->getForm()->validate()) {
            $date = $this->getForm()->exportValue('date');
            $date_graense = 'DATE_FORMAT("'.$date['Y'] . '-' .  $date['M'] . '-' . $date['d'].'", "%Y-%m-%d")';
        } else {
            $date_graense = 'DATE_FORMAT(NOW(), "%Y-%m-%d")';
        }

        // dagens fritagelser
        $sql = '
            SELECT *,
                    DATE_FORMAT(item.date_start, "%d-%m %H:%i") AS date_start_dk,
                    DATE_FORMAT(item.date_end, "%d-%m %H:%i") AS date_end_dk,
                    item.id AS id,
                    tilmelding.id AS elev_id
                FROM langtkursus_tilmelding_protokol_item item
                INNER JOIN langtkursus_tilmelding tilmelding
                    ON tilmelding.id = item.tilmelding_id
                WHERE DATE_FORMAT(item.date_start, "%Y-%m-%d") <=  '. $date_graense .'
                    AND DATE_FORMAT(item.date_end, "%Y-%m-%d") >= '. $date_graense .'
                    AND active = 1
                ORDER BY tilmelding.id ASC, date_start ASC, date_end ASC';

        $res = $this->db->query($sql);

        // Always check that result is not an error
        if (PEAR::isError($res)) {
            throw new Exception($res->getMessage());
        }

        $list = array('vis_navn' => 'true', 'items' => $res, 'type_key' => $type_key);

        $sql = '
            SELECT *,
                    DATE_FORMAT(item.date_start, "%d-%m %H:%i") AS date_start_dk,
                    DATE_FORMAT(item.date_end, "%d-%m %H:%i") AS date_end_dk,
                    item.id AS id,
                    tilmelding.id AS elev_id
                FROM langtkursus_tilmelding_protokol_item item
                INNER JOIN langtkursus_tilmelding tilmelding
                    ON tilmelding.id = item.tilmelding_id
                WHERE DATE_FORMAT(item.date_start, "%Y-%m-%d") >=  DATE_FORMAT("'.date('Y-m-d').'", "%Y-%m-%d")
                    AND DATE_FORMAT(item.date_end, "%Y-%m-%d") <= '. $date_graense .'
                    AND active = 1 AND type_key = 1
                ORDER BY date_start ASC, date_end ASC';

        $res = $this->db->query($sql);

        // Always check that result is not an error
        if (PEAR::isError($res)) {
            throw new Exception($res->getMessage());
        }

        $list1 = array('vis_navn' => 'true',
                       'items' => $res,
                       'type_key' => $type_key);

        // topscorere
        /*
        $res =& $db->query('SELECT *, elev.id AS elev_id, count(tilmelding_id) AS antal
            FROM langtkursus_tilmelding_protokol_item item
            INNER JOIN langtkursus_tilmelding tilmelding
                ON item.tilmelding_id = tilmelding.id
            INNER JOIN langtkursus
                ON tilmelding.kursus_id = langtkursus.id
            WHERE
                tilmelding.active = 1
            GROUP BY tilmelding_id
            ORDER BY antal DESC LIMIT 30');

        // Always check that result is not an error
        if (PEAR::isError($res)) {
            die($res->getMessage());
        }
        $list = '<ul>';
        while ($res->fetchInto($row, DB_FETCHMODE_ASSOC)) {
            $tilmelding = new VIH_Model_LangtKursus_Tilmelding($row['elev_id']);
            $list .= '<li><a href="elev.php?id='.$row['id'].'">' . $tilmelding->get('navn') . '</a> ('.$row['antal'].')</li>' . "\n";
        }
        $list .= '</ul>';
        */

        $this->document->setTitle('Protokol');
        $this->document->addOption('Holdliste', $this->url('holdliste'));
        $this->document->addOption('Hurtig indtastning', $this->url('holdliste/batch'));

        $tpl = $this->template->create('protokol/liste');

        return '<h2>Fraværende</h2>
            '.$this->getForm()->toHTML().'
            ' .$tpl->render($this, $list) .
            '<h2>Fritagelser indtil dato</h2>'
            . $tpl->render($this, $list1);
    }

    function postForm()
    {

    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm('protokol', 'GET', $this->url());
        $form->addElement('date', 'date', 'Dato');
        $form->addElement('submit', null, 'Hent');
        $form->setDefaults(array('date' => date('Y-m-d')));

        return ($this->form = $form);
    }
}