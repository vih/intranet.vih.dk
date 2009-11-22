<h1><?php echo $overskrift; ?></h1>

<p>Følgende tekst vil blive sendt:</p>

<div style="border: 3px solid red; padding: 20px; width: 400px;">
<?php
echo nl2br($brev_tekst);
?>
</div>
<br />
<form action="<?php print(url('./')); ?>" method="POST">

<?php

if($tilmelding->get('email') == "") {
    $email = 'disabled="disabled"';
}
else {
    $email = '';
}
?>
<input type="hidden" name="type" value="<?php print($brev_type); ?>"/>
<input type="submit" name="send_email" value="  Send brev via e-mail  " <?php echo $email; ?> onClick="return confirm('Dette vil sende e-mailen');" /> <input type="submit" name="send_pdf" value="  Send brev via post  " />
</form>