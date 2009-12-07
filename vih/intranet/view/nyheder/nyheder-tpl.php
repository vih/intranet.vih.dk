<?php if (is_array($nyheder)): ?>

    <table>
        <caption>Nyheder</caption>
        <?php foreach($nyheder AS $nyhed): ?>
            <tr>
                <td><?php echo $nyhed->get('date_dk'); ?></td>
                <td><a href="<?php echo url($nyhed->get('id')); ?>"><?php echo $nyhed->get('overskrift'); ?></a></td>
                <td><a href="<?php echo url($nyhed->get('id') . '/edit'); ?>">ret</a></td>
                <td><a href="<?php echo url($nyhed->get('id') . '/delete'); ?>" onclick="return confirm('Er du sikker?');">slet</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>