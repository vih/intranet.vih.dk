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
        if ($this->query('sent')) {
            $bestilling = new VIH_Model_MaterialeBestilling((int)$this->query('sent'));
            $bestilling->setSent();
        }

        $bestilling = new VIH_Model_MaterialeBestilling;

        if ($this->query('filter')) {
            $bestillinger = $bestilling->getList($this->query('filter'));
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
