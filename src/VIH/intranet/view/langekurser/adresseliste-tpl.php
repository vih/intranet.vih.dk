<?php if (is_array($elever)): ?>

	<table>
	<tr>
		<th>Navn</th>
		<th>Adresse</th>
		<th>Postnr</th>
		<th>Postby</th>
		<th>Telefon</th>
		<th>E-mail</th>
		<th>Alder</th>
	</tr>

	<?php foreach($elever AS $elev): ?>
		<tr>
			<td><?php echo $elev->get('navn'); ?></td>
			<td><?php echo $elev->get('adresse'); ?></td>
			<td><?php echo $elev->get('postnr'); ?></td>
			<td><?php echo $elev->get('postby'); ?></td>
			<td><?php echo $elev->get('telefon'); ?></td>
			<td><?php echo $elev->get('email'); ?></td>															
			<td><?php echo $elev->get('age'); ?></td>
		</tr>
		
	<?php endforeach; ?>
	
	</table>
	
<?php endif; ?>