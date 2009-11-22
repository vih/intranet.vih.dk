<?php

// oplysningert til indsættelse i teksten

$navn = $tilmelding->get('navn');
$ordrenummer = $tilmelding->get("id");
$kursus = $tilmelding->kursus->get("kursusnavn");
$afsendernavn = "";
$tilmelding->loadBetaling();
$betalt = intval($tilmelding->get('betalt'));
$skyldig = intval($tilmelding->get('skyldig'));
$forfaldsdato = $tilmelding->get('dato_forfalden_dk');
$forsikring = "";
if ($tilmelding->get('afbestillingsforsikring') == 'Ja') {
    $forsikring = " afbestillingsforsikring og";
}
$onlinebetaling = 'eller online på adressen ' . KORTEKURSER_LOGIN_URI . $tilmelding->get('code');

$deltagere = count($tilmelding->getDeltagere());
if ($deltagere == 1) {
    $stedord = 'din tilmelding';
}
else {
    $stedord = 'din tilmelding for ' . $deltagere . ' deltagere';
}

$brev_tekst = <<<EODTEKST
Kære $navn

Hermed bekræfter vi, at vi har modtaget din betaling på $betalt kroner. Den dækker$forsikring depositum for kurset $kursus.

Dit ordrenummer er: $ordrenummer

Derved træder $stedord i kraft. Husk at betale det resterende beløb $skyldig kroner senest $forfaldsdato.

Pengene bedes overført til vores konto i Jyske Bank: 7244-1469664 $onlinebetaling.

Med venlig hilsen

$afsendernavn
Vejle Idrætshøjskole
Telefon: 75820811
EODTEKST;

?>