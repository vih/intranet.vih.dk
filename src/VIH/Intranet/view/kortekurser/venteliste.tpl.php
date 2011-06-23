<?php if (is_array($venteliste) AND count($venteliste) > 0): ?>
<table>
    <tr>
        <!--
        <th>Id</th>
        -->
        <th>#</th>
        <th>Opskrevet</th>
        <th>Kontaktnavn</th>
        <!--
        <th>Adresse</th>
        <th>Postnr. og by</th>
        -->
        <th>E-mail</th>
        <th>Telefon</th>
        <th>Arbejdstelefon</th>
        <th>Antal</th>
        <th>Besked</th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach ($venteliste AS $entry): ?>
    <tr>
        <!--
        <td><?php e($entry['id']); ?></td>
        -->
        <td><?php e($entry['nummer']); ?></td>
        <td><?php e($entry['date_created_dk']); ?></td>
        <td><?php e($entry['navn']); ?></td>
        <!--
        <td><?php e($entry['adresse']); ?></td>
        <td><?php e($entry['postnr'].' '.$entry['postby']); ?></td>
        -->
        <td><a href="mailto:<?php e($entry['email']); ?>"><?php e($entry['email']); ?></a></td>
        <td><?php e($entry['telefon']); ?></td>
        <td><?php e($entry['arbejdstelefon']); ?></td>
        <td><?php e($entry['antal']); ?></td>
        <td><?php e($entry['besked']); ?></td>
        <td><a href="<?php e(url($entry['id'], array('edit'))); ?>">Rediger</a></td>
        <td><a href="<?php e(url($entry['id'], array('delete'))); ?>" onclick="return confirm('Dette vil slette personen');">Slet</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php else: ?>
<p>Der er sørme slet ikke nogen på venteliste.</p>
<?php endif; ?>