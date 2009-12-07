
		
		<table id="prisoversigt">
			<caption>Priser</caption>
			<tr>
				<td>Uger</td>
				<td align="right"><?php print($kursus->get("ugeantal")); ?></td>
			</tr>
			<tr>
				<td>Ugepris</td>
				<td align="right"><?php print($kursus->get("pris_uge")); ?></td>
			</tr>		
			<tr>
				<td>Materialepris</td>
				<td align="right"><?php print($kursus->get("pris_materiale")); ?></td>
			</tr>
			<tr>
				<td>Rejsedepositum</td>
				<td align="right"><?php print($kursus->get("pris_rejsedepositum")); ?></td>
			</tr>
			<tr>
				<td>Rejserest</td>
				<td align="right"><?php print($kursus->get("pris_rejserest")); ?></td>
			</tr>

			<?php if ($kursus->get("pris_rejselinje") > 0): ?>
			<tr>
				<td>Rejsepris</td>
				<td align="right"><?php print($kursus->get("pris_rejselinje")); ?></td>
			</tr>
			<?php endif; ?>
			<tr>
				<td>Nøgledepositum</td>
				<td align="right"><?php print($kursus->get("pris_noegledepositum")); ?></td>
			</tr>
			<tr>
				<td>Tilmeldingsgebyr</td>
				<td align="right"><?php print($kursus->get("pris_tilmeldingsgebyr")); ?></td>
			</tr>			
			<tr>
				<td>Samlet pris</td>
				<td align="right"><?php print($kursus->get('pris_total')); ?></td>
			</tr>

		</table>