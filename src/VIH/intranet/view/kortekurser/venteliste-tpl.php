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
        <td><?php echo $entry['id']; ?></td>
        -->
        <td><?php echo $entry['nummer']; ?></td>
        <td><?php echo $entry['date_created_dk']; ?></td>
        <td><?php echo $entry['navn']; ?></td>
        <!--
        <td><?php echo $entry['adresse']; ?></td>
        <td><?php echo $entry['postnr'].' '.$entry['postby']; ?></td>
        -->
        <td><a href="mailto:<?php echo $entry['email']; ?>"><?php echo $entry['email']; ?></a></td>
        <td><?php echo $entry['telefon']; ?></td>
        <td><?php echo $entry['arbejdstelefon']; ?></td>
        <td><?php echo $entry['antal']; ?></td>
        <td><?php echo $entry['besked']; ?></td>
        <td><a href="<?php echo url($entry['id'] . '/edit'); ?>">Rediger</a></td>
        <td><a href="<?php echo url($entry['id'] . '/delete'); ?>" onclick="return confirm('Dette vil slette personen');">Slet</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php else: ?>
<p>Der er sørme slet ikke nogen på venteliste.</p>
<?php endif; ?>