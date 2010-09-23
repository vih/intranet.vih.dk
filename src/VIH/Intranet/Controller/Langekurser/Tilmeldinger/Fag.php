<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Fag extends k_Component
{
    protected $template;
    protected $doctrine;

    function __construct(k_TemplateFactory $template, Doctrine_Connection_Common $doctrine)
    {
        $this->template = $template;
        $this->doctrine = $doctrine;
    }

    function renderHtml()
    {
        $this->document->setTitle('Tilmelding: Vælg fag');

        $tilmelding = $this->getRegistration();

        $periods = $this->getPeriods();

        $data = array('periods' => $periods,
                      'tilmelding' => $tilmelding);

        $tpl = $this->template->create('langekurser/tilmelding/fag');
        return $tpl->render($this, $data);
    }

    function postForm()
    {
        if (!$this->validate()) {
            throw new Exception('Du skal vælge et af hvert fag');
        }

        $registration = Doctrine::getTable('VIH_Model_Course_Registration')->findOneById($this->getRegistration()->getId());

        $current_subjects = Doctrine::getTable('VIH_Model_Course_Registration_Subject')->findByRegistrationId($this->getRegistration()->getId());

        foreach ($current_subjects as $subj) {
            $subj->delete();
        }
        /*
        $subjects = array();
        foreach ($registration->Subjects as $subject) {
            $subjects[] = $subject->getId();
        }
        if (!empty($subjects)) {
            try {
                $registration->unlink('Subjects', $subjects);
            } catch (Doctrine_Query_Exception $e) {

            }
        }
        */

        if ($this->body('subject')) {
            $input = $this->body();
            foreach ($this->body('subject') as $key => $value) {
                $registrationsubject = new VIH_Model_Course_Registration_Subject;
                $registrationsubject->period_id = $input['subjectperiod'][$key];
                $registrationsubject->registration_id = $this->getRegistration()->getId();
                $registrationsubject->subject_id = $value;
                $registrationsubject->subjectgroup_id = $input['subjectgroup'][$key];
                $registrationsubject->save();
            }
        }

        //$registration->save();

        return new k_SeeOther($this->getRedirectUrl());
    }

    function getPeriods()
    {
        $tilmelding = new VIH_Model_LangtKursus_OnlineTilmelding($this->context->name());
        return Doctrine::getTable('VIH_Model_Course_Period')->findByCourseId($tilmelding->getKursus()->getId());
    }

    function getRedirectUrl()
    {
        return $this->url();
    }

    /**
     * This method should validate whether the correct number of subjects has
     * been chosen i the different periods and subjectgroups.
     */
    function validate()
    {
        // @hack FIXME @todo
        return true;
        /*
        if (empty($this->POST['subject'])) {
            return false;
        }
        return true;
        */
    }

    function getRegistration()
    {
        return new VIH_Model_LangtKursus_Tilmelding($this->context->name());
    }
}