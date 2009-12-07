<form action="<?php echo $this->url(); ?>" method="POST">

<fieldset>
<legend>Rater</legend>

<table  width="300">

    <tr>
        <th>Nr.</th>
        <th>Dato</th>
        <th>Beløb</th>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Depositum</td>
        <td align="right"><?php print($tilmelding->get("pris_tilmeldingsgebyr")); ?></td>
    </tr>

    <?php

    $rater = $tilmelding->getRater();
    $samlet_rater = 0;


    for($i = 0, $max = count($rater); $i < $max; $i++) {
        $samlet_rater += $rater[$i]["beloeb"];

        ?>
        <tr>
            <td><?php print($i + 1); ?></td>
            <td>
                <input type="text" name="rate[<?php print($i); ?>][betalingsdato]" value="<?php print($rater[$i]["dk_betalingsdato"]); ?>" size="10" maxlength="10" />
                <input type="hidden" name="rate[<?php print($i); ?>][id]" value="<?php print($rater[$i]["id"]); ?>" />
            </td>
            <td align="right"><input type="text" name="rate[<?php print($i); ?>][beloeb]" value="<?php print($rater[$i]["beloeb"]); ?>" size="5" /></td>
            <td align="right"><a href="?delete=<?php print($rater[$i]["id"]); ?>&amp;id=<?php echo $tilmelding->get('id'); ?>">Slet</a></td>
        </tr>
        <?php
    }
    ?>
    <tr>
        <td>&nbsp;</td>
        <td>Ialt</td>
        <td align="right"><?php print($tilmelding->get("pris_tilmeldingsgebyr") + $samlet_rater); ?></td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td><strong>Difference til kursuspris</strong></td>
        <td align="right"><strong><?php print($tilmelding->get("pris_tilmeldingsgebyr") + $samlet_rater - $tilmelding->get('pris_total')); ?></strong></td>
    </tr>
</table>

</fieldset>

<input type="submit" name="opdater_rater" value="Gem">
</form>
