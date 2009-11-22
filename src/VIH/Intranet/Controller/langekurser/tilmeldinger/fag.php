<?php

class VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Fag extends VIH_Controller_LangtKursus_Login_Fag
{
    function getRegistration()
    {
        return new VIH_Model_LangtKursus_Tilmelding($this->context->name);
    }

    function GET()
    {

        $this->document->title = $this->getRegistration()->get('navn').' fag på '.$this->getRegistration()->getKursus()->getKursusNavn();
        $this->document->options = array($this->url('../') => 'Tilmeldingen',
                                         $this->url('../diplom') => 'Diplom (pdf)');
    	return parent::GET();
    }
}

/*
class VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Fag extends k_Controller
{
    function getRegistration()
    {
        return new VIH_Model_LangtKursus_Tilmelding($this->context->name);
    }

    function POST()
    {
        $doctrine = $this->registry->get('doctrine');
        $registration = Doctrine::getTable('VIH_Model_Course_Registration')->findOneById($this->context->name);

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

        throw new k_http_Redirect($this->url());
    }

    function GET()
    {
        $doctrine = $this->registry->get('doctrine');
        $registration = Doctrine::getTable('VIH_Model_Course_Registration')->findOneById($this->context->name);

        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name);

        $this->document->title = $tilmelding->get('navn').' fag på '.$tilmelding->getKursus()->getKursusNavn();
        $this->document->options = array($this->url('../') => 'Tilmeldingen',
                                         $this->url('../diplom') => 'Diplom (pdf)');

        $chosen = array();
        foreach ($registration->Subjects as $subject) {
            $chosen[] = $subject->getId();
        }
        $data = array('chosen' => $chosen,
                      'tilmelding' => $registration);

        return $this->render('vih/intranet/view/langekurser/tilmelding/fag.tpl.php', $data);
    }
}
*/