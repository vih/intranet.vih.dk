<?php
class VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Edit extends k_Component
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
        $descriptors[] = array('name' => 'name', 'filters' => array('trim'), 'default' => $this->getDefaultValue('name'));
        $descriptors[] = array('name' => 'description', 'filters' => array('trim'), 'default' => $this->getDefaultValue('description'));
        $descriptors[] = array('name' => 'elective_course', 'filters' => array('trim'), 'default' => $this->getDefaultValue('elective_course'));

        $tpl = $this->template->create('form');
        return $tpl->render($this, array('descriptors' => $descriptors));
    }

    function getDefaultValue($key)
    {
        $model = $this->context->getModel();
        $defaults = array('name' => $model->getName(),
                     'electice_course' => (string)$model->isElectiveCourse(),
                     'description' => $model->getDescription());
        return $defaults[$key];

    }

    function validate($values)
    {
        return TRUE;
    }

    function postForm()
    {
        $values = $this->body();
        $group = Doctrine::getTable('VIH_Model_Course_SubjectGroup')->findOneById($this->context->name());
        $group->name = $values['name'];
        $group->description = $values['description'];
        $group->elective_course = $values['elective_course'];

        try {
            $group->save();
        } catch (Exception $e) {
            throw $e;
        }

        return new k_SeeOther($this->url("../.."));
    }
}