<?php
class VIH_Intranet_Controller_LangeKurser_Periode_Create extends k_Controller
{
    function __construct(k_iContext $parent, $name = '')
    {
        parent::__construct($parent, $name);
        $descriptors[] = array('name' => 'name', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'description', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'date_start', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'date_end', 'filters' => array('trim'));
        $this->form = new k_FormBehaviour($this, 'vih/intranet/view/form-tpl.php');
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

        $course = Doctrine::getTable('VIH_Model_Course')->findOneById($this->context->getLangtKursusId());

        $period = new VIH_Model_Course_Period();
        $period->Course = $course;
        $period->name = $values['name'];
        $period->description = $values['description'];
        $period->date_start = $values['date_start'];
        $period->date_end = $values['date_end'];

        try {
            $period->save();
        } catch (Exception $e) {
            throw $e;
        }
        //$values['course_id'] = $this->context->getLangtKursusId();
        //if (!$gateway->insert($values)) {
        //    throw new Exception('insert failed');
        //}
        // It would be proper REST to reply with 201, but browsers doesn't understand that
        throw new k_http_Redirect($this->context->url());
    }
}