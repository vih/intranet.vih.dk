<p><img src="/gfx/logo/vih_logo.jpg" width="200" alt="" /></p>
        <table cellspacing="0" cellpadding="5">
            <caption>Drikkevarer til <?php echo $kursus->get("kursusnavn"); ?></caption>
            <tr>
                <th>Navn</th>
                <th>Øl 16 kr</th>
                <th>Fadøl 20 kr</th>
                <th>Rødvin 75 kr</th>
                <th>Hvidvin 75 kr</th>
                <th>Sodavand 16 kr</th>
                <th>Andet</th>
            </tr>
            <?php
            foreach ($deltagere AS $key=>$deltager) {
                echo '<tr><td>' . $deltager->get("navn") .'</td>';
                echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
                echo '</tr>';
                    //  $deltagerantal = $deltagerantal + 1;
            }
    ?>
    </table>