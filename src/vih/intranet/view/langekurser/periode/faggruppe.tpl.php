<form method="post" action="<?php e(url('./')); ?>">
<table>
<?php foreach ($fag as $f): ?>
    <tr>
        <td><input type="checkbox" name="fag[]" value="<?php e($f->getId()); ?>" <? if(in_array($f->getId(), $chosen)) echo 'checked="checked"'; ?>/></td>
        <td><?php e($f->getName()); ?></td>
    </tr>
<?php endforeach; ?>
</table>
<input type="submit" value="Send" />
</form>