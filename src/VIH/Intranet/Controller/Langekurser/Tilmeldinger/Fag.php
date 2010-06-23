<?php

class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Fag extends VIH_Controller_LangtKursus_Login_Fag
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function getRegistration()
    {
        return new VIH_Model_LangtKursus_Tilmelding($this->context->name());
    }

    function renderHtml()
    {
        $this->document->setTitle($this->getRegistration()->get('navn').' fag på '.$this->getRegistration()->getKursus()->getKursusNavn());
        $this->document->addOption('Tilmeldingen', $this->url('../'));
        $this->document->addOption('Diplom (pdf)', $this->url('../diplom'));
    	return parent::GET();
    }
}