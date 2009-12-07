<?php
/*
require('../include_intranet.php');
require('fpdf/fpdf.php');
*/
/**
 * Skriver labels ud på BrotherQL500
 *
 * @author Lars Olesen <lars@legestue.net>
 */
/*
function BrotherQL500($x, $y, &$pdf, $address, $interest) {
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

$bestilling = new VIH_Model_MaterialeBestilling;
$bestillinger = $bestilling->getList();

$pdf=new FPDF('L', 'mm', array(29, 90));
$pdf->Open();
$pdf->SetMargins(0,0);
$pdf->SetAutoPageBreak(false);

// sætter startpunkterne for label
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
    BrotherQL500($x,$y,$pdf,$LabelText, $interest);

    $bestil = new VIH_Model_MaterialeBestilling($row['id']);

}

$pdf->Output();
*/
?>