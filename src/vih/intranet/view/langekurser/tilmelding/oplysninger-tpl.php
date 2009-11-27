<table>
    <tr>
        <th>Adresse</th>
        <th>Kontaktperson</th>
    </tr>
    <tr valign="top">
        <td>
            <?php echo $tilmelding->get("cpr"); ?><br />
            <?php echo $tilmelding->get("navn"); ?><br />
            <?php echo $tilmelding->get("adresse"); ?><br />
            <?php echo $tilmelding->get("postnr"); ?>  <?php echo $tilmelding->get("postby"); ?><br />
            <?php echo $tilmelding->get("telefon"); ?> / <?php echo $tilmelding->get("mobil"); ?><br />
            <?php echo $tilmelding->get("email"); ?><br />
            <?php echo $tilmelding->get("kommune"); ?><br />
            <?php echo $tilmelding->get("nationalitet"); ?><br />
            </td>
        <td>
            <?php echo $tilmelding->get("kontakt_navn"); ?><br />
            <?php echo $tilmelding->get("kontakt_adresse"); ?><br />
            <?php echo $tilmelding->get("kontakt_postnr"); ?>  <?php echo $tilmelding->get("kontakt_postby"); ?><br />
            <?php echo $tilmelding->get("kontakt_telefon"); ?> / <?php echo $tilmelding->get("kontakt_arbejdstelefon"); ?> / <?php echo $tilmelding->get("kontakt_mobil"); ?><br />
            <?php echo $tilmelding->get("kontakt_email"); ?><br />
        </td>
    </tr>
</table>

    <?php if ($tilmelding->get("besked") != ''): ?>
    <div id="besked">
        <p><strong>Besked:</strong><br />
        <?php print($tilmelding->get("besked")); ?></p>
    </div>
    <?php endif; ?>

<table>
  <tr>
    <th>Skoleuddannelse:</th>
    <td><?php echo $tilmelding->get("uddannelse"); ?></td>
  </tr>
  <tr>
    <th>Betaling af ophold</th>
    <td><?php echo $tilmelding->get("betaling"); ?></td>
  </tr>
</table>
