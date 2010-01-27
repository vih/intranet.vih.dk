<?php
class VIH_Intranet_Controller_Langekurser_Periode_Edit extends k_Component
{
    function __construct(k_iContext $parent, $name = "")
    {
        parent::__construct($parent, $name);
        $descriptors = Array();
        $descriptors[] = array('name' => 'name', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'description', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'date_start', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'date_end', 'filters' => array('trim'));

        $this->form = new k_FormBehaviour($this, 'VIH/Intranet/view/form-tpl.php');
        $this->form->descriptors = $descriptors;
    }

    function getDefaultValues()
    {
        $model = $this->context->getModel();
        return array('name' => $model->getName(),
                     'description' => $model->getDescription(),
                     'date_start' => $model->getDateStart()->format('%Y-%m-%d'),
                     'date_end' => $model->getDateEnd()->format('%Y-%m-%d'));
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
        $period = Doctrine::getTable('VIH_Model_Course_Period')->findOneById($this->context->name());
        $period->name = $values['name'];
        $period->description = $values['description'];
        $period->date_start = $values['date_start'];
        $period->date_end = $values['date_end'];

        try {
            $period->save();
        } catch (Exception $e) {
            throw $e;
        }

        return new k_SeeOther($this->url("../.."));
    }
}