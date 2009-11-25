<?php
require_once 'fpdf/fpdf.php';

class VIH_Intranet_Controller_Kortekurser_Lister_Adresselabels extends k_Component
{
    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus((int)$this->context->name());
        $deltagere = $kursus->getTilmeldinger();

        $data = $this->printAddressLabels($deltagere);

        // hack ...
        $pdf=new FPDF();
        $pdf->Open();
        $pdf->AddPage();
        $pdf->Cell(10, 10, 'text');
        $data = $pdf->Output();

        $response = new k_http_Response(200, $data);
        $response->setEncoding(NULL);
        $response->setContentType("application/pdf");

        $response->setHeader("Content-Length", strlen($data));
        $response->setHeader("Content-Disposition", "attachment;filename=\"adresselabels.pdf\"");
        $response->setHeader("Content-Transfer-Encoding", "binary");
        $response->setHeader("Cache-Control", "Public");
        $response->setHeader("Pragma", "public");

        throw $response;
    }

    function Avery7160($x, $y, &$pdf, $Data)
    {
        $LeftMargin = 6.0;
        $TopMargin = 12.7;
        $LabelWidth = 63.5;
        $LabelHeight = 39.6;
        // Create Co-Ords of Upper left of the Label
        $AbsX = $LeftMargin + (($LabelWidth + 4.22) * $x);
        $AbsY = $TopMargin + ($LabelHeight * $y);

        // Fudge the Start 3mm inside the label to avoid alignment errors
        $pdf->SetXY($AbsX+3,$AbsY+3);
        $pdf->MultiCell($LabelWidth-8,4.5,$Data);
    }

    function PrintAddressLabels($deltagere)
    {
        $rows = 7;

        $pdf = new FPDF();
        $pdf->Open();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',10);
        $pdf->SetMargins(0,0);
        $pdf->SetAutoPageBreak(false);

        $x = 0;
        $y = 0;

        foreach ($deltagere AS $row) {
            $LabelText = sprintf(
                "%s\n%s\n%s",
                $row->get('navn'),
                $row->get('adresse'),
                $row->get('postnr') . '  ' . $row->get('postby')
            );

            $this->Avery7160($x,$y,$pdf,$LabelText);

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

