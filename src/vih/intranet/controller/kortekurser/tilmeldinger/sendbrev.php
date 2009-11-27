<?php
require_once 'fpdf/fpdf.php';

class VIH_Intranet_Controller_KorteKurser_Tilmeldinger_SendBrev extends k_Controller
{
    function GET()
    {

        $GLOBALS['_global_function_callback_url'] = Array($this, 'url');
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->context->name);

        $allowed_brev_type = array('' => '_fejl_',
           'rykker.php' => 'rykker',
           'depositumrykker.php' => 'depositumrykker',
           'depositum.php' => 'depositum',
           'bekraeftelse.php' => 'bekraeftelse',
           'depositumbekraeftelse.php' => 'depositumbekraeftelse');

        $brev_type = $this->GET['type'];
        $brev_type_key = array_search($brev_type, $allowed_brev_type);

        if($brev_type_key === false) {
           trigger_error("Ugyldig brev type", E_USER_ERROR);
        }

        if($tilmelding->get('id') == 0) {
           trigger_error("Ugyldig tilmelding", E_USER_ERROR);
        }

        include(dirname(__FILE__) . '/breve/'.$brev_type_key);
        // returnerer $brev_tekst;


        if(isset($this->GET['create']) && $this->GET['create'] == 'pdf') {

           $pdf=new FPDF('P','mm','A4');
           $pdf->Open();
           $pdf->AddPage();
           $pdf->SetFont('Arial','',12);
           $pdf->SetMargins(30,30);
           $pdf->SetAutoPageBreak(false);

           $pdf->setY(30);

           $modtager = $tilmelding->get("navn")."\n".$tilmelding->get("adresse")."\n".$tilmelding->get("postnr")."  ".$tilmelding->get("postby");
           $pdf->Write(5, $modtager);

           $pdf->setY(70);
           $pdf->Cell(0, 10, "Vejle, " . date('d-m-Y'), '', '', 'R');

           $pdf->setY(100);

           $pdf->Write(5, $brev_tekst);
           $pdf->Output(dirname(__FILE__) .'/udsendte_pdf/' . $tilmelding->get('id') . '.pdf', 'F');

           # workaround for at få IE til at forstå noget.
           throw new k_http_Redirect($this->url('/kortekurser/tilmeldinger/udsendte_pdf/'.$tilmelding->get('id').'.pdf'));
        }

        $this->document->title = 'Send brev';

        $send_tpl = $this->registry->get('template');
        $send_tpl->set('tilmelding', $tilmelding);
        $send_tpl->set('brev_tekst', $brev_tekst);
        $send_tpl->set('brev_type', $this->GET['type']);
        $send_tpl->set('overskrift', 'Send '.$brev_type);
        return $send_tpl->fetch('kortekurser/send_brev-tpl.php');
    }

    function POST()
    {
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->context->name);

        $allowed_brev_type = array('' => '_fejl_',
           'rykker.php' => 'rykker',
           'depositumrykker.php' => 'depositumrykker',
           'depositum.php' => 'depositum',
           'bekraeftelse.php' => 'bekraeftelse',
           'depositumbekraeftelse.php' => 'depositumbekraeftelse');

        $brev_type = $this->POST['type'];
        $brev_type_key = array_search($brev_type, $allowed_brev_type);

        if($brev_type_key === false) {
           trigger_error("Ugyldig brev type", E_USER_ERROR);
        }

        include(dirname(__FILE__) . '/breve/'.$brev_type_key);

        if(isset($this->POST['send_email'])) {
            $mail = new VIH_Email;
            $mail->setSubject(ucfirst($brev_type)." fra Vejle Idrætshøjskole");
            $mail->setBody($brev_tekst);
            $mail->addAddress($tilmelding->get('email'), $tilmelding->get('navn'));
            if(!$mail->send()) {
                trigger_error("Email blev ikke sendt. Der opstod en fejl. Du kan forsøge igen eller kontakte ham den dovne webmaster", E_USER_ERROR);
            }
            $historik = new VIH_Model_Historik('kortekurser', $tilmelding->get("id"));
            $historik->save(array('type' => $brev_type, 'comment' => "Sendt via e-mail"));

            throw new k_http_Redirect($this->context->url());
        } elseif(isset($this->POST['send_pdf'])) {

            $historik = new VIH_Model_Historik('kortekurser', $tilmelding->get("id"));
            $historik->save(array('type' => $brev_type, 'comment' => "Sendt via post"));
            throw new k_http_Redirect($this->context->url(null, array('download_file' => $this->url('sendbrev'), 'type' => $brev_type, 'create' => 'pdf')));
        }

    }
}