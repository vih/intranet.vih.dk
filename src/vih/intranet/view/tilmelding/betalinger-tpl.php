<?php if (!empty($betalinger) AND is_array($betalinger)): ?>

    <?php if (count($betalinger) > 0): ?>
    <table id="betalinger">
        <caption><?php echo $caption; ?></caption>
        <thead>
            <tr>
                <th>Dato</th>
                <th>Beløb</th>
                <th>#</td>
                <th></th>
                <th></th>

            </tr>
        </thead>
        <?php foreach($betalinger AS $betaling):  ?>
            <tr>
                <td><?php echo $betaling->get('date_created_dk'); ?></td>
                <td><?php echo $betaling->get('amount'); ?></td>
                <?php if ($betaling->get('type') == 1): // quickpay ?>
                <td><?php echo $betaling->get('transactionnumber'); ?></td>
                <td><a href="<?php echo url('/betaling/' . $betaling->get('id') . '/capture'); ?>" onclick="return confirm('Er du sikker på, at du vil gennemføre betalingen?');">Gennemfør</a></td>
                <td><a href="<?php echo url('/betaling/' . $betaling->get('id') . '/reverse'); ?>" onclick="return confirm('Er du sikker på, at du vil annullere betalingen?');">Annuller</a></td>
                <?php else: ?>
                <td></td>
                <td></td>
                <td></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p><?php if (isset($msg_ingen)) echo $msg_ingen; ?></p>
    <?php endif; ?>
<?php endif; ?>