<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Adresseliste
{
    private $db;

    protected $template;

    function __construct(DB_Sql $db, k_TemplateFactory $template)
    {
        $this->db = $db;
        $this->template = $template;
    }

    function renderHtml()
    {
        $db = $this->db;
        $db->query("SELECT tilmelding.id, tilmelding.dato_slut
            FROM langtkursus_tilmelding tilmelding
                INNER JOIN langtkursus ON langtkursus.id = tilmelding.kursus_id
                INNER JOIN adresse ON tilmelding.adresse_id = adresse.id
            WHERE
                ((tilmelding.dato_slut > langtkursus.dato_slut AND tilmelding.dato_start < DATE_ADD(NOW(), INTERVAL 14 DAY) AND tilmelding.dato_slut > NOW())
                OR (tilmelding.dato_slut <= langtkursus.dato_slut AND tilmelding.dato_start < DATE_ADD(NOW(), INTERVAL 14 DAY) AND tilmelding.dato_slut > NOW())
                OR (tilmelding.dato_slut = '0000-00-00' AND langtkursus.dato_start < DATE_ADD(NOW(), INTERVAL 14 DAY) AND langtkursus.dato_slut > NOW()))
                AND tilmelding.active = 1
            ORDER BY adresse.fornavn ASC, adresse.efternavn ASC");

        $list = array();
        while($db->nextRecord()) {
            $list[] = new VIH_Model_LangtKursus_Tilmelding($db->f('id'));
        }

        $this->document->setTitle('Adresseliste');
        $this->document->options = array();

        $data = array('elever' => $list);


        return '<p>Antal elever: ' . $db->numRows() . '</p>'
            . $this->render(dirname(__FILE__) . '/../../view/langekurser/adresseliste-tpl.php');
    }
}
