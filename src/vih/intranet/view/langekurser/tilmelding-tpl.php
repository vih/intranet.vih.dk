<div id="content-left">

    <h1>Tilmelding #<?php echo $tilmelding->get('id'); ?></h1>
    <p><strong>Kursus</strong>: <?php echo $tilmelding->getKursus()->getKursusNavn(); ?></p>
    <p><strong>Tilmeldingsdato</strong>: <?php echo $tilmelding->get('date_created_dk'); ?></p>

    <?php echo $message; ?>

    <ul class="navigation-sub">
        <li><a href="<?php echo url('/langekurser/' . $tilmelding->kursus->get('id')); ?>">Til kursus</a></li>
        <li><a href="<?php echo url('/langekurser/'.$tilmelding->kursus->get('id') . '/tilmeldinger'); ?>">Tilmeldinger</a></li>
        <li><a href="<?php echo url('edit'); ?>">Ret</a></li>
        <li><a href="<?php echo url('delete'); ?>" onclick="return confirm('Er du sikker på, at du vil slette?');">Slet</a></li>
        <li><a href="<?php echo url('/protokol/holdliste/' . $tilmelding->get('id')); ?>">Protokol</a></li>
        <li><a href="<?php echo url('brev'); ?>">Betalingsopgørelse</a></li>
        <li><a href="<?php echo url('fag'); ?>">Fag</a></li>
        <li><a href="<?php echo url('diplom'); ?>">Udskriv diplom</a></li>
        <li><a href="<?php echo LANGEKURSER_LOGIN_URI . $tilmelding->get('code'); ?>">Kundens side</a></li>
    </ul>

    <!--
    <ul class="navigation-sub">
        <li><a href="<?php echo LANGEKURSER_LOGIN_URI . $tilmelding->get('code'); ?>">Kundens side</a></li>
        <li><a href="send_brev.php?id=<?php echo $tilmelding->get('id'); ?>">Betalingsopgørelse</a></li>
        <?php if ($tilmelding->get('email')): ?>
        <li><a href="tilmelding.php?action=sendemail&amp;id=<?php echo $tilmelding->get('id'); ?>" onclick="return confirm('Er du sikker på, at du vil sende bekræftelsese-mailen igen?');">Send e-mail</a></li>
        <?php endif; ?>


        <li><a href="pdfdiplom.php?id=<?php echo $tilmelding->get('id'); ?>" target="_blank">Udskriv diplom</a></li>
    </ul>
    -->

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
    <form action="<?php echo $this->url(); ?>" method="get">
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