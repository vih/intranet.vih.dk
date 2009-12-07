<form action="<?php echo $this->url(); ?>" method="post" id="fag">
<?php $period = Doctrine::getTable('VIH_Model_Course_Period')->findByCourseId($tilmelding->Course->getId()); ?>
<?php foreach ($period as $p) { ?>
    <h2><?php e($p->getName()); ?></h2>
    <?php $subjectgroups = Doctrine::getTable('VIH_Model_Course_SubjectGroup')->findByPeriodId($p->getId()); ?>
    <?php foreach ($subjectgroups as $grp) { ?>
        <fieldset>
        <legend><?php e($grp->getName()); ?></legend>
        <?php foreach ($grp->Subjects as $subj) { ?>
            <input type="checkbox" name="subjects[]" value="<?php e($subj->getId()); ?>" <?php if (in_array($subj->getId(), $chosen)) echo ' checked="checked"' ?>/> <label for=""><?php e($subj->getName()); ?></label>
        <?php } ?>
        </fieldset>
    <?php } ?>
<?php } ?>
</fieldset>
<p><input type="submit" id="submit" value="Videre" /></p>
</form>