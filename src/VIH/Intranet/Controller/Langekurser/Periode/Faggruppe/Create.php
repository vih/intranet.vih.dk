<?php
class VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Create extends k_Component
{
    protected $doctrine;
    protected $template;

    function __construct(Doctrine_Connection_Common $doctrine, k_TemplateFactory $template)
    {
        $this->doctrine = $doctrine;
        $this->template = $template;
    }

    function renderHtml()
    {
        $this->document->setTitle('Rediger faggruppe');

        $descriptors = array();
        $descriptors[] = array('name' => 'name', 'description' => 'Navn', 'filters' => array('trim'), 'default' => '');
        $descriptors[] = array('name' => 'description', 'description' => 'Beskrivelse', 'filters' => array('trim'), 'default' => '');
        $descriptors[] = array('name' => 'elective_course', 'description' => 'Elever skal vælge fag (0 eller 1)', 'filters' => array('trim'), 'default' => '1');

        $tpl = $this->template->create('form');
        return $tpl->render($this, array('descriptors' => $descriptors));
    }

    function validate($values)
    {
        return TRUE;
    }

    function postForm()
    {
        $values = $this->body();
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

        return new k_SeeOther($this->context->context->url());
    }
}