<?php
class VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Show extends k_Component
{
    public $map = array('edit'   => 'VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Edit',
                        'delete' => 'VIH_Intranet_Controller_Langekurser_Periode_Faggruppe_Delete');
    protected $doctrine;
    protected $template;

    function __construct(Doctrine_Connection_Common $doctrine, k_TemplateFactory $template)
    {
        $this->doctrine = $doctrine;
        $this->template = $template;
    }

    function getDatasource()
    {
        return $this->context->getDatasource();
    }

    function getLangtKursusId()
    {
        return $this->context->name();
    }

    function map($name)
    {
        return $this->map[$name];
    }

    function getModel()
    {
        return Doctrine::getTable('VIH_Model_Course_SubjectGroup')->findOneById($this->name());
    }

    function getSubjects()
    {
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
        return Doctrine::getTable('VIH_Model_Subject')->findOneById($id);
    }

    function renderHtml()
    {
        $this->document->setTitle('Faggruppe: ' . $this->getModel()->getName());
        $this->document->addOption('Opret', $this->url('../create'));
        $this->document->addOption('Tilbage til perioden', $this->url('../../'));

        $chosen = array();
        foreach ($this->getModel()->Subjects as $subject) {
            $chosen[] = $subject->getId();
        }
        
        $faggrupper = VIH_Model_Fag_Gruppe::getList();
        
        foreach ($faggrupper AS $gruppe) {
            $fag[$gruppe->get('id')] = Doctrine::getTable('VIH_Model_Subject')->findByDql('fag_gruppe_id = ? AND published = 1 and active = 1 ORDER BY navn', $gruppe->get('id'));
        }
        

        $data = array('faggrupper'       => $faggrupper, // $this->getSubjects(),
                       'fag' 		=> $fag,
                      'chosen'    => $chosen);

        $tpl = $this->template->create('langekurser/periode/faggruppe');
        return $tpl->render($this, $data);
    }

    function postForm()
    {
        $SubjectGroup = $this->getModel();
        
        $current_subjects = array();
        foreach ($SubjectGroup->Subjects as $subject) {
            $current_subjects[] = $subject->getId();
        }
        
        $new_subjects = array();
        if (is_array($this->body('fag'))) {
            foreach ($this->body('fag') as $subject) {
                $new_subjects[] = $subject;
            }
        }
        
        $remove_subjects = array_diff($current_subjects, $new_subjects);
        $add_subjects = array_diff($new_subjects, $current_subjects);
        
        if (!empty($remove_subjects)) {
            $SubjectGroup->unlink('Subjects', $remove_subjects);
        }

        foreach ($add_subjects as $id) {
            $SubjectGroup->Subjects[] = $this->getSubject($id);
        }

        $SubjectGroup->save();

        return new k_SeeOther($this->context->context->url());
    }


}
