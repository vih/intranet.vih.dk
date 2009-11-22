
<?php if (is_array($medlemmer)): ?>

	<table>

		<caption></caption>
		
		<?php foreach ($medlemmer AS $medlem): ?>
		
		<tr>
		
			<td><a href="medlem.php?id=<?php echo $medlem->get('id'); ?>"><?php echo $medlem->adresse->get('navn'); ?></a></td>
			<td><a href="medlem_edit.php?id=<?php echo $medlem->get('id'); ?>">Ret</a></td>
			<td><a href="medlem.php?delete=<?php echo $medlem->get('id'); ?>">Slet</a></td>						
		
		</tr>
		
		<?php endforeach; ?>
	
	</table>
	
<?php endif; ?>