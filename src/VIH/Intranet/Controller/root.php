<?php
class VIH_Intranet_Controller_Root extends k_Dispatcher
{
    public $map = array('admin'               => 'VIH_Intranet_Controller_Index',
                        'login'               => 'VIH_Intranet_Controller_Login',
                        'logout'              => 'VIH_Intranet_Controller_Logout',
                        'langekurser'         => 'VIH_Intranet_Controller_LangeKurser_Index',
                        'kortekurser'         => 'VIH_Intranet_Controller_KorteKurser_Index',
                        'faciliteter'         => 'VIH_Intranet_Controller_Faciliteter_Index',
                        'materialebestilling' => 'VIH_Intranet_Controller_Materialebestilling_Index',
                        'ansatte'             => 'VIH_Intranet_Controller_Ansatte_Index',
                        'fag'                 => 'VIH_Intranet_Controller_Fag_Index',
                        'betaling'            => 'VIH_Intranet_Controller_Betaling_Index',
                        'nyheder'             => 'VIH_Intranet_Controller_Nyheder_Index',
                        'protokol'            => 'VIH_Intranet_Controller_Protokol_Index',
                        //'holdliste'           => 'VIH_Intranet_Controller_LangeKurser_Holdlister_Index',
                        'fotogalleri'         => 'VIH_Intranet_Controller_Fotogalleri_Index',
                        //'calendar'            => 'VIH_Intranet_Controller_Calendar_Index',
                        'filemanager'         => 'Intraface_Filehandler_Controller_Index',
                        'file'                => 'Intraface_Filehandler_Controller_Viewer',
                        'keyword'             => 'Intraface_Keyword_Controller_Index',
                        'elevforeningen'      => 'VIH_Intranet_Controller_Elevforeningen_Index');
    public $debug = true;

    function __construct()
    {
        parent::__construct();
        $this->document->template = dirname(__FILE__) . '/../view/main-tpl.php';
        $this->document->title = 'Vejle Idrætshøjskoles Intranet';
        $this->document->options = array();
        $this->document->navigation = array(
            $this->url('/nyheder') => 'Nyheder',
            $this->url('/langekurser/tilmeldinger')  => 'Lange kurser',
            $this->url('/kortekurser/tilmeldinger')  => 'Korte kurser',
            $this->url('/betaling') => 'Betalinger',
            $this->url('/materialebestilling')  => 'Brochurebestilling',
            $this->url('/ansatte')  => 'Ansatte',
            $this->url('/faciliteter')  => 'Faciliteter',
            $this->url('/filemanager') => 'Dokumenter',
            $this->url('/fotogalleri')  => 'Højdepunkter',
            $this->url('/logout')  => 'Logout');
    }

    function loadUser($username, $password)
    {
        $liveuser = $this->registry->get('liveuser');
        return $liveuser;
    }

    function forward($name)
    {
        if ($name == 'login') {
            return parent::forward('login');
        }

        $session = $this->SESSION->get('vih');
        if (empty($session['logged_in']) OR $session['logged_in'] != 'true') {
            throw new k_http_Redirect($this->url('/login'));
        }
        return parent::forward($name);
    }

    function execute()
    {
        return $this->forward('admin');
    }
}