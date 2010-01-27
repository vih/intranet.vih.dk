<?php
class VIH_Intranet_Controller_Betaling_Show extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        return $this->name();
    }

    function map($name)
    {
        if ($name == 'capture') {
            return 'VIH_Intranet_Controller_Betaling_Capture';
        } elseif ($name == 'reverse') {
            return 'VIH_Intranet_Controller_Betaling_Reverse';
        }
    }
}
