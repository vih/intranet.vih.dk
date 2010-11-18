<?php
class VIH_Intranet_Controller_Langekurser_Periode_Create extends k_Component
{
    protected $doctrine;
    protected $template;

    function __construct(Doctrine_Connection_Common $doctrine, k_TemplateFactory $template)
    {
        $this->template = $template;
        $this->doctrine = $doctrine;
    }

    function renderHtml()
    {
        $this->document->setTitle('Opret fagperiode');
        $descriptors[] = array('name' => 'name', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'description', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'date_start', 'filters' => array('trim'));
        $descriptors[] = array('name' => 'date_end', 'filters' => array('trim'));
        $tpl = $this->template->create('form');
        return $tpl->render($this, array('descriptors' => $descriptors));
    }

    function validate()
    {
        return TRUE;
    }

    function postForm()
    {
        if (!$this->validate()) {
            return $this->render();
        }
        $values = $this->body();
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
        return new k_SeeOther($this->context->url());
    }
}