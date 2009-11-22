<?php
$tjekfaggruppe = '';
$i = 0;
?>

<h1>Vælg fag</h1>

<p><a href="<?php echo url('../'); ?>">Til kursus</a></p>

<form method="post" action="<?php echo url('./'); ?>">
<table>
<?php foreach($fag as $f): ?>
        <?php if ($f->get('faggruppe') != $tjekfaggruppe): $tjekfaggruppe = $f->get('faggruppe'); ?>
        <tr>
            <td colspan="2"><strong><?php echo $f->get('faggruppe'); ?></strong></td>
        </tr>
        <?php endif; ?>
    <tr>
        <td><input type="hidden" name="fag[<?php e($i); ?>]" value="<?php e($f->get('id')); ?>" /><?php echo $f->get('navn'); ?></td>
        <td>
            <?php foreach($periods as $p): ?>
                <input type="checkbox" name="period[<?php e($i); ?>][<?php e($p->getId()); ?>]" value="<?php e($p->getId()); ?>"<?php
                    foreach ($selected as $fagperiod):
                        if ($f->get('id') == $fagperiod->getFag()->getId() AND $p->getId() == $fagperiod->getPeriode()->getId()) {
                            echo ' checked="checked"';
                        }
                    endforeach; ?> /><?php echo $p->getDescription(); ?>
            <?php endforeach; ?>
        </td>
    </tr>

<?php $i++; endforeach; ?>
</table>

<input type="submit" />

</form>