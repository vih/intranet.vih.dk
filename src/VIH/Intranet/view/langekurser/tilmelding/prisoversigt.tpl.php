<table id="prisoversigt">
    <caption <?php if (isset($skyldig) AND $skyldig > 0) echo ' class="notpayed"'; ?>>Pris</caption>
    <tr>
        <th>Tilmeldingsgebyr</th>
        <td><?php echo number_format($tilmelding->get("pris_tilmeldingsgebyr"), 0, ',','.'); ?></td>
    </tr>

    <tr>
        <th>Antal uger</th>
        <td><?php echo $tilmelding->get("ugeantal"); ?></td>
    </tr>

    <tr>
        <th>Ugepris</th>
        <td><?php echo number_format($tilmelding->get("pris_uge"), 0, ',','.'); ?></td>
    </tr>

    <tr>
        <th>Samlet ugepris</th>
        <td><?php echo number_format($tilmelding->get("ugeantal") * $tilmelding->get("pris_uge"), 0, ',', '.'); ?></td>
    </tr>
    <tr>
        <th>Materialegebyr</th>
        <td><?php echo number_format($tilmelding->get("pris_materiale"), 0, ',', '.'); ?></td>
    </tr>
    <tr>
        <th>Rejseforudbetaling</th>
        <td><?php echo number_format($tilmelding->get("pris_rejsedepositum"), 0, ',', '.'); ?></td>
    </tr>
    <tr>
        <th>Restbeløb rejse 1)</th>
        <td><?php echo number_format($tilmelding->get("pris_rejserest"), 0, ',', '.'); ?></td>
    </tr>
    <?php if ($tilmelding->get("pris_rejselinje") > 0): ?>
    <tr>
        <th>Rejselinje</th>
        <td><?php echo number_format($tilmelding->get("pris_rejselinje"), 0, ',', '.'); ?></td>
    </tr>
    <?php endif; ?>
    <tr>
        <th>Nøgledepositum</th>
        <td><?php echo number_format($tilmelding->get("pris_noegledepositum"), 0, ',', '.'); ?></td>
    </tr>
  <?php if ($tilmelding->get("aktiveret_tillaeg") > 0): ?>
    <tr>
        <th>Tillæg til aktiverede</th>
        <td><?php echo number_format($tilmelding->get("aktiveret_tillaeg"), 0, "", ""); ?></td>
    </tr>
  <?php endif; ?>

    <?php if($tilmelding->get("pris_afbrudt_ophold") != 0): ?>
        <tr>
            <th>Afbrudt ophold</th>
            <td><?php echo number_format($tilmelding->get("pris_afbrudt_ophold"), 0, ',', '.'); ?></td>
        </tr>
        <?php endif; ?>

  <?php if ($tilmelding->get("elevstotte") > 0): ?>
    <tr>
        <th>Elevstøtte (<?php echo $tilmelding->get('elevstotte'); ?> kr * <?php echo $tilmelding->get('ugeantal_elevstotte'); ?> uger)</th>
        <td> - <?php echo number_format($tilmelding->get("elevstotte") * $tilmelding->get('ugeantal_elevstotte'), 0, "", ""); ?></td>
    </tr>
  <?php endif; ?>
  <?php if ($tilmelding->get("kompetencestotte") > 0): ?>
    <tr>
        <th>Kompetencestøtte (<?php echo $tilmelding->get('kompetencestotte'); ?> kr * <?php echo $tilmelding->get('ugeantal'); ?> uger)</th>
        <td> - <?php echo number_format($tilmelding->get("kompetencestotte"), 0, "", "") * $tilmelding->get('ugeantal'); ?></td>
    </tr>
  <?php endif; ?>
  <?php if ($tilmelding->get("statsstotte") > 0): ?>
    <tr>
        <th>Indvandrerstøtte (<?php echo $tilmelding->get("statsstotte"); ?> * <?php echo $tilmelding->get('ugeantal'); ?>)</th>
        <td>- <?php echo number_format($tilmelding->get("statsstotte") * $tilmelding->get('ugeantal'), 0, "", ""); ?></td>
    </tr>
  <?php endif; ?>
 <?php if ($tilmelding->get("kommunestotte") > 0): ?>
    <tr>
        <th>Kommunestøtte</th>
        <td> - <?php echo number_format($tilmelding->get("kommunestotte"), 0, "", ""); ?></td>
    </tr>
  <?php endif; ?>

    <tr>
        <th>Samlet pris</th>
        <td><?php echo number_format($tilmelding->get("pris_total"), 0, ',','.'); ?></td>
    </tr>

    <tr>
        <th>Har betalt</th>
        <td>
            <?php echo number_format((int)$tilmelding->get("betalt"), 0, ',', '.'); ?>
            <?php if ($tilmelding->get('betalt_not_approved') > 0) echo '*'; ?>
        </td>
    </tr>
    <tr>
        <th>Restance</th>
        <td><?php echo number_format($tilmelding->get("saldo"), 0, ',','.'); ?></td>
    </tr>
    <?php
    if($tilmelding->get("forfalden") > 0) {
        ?>
        <tr>
            <th>Forfalden</th>
            <td class="red" style="text-align: right;"><?php echo number_format($tilmelding->get("forfalden"), 0, ',','.'); ?></td>
        </tr>
        <?php
    }
    ?>
</table>

<p class="noter">1) Restbeløbet for rejsen fastlægges først, når den endelige pris for rejsen foreligger.</p>

<?php if ($tilmelding->get('betalt_not_approved') > 0): ?>
    <p class="notice">* Der er afventende betalinger. Oversigten opdateres, når vi hæver dem.</p>
<?php endif; ?>
