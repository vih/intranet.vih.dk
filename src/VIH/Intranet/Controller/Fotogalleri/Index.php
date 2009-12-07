<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Fotogalleri_Index extends k_Component
{
    public $map = array('create' => 'VIH_Intranet_Controller_Fotogalleri_Edit',);

    private $db;
    protected $template;
    function __construct(MDB2_Driver_Common $db, k_TemplateFactory $template)
    {
        $this->db = $db;
        $this->template = $template;
    }

    function renderHtml()
    {
        $db = $this->db;

        $result = $db->query('SELECT id, description, DATE_FORMAT(date_created, "%d-%m-%Y") AS dk_date_created, active FROM fotogalleri ORDER BY date_created DESC');
        if (PEAR::isError($result)) {
            throw new Exception($result->getUserInfo());
        }

        $i = 0;
        $list = array();
        while($row = $result->fetchRow()) {
            $list['gallerier'][$i] = $row;
            $list['gallerier'][$i]['url_show'] = $this->url($row['id']);
            $list['gallerier'][$i]['url_edit'] = $this->url($row['id'] . '/edit');
            if($row['active'] == 1) {
                $list['gallerier'][$i]['url_active'] = $this->url($row['id'].'/deactivate');
                $list['gallerier'][$i]['active'] = 'Aktiv';
            }
            else {
                $list['gallerier'][$i]['url_active'] = $this->url($row['id'].'/activate');
                $list['gallerier'][$i]['active'] = 'Inaktiv';
            }
            $i++;

        }

        $this->document->setTitle('Fotogalleri');
        $this->document->options = array($this->url('create') => 'Tilføj');
        $tpl = $this->template->create('fotogalleri/gallerier');

        return '<h2>Gallerier</h2>
            ' .$tpl->render($this, $list);

    }

    function map($name)
    {
        return 'VIH_Intranet_Controller_Fotogalleri_Show';
    }
}