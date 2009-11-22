<?php if (is_array($bestillinger) AND count($bestillinger) > 0): ?>
<!--
<p><a href="pdf_label.php" class="pdf">Udskriv labels</a></p>
-->
<table>
    <thead>
    <tr>
        <th>Dato</th>
        <th>Navn</th>
        <th>Adresse</th>
        <th>Postnr og by</th>
        <th>Status</th>
        <th>Lange kurser</th>
        <th>Korte kurser</th>
        <th>Efterskole</th>
        <th>Kursuscenter</th>
        <th>Telefon</th>
        <th>Email</th>
        <th>Kommentar</th>
    </tr>
    </thead>

    <tbody>
    <?php for($i = 0, $max = count($bestillinger); $i < $max; $i++) { ?>
        <tr>
            <td><?php echo $bestillinger[$i]["date_dk"]; ?></td>
            <td><?php echo $bestillinger[$i]["navn"]; ?></td>
            <td><?php echo $bestillinger[$i]["adresse"]; ?></td>
            <td><?php echo $bestillinger[$i]["postnr"]; ?>  <?php print($bestillinger[$i]["postby"]); ?></td>
            <td>
                <?php if ($bestillinger[$i]["er_sendt"] <> 1): ?>
                <a href="?sent=<?php echo $bestillinger[$i]["id"]; ?>">Er sendt</a>
                <?php endif;	 ?>
            </td>
            <td align="center"><?php if ($bestillinger[$i]["langekurser"] == 1) echo '+'; ?>&nbsp;</td>
            <td align="center"><?php if ($bestillinger[$i]["kortekurser"] == 1) echo '+'; ?>&nbsp;</td>
            <td align="center"><?php if ($bestillinger[$i]["efterskole"] == 1) echo '+'; ?>&nbsp;</td>
            <td align="center"><?php if ($bestillinger[$i]["kursuscenter"] == 1) echo '+'; ?>&nbsp;</td>
            <td><?php echo $bestillinger[$i]["telefon"]; ?></td>
            <td><?php echo $bestillinger[$i]["email"]; ?></td>
            <td><?php echo $bestillinger[$i]["besked"]; ?>&nbsp;</td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php else: ?>
    <p>Der er i øjeblikket ikke nogen bestillinger, der mangler at blive sendt ud.</p>
<?php endif; ?>