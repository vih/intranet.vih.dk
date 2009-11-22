<?php
class VIH_Intranet_Controller_LangeKurser_Periode_Show extends k_Controller
{
    public $map = array('edit'   => 'VIH_Intranet_Controller_LangeKurser_Periode_Edit',
                        'delete' => 'VIH_Intranet_Controller_LangeKurser_Periode_Delete',
                        'faggruppe' => 'VIH_Intranet_Controller_LangeKurser_Periode_Faggruppe_Index');

    function getDatasource()
    {
        return $this->context->getDatasource();
    }

    function getLangtKursusId()
    {
        return $this->context->name;
    }

    protected function forward($name)
    {
        $response = parent::forward($name);
        return $response;
    }

    function getModel()
    {
        $this->registry->get('doctrine');
        return Doctrine::getTable('VIH_Model_Course_Period')->findOneById($this->name);
    }

    function getSubjectGroup()
    {
        $this->registry->get('doctrine');
        return Doctrine::getTable('VIH_Model_Course_SubjectGroup')->findByPeriodId($this->name);
    }

    function GET()
    {
        $periode = $this->getModel();
        $this->document->title = $this->getModel()->getName() . $this->getModel()->getDateStart()->format('%d-%m-%Y') . ' til ' . $this->getModel()->getDateEnd()->format('%d-%m-%Y');
        $this->document->options = array(
            $this->url('faggruppe/create') => 'Opret faggruppe',
            $this->url('../') => 'Luk'
        );

        return $this->render('vih/intranet/view/langekurser/periode/show.tpl.php', array('periode' => $periode, 'faggrupper' => $this->getSubjectGroup()));
    }

}
