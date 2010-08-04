<?php if (is_array($elever)): ?>
    <table>
    <?php foreach ($elever AS $elev): ?>
        <tr>
            <td><?php e($elev->get('vaerelse')); ?></td>
            <td><a href="<?php e(url($elev->get('id'))); ?>"><?php e($elev->get('navn')); ?></a></td>
            <td><?php e($elev->get('telefon')); ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>