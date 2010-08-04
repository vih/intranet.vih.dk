<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Index extends k_Component
{
    protected $map = array(
    					'admin'               => 'VIH_Intranet_Controller_Index',
                        'login'               => 'VIH_Intranet_Controller_Login',
                        'logout'              => 'VIH_Intranet_Controller_Logout',
                        'langekurser'         => 'VIH_Intranet_Controller_Langekurser_Index',
                        'kortekurser'         => 'VIH_Intranet_Controller_Kortekurser_Index',
                        'faciliteter'         => 'VIH_Intranet_Controller_Faciliteter_Index',
                        'materialebestilling' => 'VIH_Intranet_Controller_Materialebestilling_Index',
                        'ansatte'             => 'VIH_Intranet_Controller_Ansatte_Index',
                        'fag'                 => 'VIH_Intranet_Controller_Fag_Index',
                        'betaling'            => 'VIH_Intranet_Controller_Betaling_Index',
                        'nyheder'             => 'VIH_Intranet_Controller_Nyheder_Index',
                        'kalender'            => 'VIH_Intranet_Controller_Calendar_Index',
                        'protokol'            => 'VIH_Intranet_Controller_Protokol_Index',
                        'fotogalleri'         => 'VIH_Intranet_Controller_Fotogalleri_Index',
                        'filemanager'         => 'Intraface_Filehandler_Controller_Index',
                        'file'                => 'Intraface_Filehandler_Controller_Viewer',
                        'keyword'             => 'Intraface_Keyword_Controller_Index',
                        'elevforeningen'      => 'VIH_Intranet_Controller_Elevforeningen_Index');

    protected $templates;
    protected $twitter;

    function __construct(k_TemplateFactory $templates, Services_Twitter $twitter)
    {
        $this->templates = $templates;
        $this->twitter = $twitter;
    }

    function map($name)
    {
        return $this->map[$name];
    }

    function dispatch()
    {
        if ($this->identity()->anonymous()) {
            return new k_NotAuthorized();
        }
        return parent::dispatch();
    }

    function renderHtml()
    {
        $special_data = array('special_days' => VIH_Model_Ansat::getBirthdays());

        $this->document->setTitle('Forside: Velkommen');
        $this->document->addOption('Protokol', $this->url('protokol'));
        $this->document->addOption('Tjek din e-mail', 'https://mail.vih.dk/exchange/');
        $this->document->addOption('Højskolens kalender', 'http://www.google.com/calendar/embed?src=scv5aba9r3r5qcs1m6uddskjic%40group.calendar.google.com');

        $special_day_tpl = $this->templates->create('special_day');
        $content = $special_day_tpl->render($this, $special_data);

        $tpl = $this->templates->create('index');
        return $tpl->render($this) . $content;
    }

    function postForm()
    {
        try {
            $this->twitter->statuses->update($this->body('twitter'));
            return new k_SeeOther($this->url());
        } catch (Exception $e) {
            throw $e;
        }
        return $this->render();
    }
}