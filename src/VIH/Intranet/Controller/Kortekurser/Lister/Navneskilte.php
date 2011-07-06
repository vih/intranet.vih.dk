<?php
class VIH_Intranet_Controller_Kortekurser_Lister_Navneskilte extends k_Component
{
    protected $fpdf;

    function __construct(FPDF $fpdf)
    {
        $this->fpdf = $fpdf;
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name());

        $deltagere = $kursus->getDeltagere();

        $data = $this->printAddressLabels($deltagere);

        $response = new k_HttpResponse(200, $data);
        $response->setEncoding(NULL);
        $response->setContentType("application/pdf");

        $response->setHeader("Content-Length", strlen($data));
        $response->setHeader("Content-Disposition", "attachment;filename=\"navneskilte.pdf\"");
        $response->setHeader("Content-Transfer-Encoding", "binary");
        $response->setHeader("Cache-Control", "Public");
        $response->setHeader("Pragma", "public");

        throw $response;
    }

    function Avery7160($x, $y, $navn, $kursus)
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
        $this->fpdf->SetXY($AbsX+3,$AbsY+4);
        $this->fpdf->SetFont('Arial','',16);
        $this->fpdf->Cell($LabelWidth-8, 2.25, $navn, 0, 0, "C");
        $this->fpdf->SetFont('Arial','',8);
        $this->fpdf->SetXY($AbsX+3,$AbsY+8);
        $this->fpdf->Cell($LabelWidth-8, 2.25, $kursus, 0, 0, "C");
        $this->fpdf->Image(dirname(__FILE__) . "/logo.jpg", $PicX,$PicY, 38);
    }

    function PrintAddressLabels($deltagere)
    {
        $rows = 7;

        $this->fpdf->Open();
        $this->fpdf->AddPage();
        $this->fpdf->SetFont('Arial','',12);
        $this->fpdf->SetMargins(0,0);
        $this->fpdf->SetAutoPageBreak(false);

        $x = 0;
        $y = 0;
        foreach ($deltagere as $row) {
            $this->Avery7160($x, $y, $row->get('navn'), $row->tilmelding->kursus->get('navn') . ', uge ' . $row->tilmelding->kursus->get('uge'));

            $y++; // next row
            if ($y == $rows) { // end of page wrap to next column
                $x++;
                $y = 0;
                if ($x == 3 ) { // end of page
                    $x = 0;
                    $y = 0;
                    $this->fpdf->AddPage();
                }
            }
        }
        $this->fpdf->Output();
    }
}
