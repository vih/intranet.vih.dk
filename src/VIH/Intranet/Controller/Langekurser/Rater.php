<?php
class VIH_Intranet_Controller_Langekurser_Rater extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name());

        if($this->query("addrate")) {
            if (!$kursus->addRate($this->query("addrate"))) {
                throw new Exception('Kunne ikke tilføje rate.', E_USER_ERROR);
            }
        }

        $this->document->setTitle('Opdater rater');

        $pris = array('kursus' => $kursus);

        if ($kursus->antalRater() == 0) {
            $form = new HTML_QuickForm('rater', 'POST', $this->url());
            $form->addElement('text', 'antal', 'Antal rater');
            $form->addElement('text', 'foerste_rate_dato', 'Første rate dato', 'dd-mm-YYYY');
            $form->addElement('submit', 'opret_rater', 'Opret rater');
            $form_html = $form->toHTML();
        } else {
            $data = array('kursus' => $kursus);
            $tpl =  $this->template->create('langekurser/rater_form');
            $form_html = $tpl->render($this, $data);
        }

        $this->document->setTitle('Rater for betaling '.$kursus->get('kursusnavn'));
        $this->document->addOption('Til kurset', $this->context->url());

        $tpl =  $this->template->create('langekurser/pris');
        return '<p><strong>Periode</strong>: '.$kursus->getDateStart()->format('%d-%m-%Y').' &mdash; '.$kursus->getDateEnd()->format('%d-%m-%Y').'</p>
        ' . $tpl->render($this, $pris) . $form_html;
    }

    function postForm()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name());

        if($this->body("opret_rater")) {
            if (!$kursus->opretRater((int)$this->body("antal"), $this->body("foerste_rate_dato"))) {
                throw new Exception('Kunne ikke oprette rater');
            }
        } elseif($this->body("opdater_rater")) {
            if (!$kursus->updateRater($this->body("rate"))) {
                throw new Exception('Kunne ikke opdatere rater');
            }
        }
        return new k_SeeOther($this->url());
    }
}
