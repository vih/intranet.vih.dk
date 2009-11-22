<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
       <title>Elevliste - frie kostskoler</title>
     <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <style type="text/css">
      h1 { margin-bottom: 0.5em; font-size: 18px; text-align: center; }
      table { font-size: 10px; margin-top: 0; }
      table {width: 100%; border-left: 1px solid black; border-top: 1px solid black; }
      td { border-right: 1px solid black; border-bottom: 1px solid black; }
      th { background: #ccc; color: black; border-right: 1px solid black; border-bottom: 1px solid black; }
      caption { margin-top: 0.5em; font-weight: bolder; text-align: left; }
    </style>
    </head>
    <body>
        <h1>Elevliste - frie kostskoler</h1>
        <table cellspacing="0">
            <tr><th>Skolekode</th><td>631303</td><th colspan="5" rowspan="2">Kursuslængde</th></tr>
            <tr><th>Skolens navn</th><td>Vejle Idrætshøjskole</td></tr>
            <tr><th>Kursusnavn</th><td><?php echo $kursus->get("kursusnavn"); ?></td><th>v/ kurser på en hel uge og derover</th><td colspan="4">&nbsp;</td></tr>
            <tr>
                <th>Dato for kursets begyndelse</th>
                <td><?php echo $kursus->get("dato_start"); ?></td><th>+ evt. afkortede uger</th><td>Antal døgn (begynd.)</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Antal døgn (begynd.)</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr><th>Dato for kursets afslutning</th><td><?php echo $kursus->get("dato_slut"); ?></td><th>v/ kurser under en hel uge</th><td>Antal døgn</td><td colspan="3">&nbsp;</td></tr>
        </table>

        <table cellspacing="0">
            <caption><?php  echo $kursus->get("kursusnavn"); ?></caption>
            <tr>
                <th rowspan="2">Elevens navn</th>
                <th rowspan="2">Personnummer</th>
                <th rowspan="2">Forudg. skolegang / afsluttet klassetrin</th>
                <th colspan="2">Elevtype</th>
                <th colspan="3">Statsborgerskab</th>
                <th rowspan="2">Nationalitetskode</th>
                <th colspan="2">Antal tilskudsløsende uger</th>
                <th colspan="2">For elever, som ikke har fulgt hele kurset</th>
            </tr>
            <tr>
                <th>Kostelev</th>
                <th>Dagelev</th>
                <th>Dansk statsb.</th>
                <th>Sidestillet</th>
                <th>Andet</th>
                <th>Hele uger</th>
                <th>Afkort./korte uger</th>
                <th>Påbegyndt</th>
                <th>Ophørt</th>
            </tr>
            <?php foreach($deltagere AS $deltager):
                // kun deltagere over 17,5 skal opgives, da kun de er tilskudsberettigede
                if  ($deltager->getAge() > 17.5):
                ?>
                <tr>
                    <td><?php echo $deltager->get('navn'); ?></td>
                    <td><?php echo $deltager->get('cpr'); ?></td>
                    <td>&nbsp;</td>
                    <td>x</td>
                    <td>&nbsp;</td>
                    <td>x</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>100</td>
                    <td><?php if ($deltager->get('ugeantal')) echo $deltager->get('ugeantal'); else echo '&nbsp;'; ?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            <?php endif;
        endforeach;
        ?>
</body>
</html>