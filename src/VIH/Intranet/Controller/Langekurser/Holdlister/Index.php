<?php
class VIH_Intranet_Controller_Langekurser_Holdlister_Index extends k_Component
{
    private $form;
    protected $template;
    protected $mdb2;
    protected $db_sql;

    function __construct(k_TemplateFactory $template, MDB2_Driver_Common $mdb2, DB_Sql $db_sql)
    {
        $this->template = $template;
        $this->mdb2 = $mdb2;
        $this->db_sql = $db_sql;
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
            $date = $get['date']['Y'] . '-' . $get['date']['M'] . '-' .$get['date']['d'];
        }

        $defaults = array('date' => $date);

        $this->getForm()->setDefaults($defaults);

        // find alle registrations der er p� skolen p� en given dato
        // tjek hvilke fag de har hver is�r

        $this->db_sql->query("SELECT DISTINCT(fag.id)
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
            $list[] = new VIH_Model_Fag($this->db_sql->f('id'));
        }

        $data = array('fag' => $list, 'date' => $date);

        $this->document->setTitle('Holdlister');

        $tpl = $this->template->create('VIH/Intranet/view/holdlister/holdlister');
        return $this->getForm()->toHTML() . $this->render($this, $data);
    }

    function getCount($fag)
    {
        $date = date('Y-m-d');
        if ($get = $this->query('date')) {
            $date = $get['date']['Y'] . '-' . $get['date']['M'] . '-' .$get['date']['d'];
        }

        $this->getForm()->setDefaults(array('date' => $date));

        $result = $this->mdb2->query("SELECT DISTINCT(tilmelding.id)
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

    function map($name)
    {
        return 'VIH_Intranet_Controller_Langekurser_Holdlister_Show'';
    }
}