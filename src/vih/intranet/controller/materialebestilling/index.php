<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Materialebestilling_Index extends k_Controller
{
    function GET()
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

        $this->document->title = 'Materialebestilling';
        $this->document->options = array($this->url(null, array('filter' => 'all')) =>'Alle');


        $data = array('headline' => 'Materialebestilling',
                      'bestillinger' => $bestillinger);

        return $this->render(dirname(__FILE__) . '/../../view/materialebestilling/index-tpl.php', $data);

    }
}
