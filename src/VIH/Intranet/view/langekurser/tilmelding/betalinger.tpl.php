<?php
if($tilmelding->antalRater() > 0) {

	$rater_samlet = $tilmelding->kursus->get("depositum"); // vi lægger depositummet på samtælling fra starten.
	
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
					print("Betalt");
				}
				elseif($tilmelding->get("date_created") < date("Y-m-d", time() - (60 * 60 * 24 * 14))) { // 14 dage
					print("<p class='red'>Forfalden</p>");
				}
				?>
			</td>	
			<td align="right"><?php echo $tilmelding->kursus->get("depositum"); ?></td>
		</tr>
		
		<?php
		$rater = $tilmelding->getRater();
		$rater_samlet = $tilmelding->kursus->get('depositum');
		for($i = 0, $max = count($rater); $i < $max; $i++) {
			$rater_samlet += $rater[$i]["beloeb"];
			?>
			<tr>
				<td><?php print($i +1); ?></td>
				<td><?php print($rater[$i]["dk_betalingsdato"]); ?></td>
				<td>
					<?php
					if($betalt >= $rater_samlet) {
						print("Betalt");
					}
					elseif($rater[$i]["betalingsdato"] < date("Y-m-d")) {
						print("<span class='red'>Forfalden</span>");
					}
					?>
				</td>
				<td align="right"><?php print($rater[$i]["beloeb"]); ?></td>
			</tr>
			<?php
		}
		?>
		
		<tr>
			<td>&nbsp;</td>
			<td colspan="2"><strong>I alt rater</strong></td>
			<td align="right"><?php print($rater_samlet); ?></td>
		</tr>
		<?php
		$rate_difference = $tilmelding->rateDifference();
		if($rate_difference != 0) {
			?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2"><strong class="red">Differende i forhold til Total</strong></td>
				<td align="right"><?php print($rate_difference); ?></td>
			</tr>
			<?php
		}
		?>
		
	</table>
	<?php
}
?>	
