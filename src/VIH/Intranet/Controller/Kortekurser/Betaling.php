<?php

// indeholder alle tilmeldinger - sorteres på kursus
/*
include('../include_intranet.php');


$kernel = new Kernel;
$page = new Page($kernel);
$module = $kernel->module("kortekurser");
$module->includeFile("KortKursus.php");

$page->start('Betalingsoversigt');

$tilmelding = new VIH_Model_KortKursus_Tilmelding($_REQUEST['id']);

if (isset($_GET['delete']) AND is_numeric($_GET['delete'])) {
	$betaling = new VIH_Model_Betaling($tilmelding, (int)$_GET['delete']);
	$betaling->delete();
}

$betal = new VIH_Model_Betaling($tilmelding);

if (isset($_POST['submit'])) {
	$betal->save("Indtastet beløb (".$kernel->user->get('id').")", $beloeb, "manuel");
}

$betaling = $betal->getList();
?>

<h1>Betalinger</h1>

<div style="width: 45%; float: left; margin-right: 3%;">

<address>
	<div>
	Ordre <a href="edit_tilmelding.php?tilmelding_id=<?php echo $tilmelding->get("id"); ?>">#<?php echo $tilmelding->get("id"); ?></a>
	<br />Kursus: <?php echo $tilmelding->kursus->get("helt_navn"); ?>
	<br />Ordredato: <?php echo $tilmelding->get("dato"); ?>
	<br /><?php echo $tilmelding->get("navn"); ?>
	<br /><?php echo $tilmelding->get("adresse"); ?>
	<br /><?php echo $tilmelding->get("postnr"); ?> <?php echo $tilmelding->get("postby"); ?>
	<br /><?php echo $tilmelding->get("telefon"); ?> / <?php echo $tilmelding->get("arbejdstelefon"); ?>
	</div>
</address>

<ul class="button">
<?php

if ($tilmelding->getSkyldig() > 0 AND $tilmelding->getForfalden() OR $tilmelding->kursus->get('id') == 62 OR $tilmelding->kursus->get('id') == 63 OR $tilmelding->kursus->get('id') == 64) {
	echo '<li><a href="rykker.php?id='.$tilmelding->get('id').'" onclick="confirm(\'Er du sikker på, at du vil sende en rykker?\')">send rykker</a></li>';
}
if ($tilmelding->getSkyldig() <= 0 OR $tilmelding->kursus->get('id') == 62 OR $tilmelding->kursus->get('id') == 63 OR $tilmelding->kursus->get('id') == 64) {
	echo '<li><a href="bekraeftelse.php?id='.$tilmelding->get('id').'" onclick="confirm(\'Er du sikker på, at du vil sende en bekræftelse?\')">send bekræftelse</a></li>';
}

if ($tilmelding->getSkyldig("depositum") > 0 AND $tilmelding->getForfalden("depositum")  OR $tilmelding->kursus->get('id') == 62 OR $tilmelding->kursus->get('id') == 63 OR $tilmelding->kursus->get('id') == 64) {
		echo '<li><a href="depositumrykker.php?id='.$tilmelding->get('id').'" onclick="confirm(\'Er du sikker på, at du vil sende en rykker for depositum?\')">send rykker for depositum</a></li>';
}
if ($tilmelding->getSkyldig("depositum") <= 0  OR $tilmelding->kursus->get('id') == 62 OR $tilmelding->kursus->get('id') == 63 OR $tilmelding->kursus->get('id') == 64) {
		echo '<li><a href="depositumbekraeftelse.php?id='.$tilmelding->get('id').'" onclick="confirm(\'Er du sikker på, at du vil sende en bekræftelse for depositum?\')">send bekræftelse for depositum</a></li>';
}

?>
</ul>

<br />

<?php
$pris = $tilmelding->getPris();
?>

<table>
<tr>
	<th>Kursuspris</th>
	<th>Afbestilling</th>
	<th>Total</th>
	<th>Har betalt</th>
	<th>Restance</th>
</tr>
<tr>
	<td><?php echo $pris['kursuspris']; ?></td>
	<td><?php echo $pris['forsikring']; ?></td>
	<td><?php echo $pris['total']; ?></td>
	<td><?php echo $betalt = $tilmelding->getBetalt(); ?></td>
	<td><?php echo $tilmelding->getSkyldig(); ?></td>
</tr>
</table>

<br />

<form action="betal.php" method="post">
	<input type="hidden" name="id" value="<?php echo $tilmelding->get("id"); ?>" />
	<fieldset>
		<div>
			<label for="beloeb">Beløb</label>
			<input type="text" id="beloeb" name="beloeb" />
			<input type="submit" name="submit" value="Betalt" />
		</div>
	</fieldset>
</form>

</div>

<div style="float:left; width:45%;">

<?php if (count($betaling)) { ?>
<h2>Betalingshistorik</h2>
<table>
	<tr>
		<th>Dato</th>
		<th>Tekst</th>
		<th>Beløb</th>
		<th></th>
	</tr>
	<?php  foreach ($betaling AS $b) {?>
	<tr>
		<td><?php echo $b['dato']; ?></td>
		<td><?php echo $b['tekst']; ?>
						<?php if ($b['type'] == 'dankort') { ?>
    <img src="/images/dankortlogo.jpg" alt="Dankort" />
<?php } ?>


		</td>
		<td><?php echo $b['beloeb']; ?>
		</td>
		<?php if ($b['type'] != 'automatisk') { ?>
		<td><a href="betal.php?delete=<?php echo $b['id']; ?>&amp;id=<?php echo $tilmelding->get('id'); ?>" onclick="return confirm('Er du sikker på, at du vil slette?');">slet</a></td>
		<?php } else { ?>
    <td></td>
   	<?php  } ?>
	</tr>
	<?php  } ?>
</table>
<?php } ?>
</div>
<div style="clear: both;"></div>
<?php
$page->end();
*/
?>