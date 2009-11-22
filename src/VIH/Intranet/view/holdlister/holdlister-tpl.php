<?php if (is_array($fag)): $test = ''; $i = 0; ?>
    <?php foreach ($fag AS $f): ?>
        <?php if ($f->get('faggruppe') != $test) { echo '<p>I alt: '.$i.'</p>'; echo '<h2>'; e($f->get('faggruppe')); echo '</h2>'; $i = 0; } $test = $f->get('faggruppe'); ?>
        <li>
            <a href="<?php e(url($f->get('id'), array('date' => $date))); ?>"><?php e($f->get('navn')); ?></a> <?php echo $this->getCount($f); $i += $this->getCount($f); ?>
        </li>
    <?php endforeach; ?>
    <?php echo '<p>I alt: '.$i.'</p>'; ?>
<?php endif; ?>