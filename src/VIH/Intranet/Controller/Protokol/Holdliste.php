<?php
class VIH_Intranet_Controller_Protokol_Holdliste extends k_Component
{
    private $form;

    private $db;
    protected $template;

    function __construct(DB_Sql $db, k_TemplateFactory $template)
    {
        $this->db = $db;
        $this->template = $template;
    }


    static public function getTypeKeys()
    {
        return        $type_key = array(1 => 'fri', // fri
                          2 => 'syg', // syg
                          3 => 'fra', // fravær
                          4 => 'mun', // mundtlig advarsel
                          5 => 'skr', // skriftlig advarsel
                          6 => 'hen', // henstilling
                          7 => 'hje',  // hjemsendt
                          8 => 'and');
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

    function renderHtml()
    {
        $date = date('Y-m-d');
        if ($this->query('date')) {
            $get = $this->query('date');
            $date = $get['Y'] . '-' . $get['M'] . '-' .$get['d'];
        }

        $this->getForm()->setDefaults(array('date' => $date));


        $db = $this->db;
        $db->query("SELECT tilmelding.id, tilmelding.dato_slut
            FROM langtkursus_tilmelding tilmelding
                INNER JOIN langtkursus ON langtkursus.id = tilmelding.kursus_id
                INNER JOIN adresse ON tilmelding.adresse_id = adresse.id
            WHERE
                ((tilmelding.dato_slut > langtkursus.dato_slut AND tilmelding.dato_start < DATE_ADD('$date', INTERVAL 3 DAY) AND tilmelding.dato_slut > NOW())
                OR (tilmelding.dato_slut <= langtkursus.dato_slut AND tilmelding.dato_start < DATE_ADD('$date', INTERVAL 3 DAY) AND tilmelding.dato_slut > '$date')
                OR (tilmelding.dato_slut = '0000-00-00' AND langtkursus.dato_start < DATE_ADD('$date', INTERVAL 3 DAY) AND langtkursus.dato_slut > '$date'))
                AND tilmelding.active = 1
            ORDER BY adresse.fornavn ASC, adresse.efternavn ASC");

        $list = array();
        while($db->nextRecord()) {
            $list[] = new VIH_Model_LangtKursus_Tilmelding($db->f('id'));
        }

        $data = array('elever' => $list);

        $this->document->setTitle('Holdliste');
        $this->document->options = array(
            $this->url('../') => 'Protokol'
        );

        $tpl = $this->template->create('protokol/holdliste');

        return $this->getForm()->toHTML().'
            <p>Antal elever: ' . $db->numRows() . '</p>'
            . $tpl->render($this, $data);
    }

    function map($name)
    {
        return 'VIH_Intranet_Controller_Protokol_Elev';
    }
}