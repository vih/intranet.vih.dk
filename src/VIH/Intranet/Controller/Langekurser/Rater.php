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

        if($kursus->get("id") == 0) {
            throw k_http_Response(404);
        }


        if(isset($this->GET["addrate"])) {
            if (!$kursus->addRate($this->GET["addrate"])) {
                trigger_error('Kunne ikke tilføje rate.', E_USER_ERROR);
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
            $form_html = $this->render('VIH/Intranet/view/langekurser/rater_form-tpl.php', $data);
        }

        $this->document->setTitle('Rater for betaling '.$kursus->get('kursusnavn'));
        $this->document->options = array($this->context->url() => 'Til kurset');

        return '<p><strong>Periode</strong>: '.$kursus->getDateStart()->format('%d-%m-%Y').' &mdash; '.$kursus->getDateEnd()->format('%d-%m-%Y').'</p>
        ' . $this->render('VIH/Intranet/view/langekurser/pris-tpl.php', $pris) . $form_html;
    }

    function postForm()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name());

        if(isset($this->POST["opret_rater"])) {
            if (!$kursus->opretRater((int)$this->POST["antal"], $this->POST["foerste_rate_dato"])) {
                trigger_error('Kunne ikke oprette rater', E_USER_ERROR);
            }
        } elseif(isset($this->POST["opdater_rater"])) {
            if (!$kursus->updateRater($this->POST["rate"])) {
                trigger_error('Kunne ikke opdatere rater', E_USER_ERROR);
            }
        }
        return new k_SeeOther($this->url());

    }
}
