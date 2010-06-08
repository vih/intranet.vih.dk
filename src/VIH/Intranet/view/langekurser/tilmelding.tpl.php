<div id="content-left">

    <p><strong>Kursus</strong>: <?php e($tilmelding->getKursus()->getKursusNavn()); ?></p>
    <p><strong>Tilmeldingsdato</strong>: <?php e($tilmelding->get('date_created_dk')); ?></p>
    <p><strong>Periode</strong>: <?php e($tilmelding->get('dato_start_dk')); ?> til <?php e($tilmelding->get('dato_slut_dk')); ?></p>

    <?php echo $message; ?>

    <div id="oplysninger">
        <?php echo $oplysninger; ?>
    </div>

    <?php echo $historik; ?>
</div>

<div id="content-right">

    <div id="status">
        <?php echo ucfirst($tilmelding->get('status')); ?>
    </div>

    <?php echo $prisoversigt; ?>

    <?php if ($tilmelding->antalRater() > 0): ?>
    <p><a href="<?php print(url('rater')); ?>">Ændre rater</a></p>
    <?php endif; ?>

    <?php echo $betalinger; ?>

    <?php echo $rater; ?>


    <?php if($tilmelding->get('skyldig') != 0): ?>
    <form action="<?php e(url()); ?>" method="get">
    <fieldset>
        <legend>Registrer betaling</legend>
            <label>Beløb
                <input type="text" name="beloeb" size="8" />
            </label>
            <input type="submit" name="registrer_betaling" value="Betalt" />
    </fieldset>
    </form>
    <?php endif; ?>

</div>