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

    function __construct(k_TemplateFactory $templates)
    {
        $this->templates = $templates;
    }

    function map($name)
    {
        return $this->map[$name];
    }
    /*
    function dispatch()
    {
        if ($this->identity()->anonymous()) {
            return new k_NotAuthorized();
        }
        return parent::dispatch();
    }
	*/

    function renderHtml()
    {
        $hilsener = array(
            'Stræk armene op over hovedet og råb jubii.',
            'Kør hånden gennem håret og sig: Gud, hvor har jeg lækkert hår.',
            'Dupont er ikke ret stor, men han er stadig meget flink.',
            'Det var dog en usædvanlig dejlig dag i dag.',
            'Sæt dig da ned et minuts tid og nyd livet. Det er skønt.',
            'Når du kan høre fuglene fløjte, må det være en vidunderlig dag.',
            'Stræk den ene arm over på ryggen og klap dig selv på skulderen.',
            'Skynd dig over på kontoret - de har slik og gaver til dig i dag.',
            'Faktisk er vi nok alt for seje.',
            'Det går den rigtige vej.',
            'Det er ikke så ringe endda.',
            'Mon køkkenet serverer hindbærsnitter i dag?',
            'Har du rost en anden i dag',
            'VIH er landets bedste idrætshøjskole',
            'Der er ingen grænser for, hvad vi kan opnå.'
        );

        $special_data = array('special_days' => VIH_Model_Ansat::getBirthdays());

        $this->document->setTitle('Forside: Velkommen');
        //$this->document->help = $hilsener[array_rand($hilsener)];

        $special_day_tpl = $this->templates->create('special_day');
        return $special_day_tpl->render($this, $special_data) . '<ul class="navigation-frontpage">
                <li><a href="'.$this->url('/protokol').'">Protokol</a></li>
                <li><a href="https://mail.vih.dk/exchange/">Tjek din e-mail</a></li>
                <li><a href="http://www.google.com/calendar/embed?src=scv5aba9r3r5qcs1m6uddskjic%40group.calendar.google.com">Højskolens kalender</a></li>
            </ul>
            ' . sprintf("<form method='post' action='%s'><p><input type='submit' value='Log out' /></p></form>", htmlspecialchars($this->url('/logout')));
    }
}