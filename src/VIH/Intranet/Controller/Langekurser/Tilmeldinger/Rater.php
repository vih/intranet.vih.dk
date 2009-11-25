<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Rater extends k_Component
{
    private $template;
    protected $templates;

    function __construct(Template $template, k_TemplateFactory $templates)
    {
        $this->template = $template;
        $this->templates = $templates;
    }

    function renderHtml()
    {
        $tilmelding = new VIH_Model_LangtKursus_Tilmelding(intval($this->context->name()));
        $tilmelding->loadBetaling();

        if($tilmelding->get("id") == 0) {
            trigger_error("Ugyldig tilmelding", E_USER_ERROR);
        }

        if(isset($this->GET["addrate"])) {
            if ($tilmelding->addRate($this->GET["addrate"])) {
                throw new k_SeeOther($this->url());
            } else {
                trigger_error('Raten kunne ikke tilføjes', E_USER_ERROR);
            }
        } elseif(isset($this->GET["delete"])) {
            if ($tilmelding->deleteRate($this->GET["delete"])) {
                throw new k_SeeOther($this->url());
            } else {
                trigger_error('Raten kunne ikke slettes', E_USER_ERROR);
            }
        }

        $pris_tpl = $this->template;
        $pris_tpl->set('tilmelding', $tilmelding);

        $data = array('tilmelding' => $tilmelding);

        $this->document->setTitle('Betalingsrater for ' . $tilmelding->get("navn"));
        $this->document->options = array($this->url(null, array('addrate' => 1)) => 'Tilføj rate');

        return $pris_tpl->fetch('langekurser/tilmelding/prisoversigt-tpl.php') .
            $this->render('VIH/Intranet/view/langekurser/tilmelding/form_rater-tpl.php', $data);

    }

    function postForm()
    {
        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name());
        if(isset($_POST["opdater_rater"])) {
            if ($tilmelding->updateRater($this->POST["rate"])) {
                throw new k_SeeOther($this->url());
            } else {
                trigger_error('Raterne kunne ikke opdateres', E_USER_ERROR);
            }
        }
    }
}