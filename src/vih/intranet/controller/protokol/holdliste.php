<?php
class VIH_Intranet_Controller_Protokol_Holdliste extends k_Controller
{
    private $form;

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

    function GET()
    {
        $date = date('Y-m-d');
        if (!empty($this->GET['date'])) {
            $date = $this->GET['date']['Y'] . '-' . $this->GET['date']['M'] . '-' .$this->GET['date']['d'];
        }

        $this->getForm()->setDefaults(array('date' => $date));


        $db = $this->registry->get('database:db_sql');
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

        $this->document->title = 'Holdliste';
        $this->document->options = array(
            $this->url('../') => 'Protokol'
        ); 

        return $this->getForm()->toHTML().'
            <p>Antal elever: ' . $db->numRows() . '</p>'
            . $this->render('vih/intranet/view/protokol/holdliste-tpl.php', $data);
    }

    function forward($name)
    {
            $next = new VIH_Intranet_Controller_Protokol_Elev($this, $name);
            return $next->handleRequest();
    }
}