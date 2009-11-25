<?php if (is_array($elever)): ?>

    <table>

    <?php foreach ($elever AS $elev): ?>
        <tr>
            <td><?php echo $elev->get('vaerelse'); ?></td>
            <td><a href="<?php echo url($elev->get('id')); ?>"><?php echo $elev->get('navn'); ?></a></td>
            <td><?php echo $elev->get('telefon'); ?></td>
        </tr>
    <?php endforeach; ?>

    </table>

<?php endif; ?>