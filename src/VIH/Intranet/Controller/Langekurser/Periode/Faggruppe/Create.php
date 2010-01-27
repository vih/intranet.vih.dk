<?php
class VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Create extends k_Component
{
    function __construct(k_iContext $parent, $name = '')
    {
        parent::__construct($parent, $name);
        $descriptors[] = array('name' => 'name', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'description', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'elective_course', 'filters' => array('trim'));
        $this->form = new k_FormBehaviour($this, 'VIH/Intranet/view/form-tpl.php');
        $this->form->descriptors = $descriptors;
    }

    function execute()
    {
        return $this->form->execute();
    }

    function validate($values)
    {
        return TRUE;
    }

    function validHandler($values)
    {
        $doctrine = $this->registry->get('doctrine');

        $period = Doctrine::getTable('VIH_Model_Course_Period')->findOneById($this->context->getPeriodId());

        $group = new VIH_Model_Course_SubjectGroup();
        $group->Period = $period;
        $group->name = $values['name'];
        $group->elective_course = $values['elective_course'];
        $group->description = $values['description'];
        
        $course = $period->Course;
        $course->SubjectGroups[] = $group;
        $course->save();

        try {
            $group->save();
        } catch (Exception $e) {
            throw $e;
        }

        return new k_SeeOther($this->context->url());
    }
}