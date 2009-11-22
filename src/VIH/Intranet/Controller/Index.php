<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Index extends k_Controller
{
    function GET()
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

        $this->document->title = 'Forside: Velkommen';
        $this->document->help = $hilsener[array_rand($hilsener)];

        return $this->render('vih/intranet/view/special_day-tpl.php', $special_data). '
            <ul class="navigation-frontpage">
                <li><a href="'.$this->url('/protokol').'">Protokol</a></li>
                <li><a href="https://mail.vih.dk/exchange/">Tjek din e-mail</a></li>
                <li><a href="http://www.google.com/calendar/embed?src=scv5aba9r3r5qcs1m6uddskjic%40group.calendar.google.com">Højskolens kalender</a></li>
            </ul>
            ';
    }
}