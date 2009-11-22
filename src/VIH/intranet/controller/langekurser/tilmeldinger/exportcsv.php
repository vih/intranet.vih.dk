<?php
class VIH_Intranet_Controller_LangeKurser_Tilmeldinger_ExportCSV extends k_Controller
{
    private $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $form = new HTML_QuickForm('holdliste', 'POST', $this->url());
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

        $this->document->title = 'Eksporter CSV';
        $this->document->options = array($this->url('../') => 'Tilmeldinger');

        return $this->getForm()->toHTML();

    }

    function POST()
    {
        $date = date('Y-m-d');
        if (!empty($this->POST['date'])) {
            $date = $this->POST['date']['Y'] . '-' . $this->POST['date']['M'] . '-' .$this->POST['date']['d'];
        }
        
        // Ensures that PEAR uses correct config file.
        PEAR_Config::singleton(PATH_ROOT.'.pearrc');

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
        $i = 0;
        while($db->nextRecord()) {
            $t = new VIH_Model_LangtKursus_Tilmelding($db->f('id'));

            /**
             * strange way to do it, but only way to get the header match data!
             */
            $list[$i][3] = $t->get('navn');
            $list[$i][5] = $t->get('email');
            $list[$i][6] = $t->get('adresse');
            $list[$i][7] = $t->get('postby');
            $list[$i][8] = $t->get('postnr');
            $list[$i][11] = $t->get('telefon');
            // $list[$i][10] = $t->get('nationalitet');
            $list[$i][13] = $t->get('mobil');


            /*
            $list[$i]['displayname'] = $t->get('navn');
            $list[$i]['home_address'] = $t->get('adresse');
            $list[$i]['home_zipcode'] = $t->get('postnr');
            $list[$i]['home_city'] = $t->get('postby');
            $list[$i]['home_phone'] = $t->get('telefon');
            $list[$i]['home_country'] = $t->get('nationalitet');
            $list[$i]['email'] = $t->get('email');
            $list[$i]['mobile'] = $t->get('mobil');
            */
            $i++;
        }

        $address_book = new Contact_AddressBook;
        $csv_builder = $address_book->createBuilder('csv_wab');
        if(PEAR::isError($csv_builder)) {
            throw new Exception('CSV_builder error: '.$csv_builder->getUserInfo());
        }

        $result = $csv_builder->setData($list);
        if(PEAR::isError($result)) {
            throw new Exception('CSV_builder data error: '.$result->getUserInfo());
        }

        // @todo some error in the build. It has been traced back to getConfig();

        $result = $csv_builder->build();


        if(PEAR::isError($result)) {
            throw new Exception('CSV_builder build error: '.$result->getUserInfo());
        }

        // This could be nice, but there is an error in the method!
        // echo $csv_builder->download('holdliste');

        // instead the following should do the job!
        if (headers_sent()) {
            throw new Exception('Cannot process headers, headers already sent');
        }

        $filename = 'holdliste.csv';
        if (Net_UserAgent_Detect::isIE()) {
            // IE need specific headers
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
        }

        header('Content-Type: ' . $csv_builder->mime);
        echo $csv_builder->result;
        exit;
    }

    function forward($name)
    {
            $next = new VIH_Intranet_Controller_Protokol_Elev($this, $name);
            return $next->handleRequest();
    }
}
