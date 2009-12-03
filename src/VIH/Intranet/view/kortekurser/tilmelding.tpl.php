<?php if (is_object($tilmelding)): ?>
<div id="content-left">

    <div id="tilmeldingsoplysninger">
        <!--  <h1>Tilmelding #<?php e($tilmelding->get('id')); ?></h1> -->
        <p><strong>Kursus:</strong> <?php e($tilmelding->kursus->get('kursusnavn')); ?></p>
        <p><strong>Tilmeldingsdato:</strong> <?php e($tilmelding->get("date_created_dk")); ?></p>
        <p><strong>Periode</strong>: <?php e($tilmelding->kursus->get('dato_start_dk')); ?> til <?php e($tilmelding->kursus->get('dato_end_dk')); ?></p>

        <!--
        <p><strong>Depositum forfalden:</strong> <?php echo $tilmelding->get("dato_forfalden_depositum_dk"); ?></p>
        <p><strong>Forfalden:</strong> <?php echo $tilmelding->get("dato_forfalden_dk"); ?></p>
        <p><strong>Forfalden:</strong> <?php echo $tilmelding->get("forfalden"); ?></p>
        -->
    </div>

    <ul id="navigation-sub">
        <li><a href="<?php echo url('../'); ?>">Tilbage til liste</a></li>
        <li><a href="<?php echo url('edit'); ?>">Ret tilmelding</a></li>
        <li><a href="<?php echo url('delete'); ?>" onclick="return confirm('Er du sikker på, at du vil slette tilmeldingen?');">Slet tilmeldingen</a></li>
        <?php if ($tilmelding->get('email')): ?>
        <li><a href="<?php echo url(null, array('action' => 'sendemail')); ?>" onclick="return confirm('Er du sikker på, at du vil sende en e-mail med koden tilmeldingen?');">Send koden med e-mail</a></li>
        <?php endif; ?>
        <li><a href="<?php echo KORTEKURSER_LOGIN_URI . $tilmelding->get('code'); ?>">Kundens side</a></li>
    </ul>

    <?php echo $message; ?>

    <div id="kontaktperson">
        <?php echo $tilmelding->get("navn"); ?><br />
        <?php echo $tilmelding->get("adresse"); ?><br />
        <?php echo $tilmelding->get("postnr"); ?> <?php echo $tilmelding->get("postby"); ?><br />
        <br />
        Telefon: <?php echo $tilmelding->get("telefonnummer"); ?><br />
        Arbejdstelefon: <?php echo $tilmelding->get("arbejdstelefon"); ?><br />
        <?php if ($tilmelding->get("mobil") != ''): ?>Mobil: <?php echo $tilmelding->get("mobil") . '<br />'; endif; ?>
        E-mail: <?php if($tilmelding->get("email") != ""): ?><a href="<?php echo $tilmelding->get("email"); ?>"><?php echo $tilmelding->get("email"); ?></a><?php else: print("Ej oplyst"); endif; ?>
    </div>

    <div id="besked">
        <?php if ($tilmelding->get("hvilkettidligerekursus") != ''): ?>
            <p><strong>Tidligere deltaget på kursus:</strong> <?php print(ucfirst($tilmelding->get("hvilkettidligerekursus"))); ?></p>
        <?php endif; ?>

        <?php if ($tilmelding->get("besked") != ''): ?>
            <p>
                <strong>Besked:</strong><br />
                <?php echo $tilmelding->get("besked"); ?>
            </p>
        <?php endif; ?>
    </div>

    <?php echo $deltagere; ?>

    <p><a href="<?php echo url('/kortekurser/' . $tilmelding->kursus->get("id") . '/deltagere'); ?>">Se alle deltagere på kurset</a></p>

    <?php echo $historik; ?>


</div>

<div id="content-right">
    <p id="status">
        <?php echo ucfirst($status); ?>
    </p>

<?php echo $prisoversigt; ?>

<form action="<?php echo url('./'); ?>" method="get">

    <fieldset>
        <legend>Registrer betaling</legend>
        <p>Beløb: <input type="text" name="beloeb" value="" />
        <input type="submit" name="registrer_betaling" value="Betalt"  /></p>
    </fieldset>
<?php if($tilmelding->get('skyldig') == 0): ?>
    <fieldset>
        <legend>Status</legend>
        <p><input type="submit" name="annuller_tilmelding" value="Annuller tilmelding" onClick="return confirm('Dette vil annullere tilmeldingen');" /></p>
    </fieldset>
<?php endif; ?>
</form>

<ul>
    <?php if($tilmelding->get('skyldig_depositum') <= 0 && $historik_object->findType("depositumbekraeftelse") == 0 && $tilmelding->get('status') != 'afsluttet'): ?>
        <li><a href="<?php echo url('sendbrev', array('type' => 'depositumbekraeftelse')); ?>">Send bekræftelse for depositum</a></li>
    <?php elseif ((int)$tilmelding->get('skyldig') <= 0 && $historik_object->findType("bekraeftelse") == 0): ?>
        <li><a href="<?php echo url('sendbrev', array('type' => 'bekraeftelse')); ?>">Send bekræftelse</a></li>
    <?php endif; ?>

    <?php if($tilmelding->get("forfalden_depositum")):?>
        <li><a href="<?php echo url('sendbrev', array('type' => 'depositumrykker')); ?>">Send rykker for depositum</a></li>

    <?php elseif($tilmelding->get('forfalden') > 0): ?>
        <li><a href="<?php echo url('sendbrev', array('type' => 'rykker')); ?>">Send rykker</a></li>
    <?php endif; ?>
</ul>


    <br />

    <?php echo $betalinger; ?>

<?php endif; ?>
</div>