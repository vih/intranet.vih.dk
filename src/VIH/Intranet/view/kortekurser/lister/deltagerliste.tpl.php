<table>
    <caption>Deltagerliste: <?php e($kursus->get("kursusnavn")); ?></caption>
    <tr>
        <th>Navn</th>
        <th>Bopæl</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach ($deltagere as $deltager) { ?>
        <tr>
            <td><?php e($deltager->get("navn")); ?></td>
            <td><?php e($deltager->tilmelding->get("adresse")); ?></td>
            <td><?php e($deltager->tilmelding->get("postnr") . " " . $deltager->tilmelding->get("postby")); ?></td>
            <?php
                switch ($deltager->tilmelding->kursus->get('gruppe_id')) {
                    case 1: // golf
                        echo '<td>' . $deltager->get("handicap") . '</td>';
                        echo '<td>' . $deltager->get("klub") . '</td>';
                        break;
                }
            ?>
        </tr>
    <?php } ?>
</table>
