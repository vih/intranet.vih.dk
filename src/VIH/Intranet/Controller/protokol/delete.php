<?php
class VIH_Intranet_Controller_Protokol_Delete extends k_Controller
{
    function GET()
    {
        $db = $this->registry->get('database:pear');

        $res = $db->query('DELETE FROM langtkursus_tilmelding_protokol_item WHERE id = ' . (int)$this->context->name);

        throw new k_http_Redirect($this->url('../../../'));
    }
}