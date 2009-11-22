<table>
<?php foreach ($faggrupper as $faggruppe): ?>
    <tr>
        <td><a href="<?php e(url('faggruppe/' .$faggruppe->getId())); ?>"><?php e($faggruppe->getName()); ?></a></td>
        <td><?php e($faggruppe->getDescription()); ?></td>
        <td>
            <?php
                $s = array();
                foreach ($faggruppe->Subjects as $subject) {
                    $s[] = $subject->getName();
                } 
                echo implode($s, ', ');
            ?>
        </td>
        <td><a href="<?php e(url('faggruppe/' .$faggruppe->getId() . '/edit')); ?>">Ret</a></td>
    </tr>
<?php endforeach; ?>
</table>