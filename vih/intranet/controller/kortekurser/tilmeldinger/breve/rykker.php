<?php
// oplysningert til indsættelse i teksten

$navn = $tilmelding->get('navn');
$ordrenummer = $tilmelding->get("id");
$kursus = $tilmelding->kursus->get("kursusnavn");
$tilmelding->loadBetaling();
$pris = intval($tilmelding->get('pris'));
$skyldig =  intval($tilmelding->get('skyldig'));
$afsendernavn = "";
$onlinebetaling = 'eller online på adressen '.KORTEKURSER_LOGIN_URI.$tilmelding->get('code');

$brev_tekst = <<<EODTEKST
Kære $navn

Ved gennemgang af vort bogholderi har vi konstateret, at du endnu mangler at indbetale $skyldig kroner for $kursus.

Dit ordrenummer er: $ordrenummer

Pengene bedes overført til vores konto i Jyske Bank: 7244-1469664 $onlinebetaling.

Såfremt beløbet er betalt, må du meget gerne ringe til os og oplyse, hvilken dato pengene er blevet indbetalt.

Hvis vi ikke hører fra dig, går vi ud fra at du ikke ønsker at opretholde din tilmelding.

Med venlig hilsen

$afsendernavn
Vejle Idrætshøjskole
Telefon: 75820811

EODTEKST;

?>