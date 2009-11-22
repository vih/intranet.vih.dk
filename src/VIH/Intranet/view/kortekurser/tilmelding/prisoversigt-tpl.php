<?php if (is_object($tilmelding)): ?>
<table summary="Prisoversigt" id="betalingsoversigt">
    <caption>Prisoversigt</caption>
    <tr>
        <th>Samlet kursuspris</th>
        <td><?php echo $tilmelding->get('pris_kursuspris'); ?></td>
    </tr>
    <?php if($tilmelding->get('pris_forsikring') > 0): ?>
        <tr>
            <th>Afbestillingsforsikring</th>
            <td>+ <?php echo $tilmelding->get('pris_forsikring'); ?></td>
        </tr>
    <?php endif; ?>
    <?php if($tilmelding->get('rabat') > 0): ?>
        <tr>
            <th>Rabat</th>
            <td>- <?php echo $tilmelding->get('rabat'); ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <th>Samlet pris</th>
        <td><?php echo $tilmelding->get('pris_total'); ?></td>
    </tr>
    <tr>
        <th>Depositum</th>
        <td><?php echo $tilmelding->get('pris_depositum'); ?></td>
    </tr>
    <?php if ($tilmelding->get('pris_forudbetaling') != $tilmelding->get('pris_depositum')): ?>
    <tr>
        <th>Forudbetaling</th>
        <td><?php echo $tilmelding->get('pris_forudbetaling'); ?></td>
    </tr>
    <?php endif; ?>
    <tr>
        <th>Betalt</th>
        <td>
            <?php echo (int)$tilmelding->get('betalt'); ?>
            <?php if (count($tilmelding->betaling->getList('not_approved'))): $mark = true; ?>
            *
            <?php endif; ?>
        </th>
    </tr>
    <tr>
        <th>Skyldig</th>
        <td><?php echo $tilmelding->get('skyldig'); ?></td>
    </tr>
</table>
<?php if (!empty($mark)) echo '<p class="notice">* Der er afventende betalinger. Oversigten opdateres, når vi har hævet pengene.</p>'; ?>

<?php endif; ?>
