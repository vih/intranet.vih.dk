<ul>
	<li><a href="<?php e(url('./', array('format' => 'excel'))); ?>">Excel</a></li>
</ul>

<?php if (is_array($deltagere)): ?>
    <table id="deltagere">
    <caption>Deltagere<caption>
    <tr>
        <?php if (!empty($vis_tilmelding) AND $vis_tilmelding == 'ja'): ?>
        <th>Tilmelding</th>
        <?php endif; ?>
        <th>Navn</th>
        <th>CPR-nummer</th>
        <?php if (!empty($indkvartering) AND $indkvartering == 'kursuscenteret' OR $indkvartering == "højskole og kursuscenter"):?>
            <th>Eneværelse</th>
            <th>Sambo</th>
        <?php endif; ?>
        <?php if(in_array('golf', $type)): ?>
            <th>Handicap</th>
            <th>Klub</th>
            <th>DGU</th>
        <?php endif; ?>
        <?php if(in_array('bridge', $type)): ?>
            <th>Bridgeniveau</th>
        <?php endif; ?>
        <?php if(in_array('familie', $type)): ?>
            <th>Alder</th>
        <?php endif; ?>
        <?php if (!empty($vis_slet) AND $vis_slet == 'ja'): ?>
            <th></th>
        <?php endif; ?>
    </tr>

    <?php foreach($deltagere AS $deltager): ?>
        <tr>
            <?php if (!empty($vis_tilmelding) AND $vis_tilmelding == 'ja'): ?>
            <td><a href="<?php echo url('/kortekurser/tilmeldinger/' . $deltager->tilmelding->get('id')); ?>"><?php echo $deltager->tilmelding->get('id'); ?></a></td>
            <?php endif; ?>
            <td><?php echo $deltager->get('navn'); ?></td>
            <td><?php echo $deltager->get("cpr"); ?></td>
            <?php if (!empty($indkvartering) AND $indkvartering == "kursuscenteret" OR $indkvartering == "højskole og kursuscenter"):?>
                <td><?php echo ucfirst($deltager->get("enevaerelse")); ?></td>
                <td><?php if ($deltager->get("sambo")) echo $deltager->get("sambo"); else echo 'Ingen valgt';?></td>
            <?php endif; ?>
            <?php if(in_array('golf', $type)): ?>
                <td><?php echo $deltager->get('handicap'); ?></td>
                <td><?php echo $deltager->get('klub'); ?></td>
                <td><?php echo $deltager->get('dgu'); ?></td>
            <?php endif; ?>
            <?php if(in_array('bridge', $type)): ?>
                <td><?php echo $deltager->get('niveau'); ?></td>
            <?php endif; ?>
            <?php if(in_array('familie', $type)): ?>
                <td><?php echo $deltager->get('alder'); ?></td>
            <?php endif; ?>
            <?php if (!empty($vis_slet) AND $vis_slet == 'ja'): ?>
                <td><a href="<?php echo url(null, array('sletdeltager' => $deltager->get('id'))); ?>" onclick="return confirm('Er du sikker på, at du vil slette deltageren?');">Slet</a></td>
            <?php endif; ?>

        </tr>
        <?php endforeach;	?>
</table>

<?php endif; ?>