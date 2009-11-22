<?php if (is_array($faggrupper)): ?>

    <table>

        <?php foreach($faggrupper AS $faggruppe): ?>
            <tr>
                <td><?php echo $faggruppe->get('navn'); ?></td>
                <td><a href="<?php echo url($faggruppe->get('id') . '/edit'); ?>">ret</a></td>
            </tr>

        <?php endforeach; ?>
    </table>

<?php endif; ?>