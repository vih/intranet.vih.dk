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

    function dispatch()
    {
        if ($this->identity()->anonymous()) {
            return new k_NotAuthorized();
        }
        return parent::dispatch();
    }

    function renderHtml()
    {
        $hilsener = array(
            'Str�k armene op over hovedet og r�b jubii.',
            'K�r h�nden gennem h�ret og sig: Gud, hvor har jeg l�kkert h�r.',
            'Dupont er ikke ret stor, men han er stadig meget flink.',
            'Det var dog en us�dvanlig dejlig dag i dag.',
            'S�t dig da ned et minuts tid og nyd livet. Det er sk�nt.',
            'N�r du kan h�re fuglene fl�jte, m� det v�re en vidunderlig dag.',
            'Str�k den ene arm over p� ryggen og klap dig selv p� skulderen.',
            'Skynd dig over p� kontoret - de har slik og gaver til dig i dag.',
            'Faktisk er vi nok alt for seje.',
            'Det g�r den rigtige vej.',
            'Det er ikke s� ringe endda.',
            'Mon k�kkenet serverer hindb�rsnitter i dag?',
            'Har du rost en anden i dag',
            'VIH er landets bedste idr�tsh�jskole',
            'Der er ingen gr�nser for, hvad vi kan opn�.'
        );

        $special_data = array('special_days' => VIH_Model_Ansat::getBirthdays());

        $this->document->setTitle('Forside: Velkommen');
        //$this->document->help = $hilsener[array_rand($hilsener)];

        $special_day_tpl = $this->templates->create('special_day');
        return $special_day_tpl->render($this, $special_data) . '<ul class="navigation-frontpage">
                <li><a href="'.$this->url('/protokol').'">Protokol</a></li>
                <li><a href="https://mail.vih.dk/exchange/">Tjek din e-mail</a></li>
                <li><a href="http://www.google.com/calendar/embed?src=scv5aba9r3r5qcs1m6uddskjic%40group.calendar.google.com">H�jskolens kalender</a></li>
            </ul>
            ' . sprintf("<form method='post' action='%s'><p><input type='submit' value='Log out' /></p></form>", htmlspecialchars($this->url('/logout')));
    }
}