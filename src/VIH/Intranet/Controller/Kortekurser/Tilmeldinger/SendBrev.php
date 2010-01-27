<?php
require_once 'fpdf/fpdf.php';

class VIH_Intranet_Controller_Kortekurser_Tilmeldinger_SendBrev extends k_Component
{
    protected $templates;

    function __construct(k_TemplateFactory $templates)
    {
        $this->templates = $templates;
    }

    function renderHtml()
    {
        $GLOBALS['_global_function_callback_url'] = Array($this, 'url');
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->context->name());

        $allowed_brev_type = array('' => '_fejl_',
           'rykker.php' => 'rykker',
           'depositumrykker.php' => 'depositumrykker',
           'depositum.php' => 'depositum',
           'bekraeftelse.php' => 'bekraeftelse',
           'depositumbekraeftelse.php' => 'depositumbekraeftelse');

        $brev_type = $this->query('type');
        $brev_type_key = array_search($brev_type, $allowed_brev_type);

        if($brev_type_key === false) {
            throw new Exception("Ugyldig brev type");
        }

        include(dirname(__FILE__) . '/breve/'.$brev_type_key);
        // returnerer $brev_tekst;


        $this->document->setTitle('Send '.$brev_type);

        $send_data = array('tilmelding' => $tilmelding,
        				   'brev_tekst' => $brev_tekst,
        				   'brev_type' => $this->query('type'));
        $tpl = $this->templates->create('kortekurser/send_brev');
        return $tpl->render($this, $send_data);
    }

    function renderPdf()
    {
        $GLOBALS['_global_function_callback_url'] = Array($this, 'url');
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->context->name());

        $allowed_brev_type = array('' => '_fejl_',
           'rykker.php' => 'rykker',
           'depositumrykker.php' => 'depositumrykker',
           'depositum.php' => 'depositum',
           'bekraeftelse.php' => 'bekraeftelse',
           'depositumbekraeftelse.php' => 'depositumbekraeftelse');

        $brev_type = $this->query('type');
        $brev_type_key = array_search($brev_type, $allowed_brev_type);

        if($brev_type_key === false) {
            throw new Exception("Ugyldig brev type");
        }

        include(dirname(__FILE__) . '/breve/'.$brev_type_key);
        // returnerer $brev_tekst;


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
           return $pdf->Output();
    }

    function postForm()
    {
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->context->name());

        $allowed_brev_type = array('' => '_fejl_',
           'rykker.php' => 'rykker',
           'depositumrykker.php' => 'depositumrykker',
           'depositum.php' => 'depositum',
           'bekraeftelse.php' => 'bekraeftelse',
           'depositumbekraeftelse.php' => 'depositumbekraeftelse');

        $brev_type = $this->body('type');
        $brev_type_key = array_search($brev_type, $allowed_brev_type);

        if($brev_type_key === false) {
           trigger_error("Ugyldig brev type", E_USER_ERROR);
        }

        include(dirname(__FILE__) . '/breve/'.$brev_type_key);

        if($this->body('send_email')) {
            $mail = new VIH_Email;
            $mail->setSubject(ucfirst($brev_type)." fra Vejle Idrætshøjskole");
            $mail->setBody($brev_tekst);
            $mail->addAddress($tilmelding->get('email'), $tilmelding->get('navn'));
            if(!$mail->send()) {
                trigger_error("Email blev ikke sendt. Der opstod en fejl. Du kan forsøge igen eller kontakte ham den dovne webmaster", E_USER_ERROR);
            }
            $historik = new VIH_Model_Historik('kortekurser', $tilmelding->get("id"));
            $historik->save(array('type' => $brev_type, 'comment' => "Sendt via e-mail"));

            return new k_SeeOther($this->context->url());
        } elseif($this->body('send_pdf')) {
            $historik = new VIH_Model_Historik('kortekurser', $tilmelding->get("id"));
            $historik->save(array('type' => $brev_type, 'comment' => "Sendt via post"));
            return new k_SeeOther($this->url(null . '.pdf', array('type' => $brev_type)));
        }

    }
}