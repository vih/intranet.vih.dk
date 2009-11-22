<?php
class VIH_Intranet_Controller_LangeKurser_Holdlister_Index extends k_Controller
{
    private $form;

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

        $defaults = array('date' => $date);

        $this->getForm()->setDefaults($defaults);

        // find alle registrations der er på skolen på en given dato
        // tjek hvilke fag de har hver især
        
        $db = new DB_Sql();
        $db->query("SELECT DISTINCT(fag.id)
            FROM langtkursus_tilmelding tilmelding
                INNER JOIN langtkursus
                    ON langtkursus.id = tilmelding.kursus_id
                INNER JOIN langtkursus_tilmelding_x_fag x_fag
                    ON tilmelding.id = x_fag.tilmelding_id
                INNER JOIN langtkursus_fag fag
                    ON x_fag.fag_id = fag.id
                INNER JOIN langtkursus_fag_periode periode
                    ON langtkursus.id = periode.langtkursus_id
            WHERE
                (
                    (tilmelding.dato_start <= '$date'
                        AND tilmelding.dato_slut > NOW())
                    OR (tilmelding.dato_slut = '0000-00-00'
                        AND langtkursus.dato_start <= '$date' AND langtkursus.dato_slut > NOW())
                )
                AND (tilmelding.active = 1)
                AND (periode.date_start <= '$date'
                    AND periode.date_end >= '$date' AND x_fag.periode_id = periode.id)
            ORDER BY fag.fag_gruppe_id ASC, fag.navn ASC");

        $list = array();
        while($db->nextRecord()) {
            $list[] = new VIH_Model_Fag($db->f('id'));
        }

        $data = array('fag' => $list, 'date' => $date);

        $this->document->title = 'Holdlister';

        return $this->getForm()->toHTML() . $this->render('vih/intranet/view/holdlister/holdlister-tpl.php', $data);
    }

    function getCount($fag)
    {
        $date = date('Y-m-d');
        if (!empty($this->GET['date'])) {
            $date = $this->GET['date']['Y'] . '-' . $this->GET['date']['M'] . '-' .$this->GET['date']['d'];
        }

        $this->getForm()->setDefaults(array('date' => $date));

        $db = MDB2::factory(DB_DSN);
        if (PEAR::isError($db)) {
            throw new Exception($db->getUserInfo());
        }

        $result = $db->query("SELECT DISTINCT(tilmelding.id)
            FROM langtkursus_tilmelding tilmelding
                INNER JOIN langtkursus
                    ON langtkursus.id = tilmelding.kursus_id
                INNER JOIN langtkursus_tilmelding_x_fag x_fag
                    ON tilmelding.id = x_fag.tilmelding_id
                INNER JOIN langtkursus_fag fag
                    ON x_fag.fag_id = fag.id
                INNER JOIN langtkursus_fag_periode periode
                    ON langtkursus.id = periode.langtkursus_id
            WHERE
                (
                    (tilmelding.dato_start <= '$date'
                        AND tilmelding.dato_slut >= '$date')
                    OR (tilmelding.dato_slut = '0000-00-00'
                        AND langtkursus.dato_start <= '$date'
                        AND langtkursus.dato_slut >= '$date')
                )
                AND tilmelding.active = 1
                AND x_fag.fag_id = ".$fag->get('id') ."
                AND (
                    periode.date_start <= '$date'
                        AND periode.date_end >= '$date'
                        AND x_fag.periode_id = periode.id
                )");

        if (PEAR::isError($result)) {
            throw new Exception($result->getUserInfo());
        }

        return $result->numRows();
    }

    function forward($name)
    {
        $next = new VIH_Intranet_Controller_LangeKurser_Holdlister_Show($this, $name);
        return $next->handleRequest();
    }
}