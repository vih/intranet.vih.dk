<ul>
	<li><a href="<?php e(url(null . '.xls')); ?>">Excel</a></li>
</ul>

<?php if (is_array($tilmeldinger)): ?>
<table>
    <caption><?php echo $caption; ?></caption>
    <tr>
        <th>#</th>
        <th>Dato</th>
        <th>Navn</th>
        <th>Kursus</th>
        <th>Pris</th>
        <th>Betalt</th>
        <th>Skyldig</th>
        <?php if (!empty($vis_besked) AND $vis_besked=='ja'): ?>
        <th>Besked</th>
        <?php endif; ?>
    </tr>
    <?php foreach ($tilmeldinger AS $tilmelding): ?>
        <?php if ($tilmelding->get('forfalden') > 0): ?>
            <tr class="forfalden">
        <?php else: ?>
            <tr>
        <?php endif; ?>
        <td><a href="<?php echo url($tilmelding->get('id')); ?>"><?php echo $tilmelding->get('id'); ?></a></td>
        <td><?php echo $tilmelding->get('date_created_dk'); ?></td>
        <td>
            <?php if ($tilmelding->get('active') == 1) echo $tilmelding->get('navn'); else echo '[SLETTET]'; ?>
        </td>
        <td><?php echo $tilmelding->kursus->get('kursusnavn'); ?></td>
        <td><?php echo $tilmelding->get('pris_total'); ?></td>
        <td><?php echo (int) $tilmelding->get('betalt'); ?></td>
        <td><?php echo (int) $tilmelding->get('saldo'); ?></td>
        <?php if (!empty($vis_besked) AND $vis_besked=='ja'): ?>
        <td><?php echo $tilmelding->get('besked'); ?></td>
        <?php endif; ?>
    </tr>

    <?php endforeach; ?>

</table>
<?php endif; ?>
