<?php $tjekfaggruppe = ''; if (is_array($list)): ?>

    <table>
        <caption>Fag</caption>

    <?php foreach ($list AS $fag): ?>
        <?php if ($fag->get('faggruppe') != $tjekfaggruppe): $tjekfaggruppe = $fag->get('faggruppe'); ?>
        <tr>
            <td colspan="4"><strong><?php echo $fag->get('faggruppe'); ?></strong></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td><a href="<?php echo url($fag->get('id')); ?>"><?php echo $fag->get('navn'); ?></a></td>
            <td><a href="<?php echo url($fag->get('id') . '/edit'); ?>">Ret</a></td>
            <td><a href="<?php echo url($fag->get('id') . '/delete'); ?>" onclick="return confirm('Er du sikker?');">Slet</a></td>
            <!--<td><a href="<?php echo $fag->get('id'); ?>" class="pdf">Pdf</a></td>-->
        </tr>
    <?php endforeach; ?>

    </table>

<?php endif; ?>