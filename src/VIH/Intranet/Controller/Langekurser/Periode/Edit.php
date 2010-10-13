<?php
class VIH_Intranet_Controller_Langekurser_Periode_Edit extends k_Component
{
    protected $doctrine;
    protected $template;

    function __construct(k_TemplateFactory $template, Doctrine_Connection_Common $connection)
    {
        $this->doctrine = $doctrine;
        $this->template = $template;
    }

    function renderHtml()
    {
        $this->document->setTitle('Rediger periode');

        $descriptors = Array();
        $descriptors[] = array('name' => 'name', 'filters' => array('trim'), 'default' => $this->getDefaultValue('name'));
        $descriptors[] = array('name' => 'description', 'filters' => array('trim'), 'default' => $this->getDefaultValue('description'));
        $descriptors[] = array('name' => 'date_start', 'filters' => array('trim'), 'default' => $this->getDefaultValue('date_start'));
        $descriptors[] = array('name' => 'date_end', 'filters' => array('trim'), 'default' => $this->getDefaultValue('date_end'));

        $tpl = $this->template->create('form');
        return $tpl->render($this, array('descriptors' => $descriptors));
    }

    function getDefaultValue($key)
    {
        $model = $this->context->getModel();
        $defaults = array('name' => $model->getName(),
                     'description' => $model->getDescription(),
                     'date_start' => $model->getDateStart()->format('%Y-%m-%d'),
                     'date_end' => $model->getDateEnd()->format('%Y-%m-%d'));
        return $defaults[$key];
    }


    function validate()
    {
        return TRUE;
    }

    function postForm()
    {
        $values = $this->body();
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