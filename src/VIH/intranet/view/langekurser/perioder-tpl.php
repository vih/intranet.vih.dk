<table>
<?php foreach ($perioder as $periode): ?>
    <tr>
        <td><a href="<?php e(url($periode->getId())); ?>"><?php e($periode->getName()); ?></a></td>
        <td><?php e($periode->getDescription()); ?></td>
        <td><?php e($periode->getDateStart()->format('%d-%m-%Y')); ?></td>
        <td><?php e($periode->getDateEnd()->format('%d-%m-%Y')); ?></td>
        <td><a href="<?php e(url($periode->getId() . '/edit')); ?>">Ret</a></td>
    </tr>
<?php endforeach; ?>
</table>