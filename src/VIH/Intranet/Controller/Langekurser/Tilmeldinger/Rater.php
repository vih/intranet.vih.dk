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

        $this->document->setTitle('Betalingsrater for ' . $tilmelding->get("navn"));
        $this->document->addOption('Tilføj rate', $this->url(null, array('addrate' => 1)));

        if ($tilmelding->get("id") == 0) {
            throw new Exception("Ugyldig tilmelding");
        }

        if ($this->query("addrate")) {
            if ($tilmelding->addRate($this->query("addrate"))) {
                return new k_SeeOther($this->url());
            } else {
                throw new Exception('Raten kunne ikke tilføjes');
            }
        } elseif ($this->query("delete")) {
            if ($tilmelding->deleteRate($this->query("delete"))) {
                return new k_SeeOther($this->url());
            } else {
                throw new Exception('Raten kunne ikke slettes');
            }
        }

        $pris_tpl = $this->templates->create('langekurser/tilmelding/prisoversigt');
        $pris_data = array('tilmelding' => $tilmelding);

        $tpl = $this->templates->create('langekurser/tilmelding/form_rater');
        $data = array('tilmelding' => $tilmelding);

        return $pris_tpl->render($this, $pris_data) .
            $tpl->render($this, $data);

    }

    function postForm()
    {
        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name());
        if (isset($_POST["opdater_rater"])) {
            if ($tilmelding->updateRater($this->body("rate"))) {
                return new k_SeeOther($this->url());
            } else {
                throw new Exception('Raterne kunne ikke opdateres');
            }
        }
        return $this->render();
    }
}