<?php if (is_array($photos)): ?>

    <table>
    <?php foreach($photos AS $photo): ?>
        <tr valign="top">
            <td nowrap="nowrap">
                <img src="<?php echo htmlentities($photo['icon_uri']); ?>" width="<?php echo htmlentities($photo['icon_width']); ?>" height="<?php echo htmlentities($photo['icon_height']); ?>" />
            </td>
            <td><a href="<?php echo $photo['url_delete']; ?>">Slet</a></td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>