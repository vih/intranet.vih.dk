<?php
class VIH_Intranet_Document extends k_Document
{
    public $options;
    public $navigation;

    function navigation()
    {
        return $this->navigation;
    }

    function options()
    {
        if (empty($this->options)) return array();
        return $this->options;
    }
}
