<?php
class VIH_Intranet_Controller_Nyheder_Index extends k_Controller
{
    public $map = array('create' => 'vih_intranet_controller_news_edit');

    function GET()
    {
        $this->document->title = 'Nyheder';
        $this->document->options = array($this->url('create') => 'Opret');

        $data = array('nyheder' => VIH_News::getList('', 100));

        return $this->render('vih/intranet/view/nyheder/nyheder-tpl.php', $data);

    }

    function forward($name)
    {
        if ($name == 'create') {
            $next = new VIH_Intranet_Controller_Nyheder_Edit($this, $name);
            return $next->handleRequest();
        }
        $next = new VIH_Intranet_Controller_Nyheder_Show($this, $name);
        return $next->handleRequest();
    }
}