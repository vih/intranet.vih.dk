<?php
class VIH_Intranet_Document extends k_Document
{
    public $navigation;
    public $help;

    protected $crumbtrail = array();
    public $options = array();

    function crumbtrail()
    {
        return $this->crumbtrail;
    }

    function addCrumb($title, $url)
    {
        return $this->crumbtrail[] = array('title' => $title, 'url' => $url);
    }

    function navigation()
    {
        return $this->navigation;
    }

    function addOption($title, $url)
    {
        return $this->options[] = array('title' => $title, 'url' => $url);
    }

    function options()
    {
        if (empty($this->options)) {
            return array();
        }
        return $this->options;
    }

    function help()
    {
        return $this->help;
    }
}