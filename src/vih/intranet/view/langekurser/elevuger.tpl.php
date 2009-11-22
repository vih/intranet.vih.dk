<h1>Elevuger <?php e($kursus->getKursusNavn()); ?></h1>

<table>
<?php foreach ($tilmeldinger as $tilmelding): ?>
    <tr>
        <td><?php e($tilmelding->get('navn')); ?></td>
        <td><?php e($tilmelding->getWeeks()); ?></td>
    </tr>
<?php endforeach; ?>
</table>