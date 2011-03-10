<?php if (is_array($elever)): ?>
<form action="<?php e(url()); ?>" method="post">

    <p><label>Dato<input type="text" name="date" value="<?php e($context->getDate()); ?> 8:00" /></label></p>
    <p><label>Tekst<textarea name="text"></textarea></label></p>

    <table>
        <tr>
            <th>Navn</th>
            <?php foreach($context->getTypeKeys() as $key => $value): ?>
            <th><?php e($value); ?></th>
            <?php endforeach; ?>
        </tr>
    <?php foreach ($elever AS $elev): ?>
        <tr>
            <td><?php e($elev->get('navn')); ?></td>
            <?php foreach($context->getTypeKeys() as $key => $value): ?>
            <td><label><input type="checkbox" name="elev[<?php e($elev->get('id')); ?>]" value="<?php e($key); ?>" /></label></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <p><input type="submit" value="Gem"  /></p>
</form>