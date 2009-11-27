<?php $saldo = 0; if (is_array($betalinger)): ?>

    <p style="background: orange; border: 2px solid red; color: white; padding: 0.8em;">
        <strong>Advarsel:</strong> Betalinger til elevforeningen må ikke hæves. Det klarer de selv.
    </p>

    <?php if (count($betalinger) > 0): ?>
    <table>
        <tr>

            <th>Dato</th>
            <th>Transaktionsnummer</th>
            <th>Beløb</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>

        <?php foreach ($betalinger AS $betaling): ?>
        <?php
            if ($betaling->get('status_string') == 'approved') {
                $saldo += $betaling->get('amount');
            }
        ?>
        <tr>

            <td><?php echo $betaling->get('date_created_dk'); ?></td>
            <td><?php echo $betaling->get('transactionnumber'); ?></td>
            <td><?php echo $betaling->get('amount'); ?></td>
            <td><?php echo $betaling->get('belong_to'); ?></td>
            <?php if ($betaling->get('status') < 2 AND $betaling->get('belong_to') != 'elevforeningen'): ?>
            <td><a href="<?php echo url($betaling->get('id') . '/capture'); ?>" onclick="return confirm('Er ud sikker?');">Gennemfør</a></td>
            <td><a href="<?php echo url($betaling->get('id') . '/reverse'); ?>" onclick="return confirm('Er ud sikker?');">Annuller</a></td>
            <?php else: ?>
            <td><?php echo $betaling->get('status_string'); ?></td>
            <td></td>
            <?php endif; ?>
            <td><a href="<?php echo url('/' .$betaling->get('belong_to') . '/tilmeldinger/' . $betaling->get('belong_to_id')); ?>">Gå til tilmeldingen</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p><strong>Accepterede betalinger</strong>: <?php echo $saldo; ?> kroner</p>
    <?php else: ?>
    <p>Der er ingen betalinger.</p>
    <?php endif; ?>

<?php endif; ?>