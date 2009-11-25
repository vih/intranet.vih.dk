<?php
require_once 'fpdf/fpdf.php';

class VIH_Intranet_Controller_Kortekurser_Lister_Navneskilte extends k_Component
{
    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name());

        $deltagere = $kursus->getDeltagere();

        $data = $this->printAddressLabels($deltagere);

        $response = new k_http_Response(200, $data);
        $response->setEncoding(NULL);
        $response->setContentType("application/pdf");

        $response->setHeader("Content-Length", strlen($data));
        $response->setHeader("Content-Disposition", "attachment;filename=\"navneskilte.pdf\"");
        $response->setHeader("Content-Transfer-Encoding", "binary");
        $response->setHeader("Cache-Control", "Public");
        $response->setHeader("Pragma", "public");

        throw $response;
    }

    function Avery7160($x, $y, &$pdf, $navn, $kursus)
    {
        $LeftMargin = 6.0;
        $TopMargin = 12.7;
        $LabelWidth = 63.5;
        $LabelHeight = 39.1;
        // Create Co-Ords of Upper left of the Label
        $AbsX = $LeftMargin + (($LabelWidth + 4.22) * $x);
        $AbsY = $TopMargin + ($LabelHeight * $y) +10.0+10;

        $PicX = $LeftMargin + (($LabelWidth + 4.22) * $x) +12;
        $PicY = $TopMargin + ($LabelHeight * $y) +5;

        // Fudge the Start 3mm inside the label to avoid alignment errors
        $pdf->SetXY($AbsX+3,$AbsY+4);
        $pdf->SetFont('Arial','',16);
        $pdf->Cell($LabelWidth-8, 2.25, $navn, 0, 0, "C");
        $pdf->SetFont('Arial','',8);
        $pdf->SetXY($AbsX+3,$AbsY+8);
        $pdf->Cell($LabelWidth-8, 2.25, $kursus, 0, 0, "C");
        $pdf->Image(dirname(__FILE__) . "/logo.jpg", $PicX,$PicY, 38);
    }

    function PrintAddressLabels($deltagere)
    {
        $rows = 7;

        $pdf=new FPDF();
        $pdf->Open();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',12);
        $pdf->SetMargins(0,0);
        $pdf->SetAutoPageBreak(false);

        $x = 0;
        $y = 0;
        foreach ($deltagere as $row) {
            $this->Avery7160($x,$y,$pdf,$row->get('navn'), $row->tilmelding->kursus->get('navn') . ', uge ' . $row->tilmelding->kursus->get('uge'));

            $y++; // next row
            if ($y == $rows) { // end of page wrap to next column
                $x++;
                $y = 0;
                if ($x == 3 ) { // end of page
                    $x = 0;
                    $y = 0;
                    $pdf->AddPage();
                }
            }

        }
        $pdf->Output();
    }

}

// http://www.webfrustration.com/archive/98/2003/11/4/139108


