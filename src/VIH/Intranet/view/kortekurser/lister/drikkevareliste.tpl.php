<p><img src="<?php e(url('/images/logo/vih_logo.jpg')); ?>" width="200" alt="" /></p>
<table cellspacing="0" cellpadding="5">
    <caption>Drikkevarer til <?php e($kursus->get("kursusnavn")); ?></caption>
    <tr>
        <th>Navn</th>
        <th>Øl 16 kr</th>
        <th>Fadøl 20 kr</th>
        <th>Rødvin 75 kr</th>
        <th>Hvidvin 75 kr</th>
        <th>Sodavand 16 kr</th>
        <th>Andet</th>
    </tr>
    <?php foreach ($deltagere as $deltager): ?>
        <tr>
            <td><?php e($deltager->get("navn")); ?></td>
            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        </tr>
    <?php endforeach; ?>
</table>
