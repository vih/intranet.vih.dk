<?php $period = Doctrine::getTable('VIH_Model_Course_Period')->findByCourseId($kursus->getId()); ?>
<?php foreach ($period as $p): ?>
    <h2><?php e($p->getName()); ?></h2>
    <?php $subjectgroups = Doctrine::getTable('VIH_Model_Course_SubjectGroup')->findByPeriodId($p->getId()); ?>
    <?php foreach ($subjectgroups as $grp): ?>
        <fieldset>
        <legend><?php e($grp->getName()); ?></legend>
        <?php foreach ($grp->Subjects as $subj): ?>
            <?php
                $subjects_in_period = Doctrine::getTable('VIH_Model_Course_Registration_Subject')->createQuery()->select('*')->addWhere('subject_id = ?', $subj->getId())->addWhere('period_id = ?', $p->getId())->addWhere('subjectgroup_id = ?', $grp->getId());
            ?>

            <?php e($subj->getName() . ' ' . $subjects_in_period->count()); ?>
        <?php endforeach; ?>
        </fieldset>

    <?php endforeach; ?>
<?php endforeach; ?>
</fieldset>
