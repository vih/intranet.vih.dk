<?php
class VIH_Intranet_Controller_Langekurser_Holdlister_Show extends k_Component
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

    function renderHtml()
    {
        $get = $this->query();
        if (1==2 AND !empty($get['date']['Y'])) {
            $date = $get['date']['Y'] . '-' . $get['date']['M'] . '-' .$get['date']['d'];
        } elseif (!empty($get['date'])) {
            $date = $get['date'];
        } else {
            $date = date('Y-m-d');
        }

        $this->getForm()->setDefaults(array('date' => $date));

        $db = new DB_Sql();

        // Trækker alle ud på den pågældende holdliste

        $db->query("SELECT DISTINCT(tilmelding.id) AS id, x_fag.hold, x_fag.id AS hold_id
            FROM langtkursus_tilmelding tilmelding
                INNER JOIN langtkursus ON langtkursus.id = tilmelding.kursus_id
                INNER JOIN adresse ON tilmelding.adresse_id = adresse.id
                INNER JOIN langtkursus_tilmelding_x_fag x_fag ON tilmelding.id = x_fag.tilmelding_id
                INNER JOIN langtkursus_fag fag ON x_fag.fag_id = fag.id
                INNER JOIN langtkursus_fag_periode periode ON langtkursus.id = periode.langtkursus_id
            WHERE
                (
                    (tilmelding.dato_start <= '$date' AND tilmelding.dato_slut >= '$date')
                OR (tilmelding.dato_slut = '0000-00-00' AND langtkursus.dato_start <= '$date' AND langtkursus.dato_slut >= '$date'))
                AND tilmelding.active = 1 AND x_fag.fag_id = ".$this->name()." AND (periode.date_start <= '$date' AND periode.date_end >= '$date')
                AND (periode.date_start <= '$date' AND periode.date_end >= '$date' AND x_fag.periode_id = periode.id)
            ORDER BY x_fag.hold ASC, adresse.fornavn ASC");

        $list = array();
        while($db->nextRecord()) {
            $list[$db->f('id')] = new VIH_Model_LangtKursus_Tilmelding($db->f('id'));
            $list[$db->f('id')]->value['hold'] = (int)$db->f('hold');
            $list[$db->f('id')]->value['hold_id'] = (int)$db->f('hold_id');
        }

        $fag = new VIH_Model_Fag($this->name());

        // skal hente holdnumrene for den pågældende tilmelding
        $data = array('tilmeldinger' => $list);

        // $this->getForm()->toHTML()
        // echo $date;

        $this->document->setTitle($fag->get('navn'));

        return '<p>'.count($list).'</p>' . $date . $this->render('VIH/Intranet/view/holdlister/holdliste-tpl.php', $data);
    }

    function postForm()
    {
        $db = new DB_Sql();
        foreach ($this->body() AS $key=>$value) {
            foreach ($value AS $id => $hold) {
                if (!$hold) continue;
                $db->query("UPDATE langtkursus_tilmelding_x_fag SET hold = ".$hold." WHERE id = " . $id);
            }
        }

        return new k_SeeOther($this->url());

    }
}