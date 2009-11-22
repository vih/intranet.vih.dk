<?php 

// oplysningert til indsættelse i teksten


$navn = $tilmelding->get('navn');
$ordrenummer = $tilmelding->get("id");
$kursus = $tilmelding->kursus->get("kursusnavn");
$afsendernavn = "";
$tilmelding->loadBetaling();
$deltagere = count($tilmelding->getDeltagere());
if ($deltagere == 1) {
	$stedord = 'du';
}
else {
$stedord = '' . $deltagere . ' deltagere ';
}
$betalt = intval($tilmelding->get('betalt'));
$forsikring = '';
if ($tilmelding->get('afbestillingsforsikring') == 'Ja') {
	$forsikring = ' afbestillingsforsikring og ';
}


$brev_tekst = <<<EODTEKST
Kære $navn

Tak for tilmeldingen til $kursus. Vi har noteret, at der er indbetalt $betalt kroner, som dækker$forsikring kursusprisen.

Dit tilmeldingsnummer er: $ordrenummer

Vi kan bekræfte, at $stedord er optaget på kurset.

Vi sender programmet ud cirka to uger før kurset starter.

På gensyn i Vejle.

Med venlig hilsen


$afsendernavn
Vejle Idrætshøjskole
Telefon: 75820811
EODTEKST;

?>