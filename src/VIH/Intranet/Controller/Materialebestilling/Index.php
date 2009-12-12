<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Materialebestilling_Index extends k_Component
{
     protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        if (!empty($this->GET['sent'])) {
            $bestilling = new VIH_Model_MaterialeBestilling((int)$this->GET['sent']);
            $bestilling->setSent();
        }

        $bestilling = new VIH_Model_MaterialeBestilling;

        if (!empty($this->GET['filter'])) {
            $bestillinger = $bestilling->getList($this->GET['filter']);
        } else {
            $bestillinger = $bestilling->getList();
        }

        $this->document->setTitle('Materialebestilling');
        $this->document->options = array($this->url(null, array('filter' => 'all')) =>'Alle');

        $data = array('headline' => 'Materialebestilling',
                      'bestillinger' => $bestillinger);

        $tpl = $this->template->create('materialebestilling/index');
        return $tpl->render($this, $data);
    }
}