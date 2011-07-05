<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_ExportCSV extends k_Component
{
    private $form;
    protected $template;
    protected $db;

    function __construct(k_TemplateFactory $template, DB_Sql $db)
    {
        $this->template = $template;
        $this->db = $db;
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }
        $date_options = array('minYear' => date('Y') - 10, 'maxYear' => date('Y') + 5);
        $form = new HTML_QuickForm('holdliste', 'GET', $this->url() . '.txt');
        $form->addElement('date', 'date', 'date', $date_options);
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

        $this->document->setTitle('Eksporter CSV');
        $this->document->addOption('Tilmeldinger', $this->url('../'));

        return $this->getForm()->toHTML();
    }

    function renderTxt()
    {
        $date = date('Y-m-d');
        if ($this->query('date')) {
            $post = $this->query();
            $date = $post['date']['Y'] . '-' . $post['date']['M'] . '-' .$post['date']['d'];
        }

        // Ensures that PEAR uses correct config file.
        PEAR_Config::singleton(PATH_ROOT.'.pearrc');

        $this->db->query("SELECT tilmelding.id, tilmelding.dato_slut
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
        while ($this->db->nextRecord()) {
            $t = new VIH_Model_LangtKursus_Tilmelding($this->db->f('id'));

            // strange way to do it, but only way to get the header match data!
            $list[$i][3] = $t->get('navn');
            $list[$i][5] = $t->get('email');
            $list[$i][6] = $t->get('adresse');
            $list[$i][7] = $t->get('postby');
            $list[$i][8] = $t->get('postnr');
            $list[$i][11] = $t->get('telefon');
            // $list[$i][10] = $t->get('nationalitet');
            $list[$i][13] = $t->get('mobil');

            $i++;
        }

        $address_book = new Contact_AddressBook;
        $csv_builder = $address_book->createBuilder('csv_wab');
        if(PEAR::isError($csv_builder)) {
            throw new Exception('CSV_builder error: '.$csv_builder->getUserInfo());
        }

        $result = $csv_builder->setData($list);
        if (PEAR::isError($result)) {
            throw new Exception('CSV_builder data error: '.$result->getUserInfo());
        }

        // @todo some error in the build. It has been traced back to getConfig();

        $result = $csv_builder->build();


        if (PEAR::isError($result)) {
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
        return $csv_builder->result;
    }
}
