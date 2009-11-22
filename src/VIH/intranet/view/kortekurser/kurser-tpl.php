<?php if (is_array($kurser)): $new_year = ''; ?>
<table>
    <caption><?php echo $caption; ?></caption>
    <tr>
        <th>Uge</th>
        <th>Kursusnavn</th>
        <th>Pladser</th>
        <th>Ledige pladser</th>
        <th>Ift. mål</th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach ($kurser AS $kursus): ?>
    <?php
        if (empty($kursus) OR !is_object($kursus)) {
            continue;
        }
        if ($kursus->get('aar') != $new_year):
            echo '<tr><td colspan="10" class="headline">' . $kursus->get('aar') . '</td></tr>';
            $new_year = $kursus->get('aar');
        endif;
    ?>

    <tr>
        <td><?php e($kursus->get('uge')); ?></td>
        <td><a href="<?php e(url($kursus->get('id'))); ?>"><?php e($kursus->get('navn')); ?></a></td>
        <td><?php e($kursus->get('pladser')); ?></td>
        <td><?php e($kursus->get('pladser_ledige')); ?></td>
        <td><?php e($kursus->get('pladser_procent_fyldt')); ?>%</td>
        <td><a href="<?php e(url($kursus->get('id') . '/edit')); ?>">rediger</a></td>
        <td><a href="<?php e(url($kursus->get('id') . '/tilmeldinger')); ?>">tilmeldinger</a></td>
        <td><a href="<?php e(url($kursus->get('id') . '/deltagere')); ?>">deltagere</a></td>
        <td id="kursus<?php e($kursus->get('id')); ?>">
            <?php if ($kursus->get('gruppe_id') == 1): ?>
            <?php     e($kursus->getBegyndere()); ?>
            <?php endif; ?>
        </td>
        <td>
            <?php if (count($kursus->venteliste->getList()) > 0): ?>
            <a href="<?php e(url($kursus->get('id') . '/venteliste')); ?>">Venteliste</a>
            <?php endif; ?>
        </td>
    </tr>

    <?php endforeach; ?>
</table>

<?php endif; ?>
