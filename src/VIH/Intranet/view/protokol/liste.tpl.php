<?php if (is_object($items)): ?>

    <table>
    <?php while ($items->fetchInto($row)): ?>
        <tr valign="top">
            <td class="<?php e($type_key[$row['type_key']]); ?>">
                <?php e($type_key[$row['type_key']]); ?>
            </td>
            <td nowrap="nowrap">
                <?php e($row['date_start_dk']); ?> &mdash; <?php e($row['date_end_dk']); ?>:
            </td>
            <?php if ($vis_navn == 'true'): ?>
            <td nowrap="nowrap">
                <a href="<?php e(url('holdliste/' . $row['elev_id'])); ?>">
                    <?php
                        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($row['tilmelding_id']);
                        e($tilmelding->get('navn')); ?>
                </a>
            </td>
            <?php endif; ?>
            <td>
                <?php e($row['text']); ?>
            </td>
            <td>
                <a href="<?php e(url('indtast', array('id' => $row['id']))); ?>">Ret</a>
            </td>
            <td>
                <a href="<?php e(url('indtast/' . $row['id'] . '/delete')); ?>" onclick="return confirm('Har du nu tænkt dig grundigt om?')">Slet</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </table>
<?php endif; ?>