<?php
class VIH_Intranet_Controller_Materialebestilling_Index extends k_Component
{
    protected $template;
    protected $fpdf;

    function __construct(FPDF $fpdf, k_TemplateFactory $template)
    {
        $this->template = $template;
        $this->fpdf = $fpdf;
    }

    function renderHtml()
    {
        if ($this->query('sent')) {
            $bestilling = new VIH_Model_MaterialeBestilling((int)$this->query('sent'));
            $bestilling->setSent();
        }

        $bestilling = new VIH_Model_MaterialeBestilling;

        if ($this->query('filter')) {
            $bestillinger = $bestilling->getList($this->query('filter'));
        } else {
            $bestillinger = $bestilling->getList();
        }

        $this->document->setTitle('Materialebestilling');
        $this->document->addOption('Alle', $this->url(null, array('filter' => 'all')));

        $data = array('headline' => 'Materialebestilling',
                      'bestillinger' => $bestillinger);

        $tpl = $this->template->create('materialebestilling/index');
        return $tpl->render($this, $data);
    }

    function renderPdf()
    {
        $bestilling = new VIH_Model_MaterialeBestilling;
        $bestillinger = $bestilling->getList();

        $pdf=$this->fpdf;
        $pdf->Open();
        $pdf->SetMargins(0,0);
        $pdf->SetAutoPageBreak(false);

        $x = 0;
        $y = 0;

        foreach ($bestillinger AS $row) {
            $pdf->AddPage();
            $LabelText = sprintf("%s\n%s\n%s",
                $row['navn'],
                $row['adresse'],
                $row['postnr'] . ' ' . $row['postby']
            );

            $interest = '';
            if (isset($row['langekurser'])) {
                $interest .= 'LK';
            }
            if (isset($row['kortekurser'])) {
                $interest .= ' KK';
            }
            if (isset($row['kursuscenter'])) {
                $interest .= ' KC';
            }
            $this->BrotherQL500($x,$y,$pdf,$LabelText, $interest);

            $bestil = new VIH_Model_MaterialeBestilling($row['id']);
        }

        $pdf->Output();
    }

    function BrotherQL500($x, $y, &$pdf, $address, $interest)
    {
        // indstillinger for label
        $LeftMargin = 6.0;
        $TopMargin = 4.7;
        $LabelWidth = 63.5;
        $LabelHeight = 39.6;
        // Create Co-Ords of Upper left of the Label
        $AbsX = $LeftMargin + (($LabelWidth + 4.22) * $x);
        $AbsY = $TopMargin + ($LabelHeight * $y);

        // Fudge the Start 3mm inside the label to avoid alignment errors
        $pdf->SetXY($AbsX+3,$AbsY+2);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell($LabelWidth-8,4.5,$address);
        $pdf->SetFont('Arial','',5);
        $pdf->Cell($LabelWidth+6,4.5,$interest, 0, 1, 'R');

        return 1;
    }

}
