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
        $this->document->setTitle('Tilmelding: Fag');

        $tilmelding = $this->getRegistration();
        $periods = $this->getPeriods();

        $registration = Doctrine::getTable('VIH_Model_Course_Registration')->findOneById($tilmelding->getId());
        $ch_subj = Doctrine::getTable('VIH_Model_Course_Registration_Subject')->findByRegistrationId($tilmelding->getId());

        $chosen = array();
        foreach ($ch_subj as $subj) {
            $chosen[$subj->period_id . $subj->subject_id . $subj->subjectgroup_id] = $subj->subject_id;
        }

        $data = array(
            'periods' => $periods,
            'tilmelding' => $tilmelding,
            'registration' => $registration,
            'chosen' => $chosen);

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

        return new k_SeeOther($this->getRedirectUrl());
    }

    function getPeriods()
    {
        return Doctrine::getTable('VIH_Model_Course_Period')->findByCourseId($this->getRegistration()->getKursus()->getId());
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