<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Fotogalleri_Index extends k_Controller
{
    public $map = array('create' => 'VIH_Intranet_Controller_Fotogalleri_Edit',);

    function GET()
    {
        $db = $this->registry->get('database:mdb2');

        $result = $db->query('SELECT id, description, DATE_FORMAT(date_created, "%d-%m-%Y") AS dk_date_created, active FROM fotogalleri ORDER BY date_created DESC');
        if (PEAR::isError($result)) {
            die($result->getUserInfo());
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

        $this->document->title = 'Fotogalleri';
        $this->document->options = array($this->url('create') => 'Tilføj');

        return '<h2>Gallerier</h2>
            ' .$this->render('vih/intranet/view/fotogalleri/gallerier-tpl.php', $list);

    }

    function forward($name)
    {
        if ($name == 'create') {
            $next = new VIH_Intranet_Controller_Fotogalleri_Edit($this, $name);
            return $next->handleRequest();
        }

        $next = new VIH_Intranet_Controller_Fotogalleri_Show($this, $name);
        return $next->handleRequest();
    }
}