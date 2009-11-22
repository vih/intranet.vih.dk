<?php
class VIH_Intranet_Controller_LangeKurser_Rater extends k_Controller
{
    function GET()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name);

        if($kursus->get("id") == 0) {
            throw k_http_Response(404);
        }


        if(isset($this->GET["addrate"])) {
            if (!$kursus->addRate($this->GET["addrate"])) {
                trigger_error('Kunne ikke tilføje rate.', E_USER_ERROR);
            }
        }

        $this->document->title = 'Opdater rater';

        $pris = array('kursus' => $kursus);

        if ($kursus->antalRater() == 0) {
            $form = new HTML_QuickForm('rater', 'POST', $this->url());
            $form->addElement('text', 'antal', 'Antal rater');
            $form->addElement('text', 'foerste_rate_dato', 'Første rate dato', 'dd-mm-YYYY');
            $form->addElement('submit', 'opret_rater', 'Opret rater');
            $form_html = $form->toHTML();
        } else {
            $data = array('kursus' => $kursus);
            $form_html = $this->render('vih/intranet/view/langekurser/rater_form-tpl.php', $data);
        }
        
        $this->document->title = 'Rater for betaling '.$kursus->get('kursusnavn');
        $this->document->options = array($this->context->url() => 'Til kurset');
        
        return '<p><strong>Periode</strong>: '.$kursus->getDateStart()->format('%d-%m-%Y').' &mdash; '.$kursus->getDateEnd()->format('%d-%m-%Y').'</p>
        ' . $this->render('vih/intranet/view/langekurser/pris-tpl.php', $pris) . $form_html;
    }

    function POST()
    {
        $kursus = new VIH_Model_LangtKursus($this->context->name);

        if(isset($this->POST["opret_rater"])) {
            if (!$kursus->opretRater((int)$this->POST["antal"], $this->POST["foerste_rate_dato"])) {
                trigger_error('Kunne ikke oprette rater', E_USER_ERROR);
            }
        } elseif(isset($this->POST["opdater_rater"])) {
            if (!$kursus->updateRater($this->POST["rate"])) {
                trigger_error('Kunne ikke opdatere rater', E_USER_ERROR);
            }
        }
        throw new k_http_Redirect($this->url());

    }
}
