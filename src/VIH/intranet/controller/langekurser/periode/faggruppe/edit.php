<?php
class VIH_Intranet_Controller_LangeKurser_Periode_Faggruppe_Edit extends k_Controller
{
    function __construct(k_iContext $parent, $name = "")
    {
        parent::__construct($parent, $name);
        $descriptors = array();
        $descriptors[] = array('name' => 'name', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'description', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'elective_course', 'filters' => array('trim'));
        $this->form = new k_FormBehaviour($this, 'vih/intranet/view/form-tpl.php');
        $this->form->descriptors = $descriptors;
    }

    function getDefaultValues()
    {
        $model = $this->context->getModel();
        return array('name' => $model->getName(), 
                     'electice_course' => (string)$model->isElectiveCourse(), 
                     'description' => $model->getDescription());
    }

    function execute()
    {
        $this->form->execute();
        return $this->form->execute();
    }

    function validate($values)
    {
        return TRUE;
    }

    function validHandler($values)
    {
        $this->registry->get('doctrine');
        $group = Doctrine::getTable('VIH_Model_Course_SubjectGroup')->findOneById($this->context->name);
        $group->name = $values['name'];
        $group->description = $values['description'];
        $group->elective_course = $values['elective_course'];

        try {
            $group->save();
        } catch (Exception $e) {
            throw $e;
        }

        throw new k_http_Redirect($this->url("../.."));
    }
}