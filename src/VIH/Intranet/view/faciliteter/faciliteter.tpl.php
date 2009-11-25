<?php if (is_array($faciliteter)): ?>

    <table>
        <caption>Faciliteter</caption>
        <?php foreach($faciliteter AS $facilitet): ?>
            <tr>
                <td><a href="<?php echo url($facilitet->get('id')); ?>"><?php echo $facilitet->get('navn'); ?></a></td>
                <td><a href="<?php echo url($facilitet->get('id') . '/edit'); ?>">ret</a></td>
                <td><a href="<?php echo url($facilitet->get('id') . '/delete'); ?>" onclick="return confirm('Er du sikker?');">slet</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>