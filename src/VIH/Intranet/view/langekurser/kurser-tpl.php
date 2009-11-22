<?php $tjekaar = ''; if (is_array($kurser)): ?>

    <table>
    <caption><?php echo $caption; ?></caption>

    <tr>

        <th>Kursusnavn</th>
        <th>Starter</th>
        <th>Slutter</th>
        <th>Tilmeldinger</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>

    <?php foreach($kurser AS $kursus): ?>

        <?php
        if ($kursus->getYear() != $tjekaar):
            echo '<tr><td colspan="9" class="headline">'.$kursus->getYear().'</td></tr>';
            $tjekaar = $kursus->getYear();
        endif;
        ?>

        <tr>
            <td><a href="<?php e(url($kursus->get('id'))); ?>"><?php e($kursus->getKursusNavn()); ?></a></td>
            <td><?php e($kursus->getDateStart()->format('%d-%m-%Y'));  ?></td>
            <td><?php e($kursus->getDateEnd()->format('%d-%m-%Y'));  ?></td>
            <td>
                <?php
                    e($kursus->getAntalTilmeldinger());
                ?>
            </td>
            <td><a href="<?php e(url($kursus->get('id') . '/edit')); ?>">ret</a></td>
            <!--<td><a href="<?php e(url($kursus->get('id') . '/delete')); ?>" onclick="return confirm('Er du sikker?');">slet</a></td>-->
            <td><a href="<?php e(url($kursus->get('id') . '/tilmeldinger')); ?>">tilmeldinger</a></td>
            <!--<td><a href="<?php echo url($kursus->get('id'), array('output'=>'pdf')); ?>" class="pdf">Pdf</a></td>
            <td><a href="<?php e(url($kursus->get('id') . '/rater')); ?>">Rater</a></td>-->
        </tr>

    <?php endforeach; ?>

    </table>

<?php endif; ?>