<?php if (isset($gallerier) && is_array($gallerier)): ?>

    <table>
    <?php foreach($gallerier AS $galleri): ?>
        <tr valign="top">
            <td nowrap="nowrap"><?php echo htmlentities($galleri['dk_date_created']); ?></td>
            <td><a href="<?php echo $galleri['url_show']; ?>"><?php echo htmlentities($galleri['description']); ?></a></td>
            <td><a href="<?php echo $galleri['url_active']; ?>"><?php echo htmlentities($galleri['active']); ?></a></td>
            <td><a href="<?php echo $galleri['url_edit']; ?>">Ret</a></td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>