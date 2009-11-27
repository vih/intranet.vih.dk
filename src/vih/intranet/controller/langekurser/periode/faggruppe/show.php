<?php
class VIH_Intranet_Controller_LangeKurser_Periode_Faggruppe_Show extends k_Controller
{
    public $map = array('edit'   => 'VIH_Intranet_Controller_LangeKurser_Periode_Faggruppe_Edit',
                        'delete' => 'VIH_Intranet_Controller_LangeKurser_Periode_Faggruppe_Delete');

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
        return Doctrine::getTable('VIH_Model_Course_SubjectGroup')->findOneById($this->name);
    }

    function getSubjects()
    {
        $this->registry->get('doctrine');
        return Doctrine::getTable('VIH_Model_Subject')->findAll();
        
        /*
        $sql = "SELECT IFNULL(langtkursus_fag_periode.date_start, '9999-01-01') AS date_start,
                IFNULL(langtkursus_fag_periode.date_end, '9999-01-01') AS date_end, fag.id AS id, x.periode_id as periode_id
            FROM langtkursus_x_fag x
            LEFT JOIN langtkursus_fag_periode ON x.periode_id = langtkursus_fag_periode.id
            INNER JOIN langtkursus_fag fag ON x.fag_id = fag.id
            INNER JOIN langtkursus_fag_gruppe gruppe ON fag.fag_gruppe_id = gruppe.id";

        $sql .= "
                INNER JOIN ansat_x_fag ON ansat_x_fag.fag_id = fag.id
                INNER JOIN ansat ON ansat.id = ansat_x_fag.ansat_id";

        $sql .= "   WHERE x.langtkursus_id = " . $this->id;
        $sql .= " AND fag.active = 1 AND fag.published = 1";

        $sql .= " ORDER BY
                           date_start ASC,
                           date_end DESC,
                           gruppe.position ASC,
                           fag.fag_gruppe_id ASC,
                           fag.navn ASC";
    
        $db = $this->registry->get('database:mdb2');
        $result = $db->query($sql);
        $subjects = array();
        while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
            $subjects[] = $this->getSubject($row['id']);
        }
        return $subjects;
        */
    }
    
    function getSubject($id)
    {
        $this->registry->get('doctrine');
        return Doctrine::getTable('VIH_Model_Subject')->findOneById($id);
    }

    function GET()
    {
        $this->document->title = 'Faggruppe: ' . $this->getModel()->getName();
        $this->document->options = array(
            $this->url('../create') => 'Opret',
            $this->url('../../') => 'Tilbage til perioden' 
        );
        
        $chosen = array();
        foreach ($this->getModel()->Subjects as $subject) {
            $chosen[] = $subject->getId();
        }
        
        $data = array('fag'       => $this->getSubjects(), 
                      'faggruppe' => $this->getModel(),
                      'chosen'    => $chosen);
        return $this->render('vih/intranet/view/langekurser/periode/faggruppe.tpl.php', $data);
    }
    
    function POST()
    {
        $SubjectGroup = $this->getModel();
        $subjects = array();
        foreach ($SubjectGroup->Subjects as $subject) {
            $subjects[] = $subject->getId();
        }
        if (!empty($subjects)) {
            try {
                $SubjectGroup->unlink('Subjects', $subjects);
            } catch (Doctrine_Query_Exception $e) {
            }    
        }        
        
        if (isset($this->POST['fag']) and is_array($this->POST['fag'])) {
            foreach ($this->POST['fag'] as $key => $post) {
                $SubjectGroup->Subjects[] = $this->getSubject($post);        
            }
        }
        
        try {
            $SubjectGroup->save();
        } catch (Exception $e) {
            throw $e;
        }
        
        throw new k_http_Redirect($this->context->url());
    }
    

}
