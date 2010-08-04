<?php
if($tilmelding->antalRater() > 0) {

	$rater_samlet = $tilmelding->kursus->get("depositum"); // vi l�gger depositummet p� samt�lling fra starten.

	?>
	<br />
	<table id="historik">
		<caption>Betalingsrater</caption>

		<tr>
			<th>Nr.</th>
			<th>Betalingsdato</th>
			<th>Status</th>
			<th>Beløb</th>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Tilmeldingsgebyr</td>
			<td>
				<?php
				if($betalt >= $rater_samlet) {
					e("Betalt");
				}
				elseif($tilmelding->get("date_created") < date("Y-m-d", time() - (60 * 60 * 24 * 14))) { // 14 dage
					print("<p class='red'>Forfalden</p>");
				}
				?>
			</td>
			<td align="right"><?php e($tilmelding->kursus->get("depositum")); ?></td>
		</tr>

		<?php
		$rater = $tilmelding->getRater();
		$rater_samlet = $tilmelding->kursus->get('depositum');
		for($i = 0, $max = count($rater); $i < $max; $i++) {
			$rater_samlet += $rater[$i]["beloeb"];
			?>
			<tr>
				<td><?php e($i +1); ?></td>
				<td><?php e($rater[$i]["dk_betalingsdato"]); ?></td>
				<td>
					<?php
					if($betalt >= $rater_samlet) {
						e("Betalt");
					} elseif($rater[$i]["betalingsdato"] < date("Y-m-d")) {
						print("<span class='red'>Forfalden</span>");
					}
					?>
				</td>
				<td align="right"><?php e($rater[$i]["beloeb"]); ?></td>
			</tr>
			<?php
		}
		?>

		<tr>
			<td>&nbsp;</td>
			<td colspan="2"><strong>I alt rater</strong></td>
			<td align="right"><?php e($rater_samlet); ?></td>
		</tr>
		<?php
		$rate_difference = $tilmelding->rateDifference();
		if($rate_difference != 0) {
			?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2"><strong class="red">Difference ift. total</strong></td>
				<td align="right"><?php e($rate_difference); ?></td>
			</tr>
			<?php
		}
		?>

	</table>
	<?php
}
?>
