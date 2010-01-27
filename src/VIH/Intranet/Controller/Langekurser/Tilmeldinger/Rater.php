<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Rater extends k_Component
{
    protected $templates;

    function __construct(k_TemplateFactory $templates)
    {
        $this->templates = $templates;
    }

    function renderHtml()
    {
        $tilmelding = new VIH_Model_LangtKursus_Tilmelding(intval($this->context->name()));
        $tilmelding->loadBetaling();

        if($tilmelding->get("id") == 0) {
            trigger_error("Ugyldig tilmelding", E_USER_ERROR);
        }

        if($this->query("addrate")) {
            if ($tilmelding->addRate($this->query("addrate"))) {
                return new k_SeeOther($this->url());
            } else {
                trigger_error('Raten kunne ikke tilføjes', E_USER_ERROR);
            }
        } elseif($this->query("delete")) {
            if ($tilmelding->deleteRate($this->query("delete"))) {
                return new k_SeeOther($this->url());
            } else {
                trigger_error('Raten kunne ikke slettes', E_USER_ERROR);
            }
        }

        $pris_tpl = $this->templates->create('langekurser/tilmelding/prisoversigt');
        $pris_data = array('tilmelding' => $tilmelding);

        $data = array('tilmelding' => $tilmelding);

        $this->document->setTitle('Betalingsrater for ' . $tilmelding->get("navn"));
        $this->document->options = array($this->url(null, array('addrate' => 1)) => 'Tilføj rate');

        $tpl = $this->templates->create('langekurser/tilmelding/form_rater');

        return $pris_tpl->render($this, $pris_data) .
            $tpl->render($this, $data);

    }

    function postForm()
    {
        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name());
        if(isset($_POST["opdater_rater"])) {
            if ($tilmelding->updateRater($this->body("rate"))) {
                return new k_SeeOther($this->url());
            } else {
                trigger_error('Raterne kunne ikke opdateres', E_USER_ERROR);
            }
        }
    }
}