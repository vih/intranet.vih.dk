<?php

class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Fag extends VIH_Controller_LangtKursus_Login_Fag
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function getRegistration()
    {
        return new VIH_Model_LangtKursus_Tilmelding($this->context->name());
    }

    function renderHtml()
    {

        $this->document->setTitle($this->getRegistration()->get('navn').' fag på '.$this->getRegistration()->getKursus()->getKursusNavn());
        $this->document->options = array($this->url('../') => 'Tilmeldingen',
                                         $this->url('../diplom') => 'Diplom (pdf)');
    	return parent::GET();
    }
}

/*
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Fag extends k_Component
{
    function getRegistration()
    {
        return new VIH_Model_LangtKursus_Tilmelding($this->context->name());
    }

    function postForm()
    {
        $doctrine = $this->registry->get('doctrine');
        $registration = Doctrine::getTable('VIH_Model_Course_Registration')->findOneById($this->context->name());

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

        foreach ($this->POST['subjects'] as $key => $value) {
            $subject = Doctrine::getTable('VIH_Model_Subject')->findOneById($value);
            $registration->Subjects[] = $subject;
        }

        $registration->save();

        return new k_SeeOther($this->url());
    }

    function renderHtml()
    {
        $doctrine = $this->registry->get('doctrine');
        $registration = Doctrine::getTable('VIH_Model_Course_Registration')->findOneById($this->context->name());

        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name());

        $this->document->setTitle($tilmelding->get('navn').' fag på '.$tilmelding->getKursus()->getKursusNavn();
        $this->document->options = array($this->url('../') => 'Tilmeldingen',
                                         $this->url('../diplom') => 'Diplom (pdf)');

        $chosen = array();
        foreach ($registration->Subjects as $subject) {
            $chosen[] = $subject->getId();
        }
        $data = array('chosen' => $chosen,
                      'tilmelding' => $registration);

        return $this->render('VIH/Intranet/view/langekurser/tilmelding/fag.tpl.php', $data);
    }
}
*/