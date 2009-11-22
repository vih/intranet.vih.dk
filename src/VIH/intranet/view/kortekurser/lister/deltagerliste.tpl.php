        <table>
            <caption>Deltagerliste: <?php echo $kursus->get("kursusnavn"); ?></caption>
            <tr>
                <th>Navn</th>
                <th>Bopæl</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <?php
            foreach ($deltagere AS $key=>$deltager) {
                echo '<tr>';
                echo '<td>' . $deltager->get("navn") . '</td>';
                echo '<td>' . $deltager->tilmelding->get("adresse") . '</td>';
                echo '<td>' . $deltager->tilmelding->get("postnr") . " " . $deltager->tilmelding->get("postby") . '</td>';
                switch ($deltager->tilmelding->kursus->get('gruppe_id')) {
                        case 1: // golf
                            echo '<td>' . $deltager->get("handicap") . '</td>';
                            echo '<td>' . $deltager->get("klub") . '</td>';
                            break;
                }
                echo '</tr>';
            }
            ?>
        </table>