<form action="<?php echo $this->url(); ?>" method="POST">
    <fieldset>
        <legend>Rater</legend>

        <table  width="300">
            <thead>
            <tr>
                <td>Nr.</td>
                <td>Dato</td>
                <td>Beløb</td>
            </tr>
            </thead>

            <tbody>
            <?php

            $rater = $kursus->getRater();
            $samlet_rater = 0;


            for($i = 0, $max = count($rater); $i < $max; $i++) {
                $samlet_rater += $rater[$i]["beloeb"];

                ?>
                <tr>
                    <td><?php print($i + 1); ?></td>
                    <td><input type="text" name="rate[<?php print($i); ?>][betalingsdato]" value="<?php print($rater[$i]["dk_betalingsdato"]); ?>" size="10" maxlength="10" /> <input type="hidden" name="rate[<?php print($i); ?>][id]" value="<?php print($rater[$i]["id"]); ?>" /></td>
                    <td  align="right"><input type="text" name="rate[<?php print($i); ?>][beloeb]" value="<?php print($rater[$i]["beloeb"]); ?>" size="5" /></td>
                </tr>
                <?php
            }
            ?>

            <tr>
                <td>&nbsp;</td>
                <td>Depositum</td>
                <td align="right"><?php print($kursus->get("depositum")); ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>Ialt</td>
                <td align="right"><?php print($kursus->get("depositum") + $samlet_rater); ?></td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td><b>Difference til kursuspris</b></td>
                <td align="right"><b><?php print($kursus->get("depositum") + $samlet_rater - $kursus->getTotalPrice()); ?></b></td>
            </tr>


            </tbody>
        </table>

        <input type="submit" name="opdater_rater" value="Opdater" /> <a href="<?php echo $this->url(null, array('addrate' => 1)); ?>">Tilføj rate</a> <a href="<?php print $this->url(null, array('addrate' => -1)) ?>">Fjern rate</a>

    </fieldset>

    </form>