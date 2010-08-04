<?php
class VIH_Intranet_Controller_Protokol_Holdliste extends k_Component
{
    protected $form;
    protected $db;
    protected $template;

    function __construct(DB_Sql $db, k_TemplateFactory $template)
    {
        $this->db = $db;
        $this->template = $template;
    }

    function map($name)
    {
        if ($name == 'batch') {
            return 'VIH_Intranet_Controller_Protokol_Batch';
        }
        return 'VIH_Intranet_Controller_Protokol_Elev';
    }

    function renderHtml()
    {
        $this->getForm()->setDefaults(array('date' => $this->getDate()));

        $data = array('elever' => $this->getTilmeldinger());

        $this->document->setTitle('Holdliste');
        $this->document->addOption('Protokol', $this->url('../'));

        $tpl = $this->template->create('protokol/holdliste');

        return $this->getForm()->toHTML().'
            <p>Antal elever: ' . $this->db->numRows() . '</p>'
            . $tpl->render($this, $data);
    }

    function getDate()
    {
        if ($this->query('date')) {
            $get = $this->query('date');
            $date = $get['Y'] . '-' . $get['M'] . '-' .$get['d'];
        } else {
            $date = date('Y-m-d');
        }

        return $date;
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $form = new HTML_QuickForm('holdliste', 'GET', $this->url());
        $form->addElement('date', 'date', 'date');
        $form->addElement('submit', null, 'Hent');

        return ($this->form = $form);
    }

    function getTilmeldinger()
    {
        $this->db->query("SELECT tilmelding.id, tilmelding.dato_slut
            FROM langtkursus_tilmelding tilmelding
                INNER JOIN langtkursus ON langtkursus.id = tilmelding.kursus_id
                INNER JOIN adresse ON tilmelding.adresse_id = adresse.id
            WHERE
                ((tilmelding.dato_slut > langtkursus.dato_slut AND tilmelding.dato_start < DATE_ADD('".$this->getDate()."', INTERVAL 3 DAY) AND tilmelding.dato_slut > NOW())
                OR (tilmelding.dato_slut <= langtkursus.dato_slut AND tilmelding.dato_start < DATE_ADD('".$this->getDate()."', INTERVAL 3 DAY) AND tilmelding.dato_slut > '".$this->getDate()."')
                OR (tilmelding.dato_slut = '0000-00-00' AND langtkursus.dato_start < DATE_ADD('".$this->getDate()."', INTERVAL 3 DAY) AND langtkursus.dato_slut > '".$this->getDate()."'))
                AND tilmelding.active = 1
            ORDER BY adresse.fornavn ASC, adresse.efternavn ASC");

        $list = array();
        while ($this->db->nextRecord()) {
            $list[] = new VIH_Model_LangtKursus_Tilmelding($this->db->f('id'));
        }
        return $list;
    }

    static public function getTypeKeys()
    {
        return $type_key = array(1 => 'fri', // fri
                          2 => 'syg', // syg
                          3 => 'fra', // frav�r
                          4 => 'mun', // mundtlig advarsel
                          5 => 'skr', // skriftlig advarsel
                          6 => 'hen', // henstilling
                          7 => 'hje',  // hjemsendt
                          8 => 'and');
    }
}