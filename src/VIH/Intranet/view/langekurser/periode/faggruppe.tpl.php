<form method="post" action="<?php e(url('./')); ?>">
<table>
<?php foreach ($faggrupper as $gruppe): ?>
    <tr>
        <th colspan="2"><?php e($gruppe->get('navn')); ?></th>
    </tr>
    <?php foreach ($fag[$gruppe->get('id')] AS $f): ?>
        <tr>
            <td><input type="checkbox" name="fag[]" value="<?php e($f->getId()); ?>" <? if(in_array($f->getId(), $chosen)) echo 'checked="checked"'; ?>/></td>
            <td><?php e($f->getName()); ?></td>
        </tr>
    <?php endforeach; ?>    
<?php endforeach; ?>
</table>
<input type="submit" value="Send" />
</form>