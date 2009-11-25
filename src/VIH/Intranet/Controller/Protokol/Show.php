<?php
class VIH_Intranet_Controller_Protokol_Show extends k_Component
{
    function map($name)
    {
        if ($name == 'delete') {
            return 'VIH_Intranet_Controller_Protokol_Delete';
        }
    }
}