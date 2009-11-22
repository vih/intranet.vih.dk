<?php if (is_array($tilmeldinger)): ?>
    <form action="<?php e(url('./')); ?>" method="post">
    <table>
    <?php foreach ($tilmeldinger AS $tilmelding): ?>
    <tr>
        <td><input type="text" value="<?php e($tilmelding->get('hold')); ?>" size="3" name="hold[<?php e($tilmelding->get('hold_id')); ?>]" /></td>
        <td><a href="<?php e(url('/langekurser/tilmeldinger/' . $tilmelding->get('id') . '/fag')); ?>"><?php e($tilmelding->get('navn')); ?></a></td>
    </tr>
    <?php endforeach; ?>
    </table>
    <p><input type="submit" value="Gem" /></p>
    </form>

<?php endif; ?>