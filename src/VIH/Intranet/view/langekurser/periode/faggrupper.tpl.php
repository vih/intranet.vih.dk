<table>
<?php foreach ($faggrupper as $gruppe): ?>
    <tr>
        <td><a href="<?php e(url($gruppe->getId())); ?>"><?php e($gruppe->getName()); ?></a></td>
        <td><?php e($gruppe->getDescription()); ?></td>
        <td><a href="<?php e(url($gruppe->getId() . '/edit')); ?>">Ret</a></td>
    </tr>
<?php endforeach; ?>
</table>