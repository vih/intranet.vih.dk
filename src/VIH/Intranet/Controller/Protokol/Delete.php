<?php
class VIH_Intranet_Controller_Protokol_Delete extends k_Component
{
    private $db;

    function __construct(DB_common $db)
    {
        $this->db = $db;
    }

    function renderHtml()
    {
        $res = $this->db->query('DELETE FROM langtkursus_tilmelding_protokol_item WHERE id = ' . (int)$this->context->name());

        return new k_SeeOther($this->url('../../../'));
    }
}