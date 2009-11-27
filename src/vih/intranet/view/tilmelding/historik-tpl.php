<?php if (is_array($historik)): ?>

<table id="historik">
    <caption>Historik</caption>
    <thead>
        <tr>
            <th>Dato</th>
            <th>Type</th>
            <th>Kommentar</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($historik AS $hist): ?>
        <tr>
            <td><?php echo $hist->get('date_created_dk'); ?></td>
            <td><?php echo $hist->get('type'); ?></td>
            <td><?php
                echo $hist->get('comment');
                if ($hist->get('betaling_id')) {
                     echo ' (bundt#' .$hist->get('betaling_id') . ')';
                }?></td>
            <td>
                <?php if($hist->get('betaling_id') != 0 OR 1==1): ?>
                    <a href="<?php echo url(null, array('slet_historik_id' => $hist->get('id'))); ?>" onClick="return confirm('Dette vil slette historik og evt. tilknyttede betalinger');">slet</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>
