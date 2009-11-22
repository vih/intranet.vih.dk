<?php if (is_array($list)): ?>

    <table>

    <?php foreach($list AS $ansat): ?>
        <tr>
            <td><a href="<?php echo url($ansat->get('id')); ?>"><?php echo $ansat->get('navn'); ?></a></td>
            <td><?php echo $ansat->get('telefon'); ?></td>
            <td><?php echo $ansat->get('mobil'); ?></td>
            <td><?php echo $ansat->get('email'); ?></td>
            <td><a href="<?php echo url($ansat->get('id') . '/edit'); ?>">Ret</a></td>
            <td><a href="<?php echo url($ansat->get('id') . '/delete'); ?>" onclick="return confirm('Er du sikker?');">Slet</a></td>
        </tr>
    <?php endforeach; ?>

    </table>
<?php endif; ?>