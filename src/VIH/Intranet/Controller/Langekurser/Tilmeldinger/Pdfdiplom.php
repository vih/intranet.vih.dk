<?php
/**
 * Bevis til eleverne når de slutter
 *
 * Formålet er at skrive et diplom til eleverne for opholdet på Vejle
 * Idrætshøjskole. Diplomet skal indeholder følgende:
 *
 * - navn
 * - antal uger
 * - hvad de har lavet her
 * - tidsrum
 * - fagene
 * - forstandernavn og underskrift
 *
 * @author Sune Jensen <sj@sunet.dk>
 */
require_once 'fpdf/fpdf.php';

class VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Pdfdiplom extends k_Controller
{
    function GET()
    {
        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name);

        $forstander_navn = 'Lars Kjærsgaard';
        $navn = $tilmelding->get('navn');
        $mdr = array('januar', 'februar', 'marts', 'april', 'maj', 'juni', 'juli', 'august', 'september', 'oktober', 'november', 'december');
        $dato_start = $tilmelding->get('dato_start_dk_streng');
        $dato_slut = $tilmelding->get('dato_slut_dk_streng');
        // enten speciel fra eleven, hvis den er udfyldt - ellers en standard fra selve kurset

        if ($tilmelding->get('tekst_diplom')) {
        	$overskrift_tekst = $tilmelding->get('ugeantal') . ' ugers ' . $tilmelding->get('tekst_diplom');
        } else {
            $overskrift_tekst = $tilmelding->get('ugeantal') . ' ugers højskoleophold';
        }

        $overskrift_tekst .= " på\nVejle Idrætshøjskole"; // skal være der

        // Array med fag. Deles ligeligt på to kolonner. Ingen opdeling af almene og idræt,
        // men betegnelserne bibeholdes fra tidligere version.

        $fag = $tilmelding->getFag();

        /*
        $fag = array();
        foreach ($fag_id AS $key => $id) {
            $fag[$id->getFag()->getId()] = $id->getFag()->get('navn');

        }
        sort($fag);
        */
        $foo = ceil(sizeof($fag)/2);
        $idraet = array_slice($fag, 0, $foo);
        $almene = array_slice($fag, $foo);

        $margin_left = 30;
        $margin_top = 50;
        $margin_right = 30;

        $pdf = new FPDF();
        $pdf->SetTitle('Diplom');
        $pdf->SetSubject('Diplom fra Vejle Idrætshøjskole');
        $pdf->SetAuthor('Lars Olesen, Vejle Idrætshøjskole');
        $pdf->SetCreator('Lars Olesen, Vejle Idrætshøjskole');
        $pdf->SetDisplayMode('fullpage', 'single');
        $pdf->SetKeywords('Diplom VIH');

        $content_width = $pdf->fw - $margin_left - $margin_right;
        $content_center = $pdf->fw/2;

        $pdf->setMargins($margin_left, $margin_top, $margin_right);
        $pdf->SetAutoPageBreak(0);
        $pdf->addPage();

        $pdf->AddFont('Garamond','','gara.php');
        $pdf->AddFont('Garamond','B','garabd.php');
        $pdf->AddFont('Garamond','I','garait.php');

        $pdf->SetFont('Garamond','',20);
        $pdf->Cell(0, 10, $navn, 0, 2, "C");

        $pdf->SetFontSize(14);
        $pdf->Cell(0, 20, 'har gennemført', 0, 2, "C");

        $pdf->SetFontSize(24);
        $pdf->MultiCell(0, 10, $overskrift_tekst, 0, 'C');

        $pdf->SetFontSize(14);
        $pdf->Cell(0, 20, 'fra ' . $dato_start . ' til ' . $dato_slut, 0, 2, "C");

        if (count($fag) > 20) {
           $pdf->setY(130); // Y hvor kassen starter
           $image_width = $pdf->fw - $margin_left - $margin_right - 10*2;
           $image_height = $image_width * 0.65;
        } else {
           $pdf->setY(140); // Y hvor kassen starter
           $image_width = $pdf->fw - $margin_left - $margin_right - 10*2;
           $image_height = $image_width * 0.5603;
        }
        $pdf->Image(dirname(__FILE__) . '/rektangel2.png', $margin_left + 10, $pdf->y, $image_width, $image_height , "PNG");
        // $pdf->Image("rektangel.png", $margin_left + 10, $pdf->y);
        // $pdf->Rect($pdf->x, $pdf->y, $content_width, 80); // sidste parameter er kassens højde

        if (count($fag) > 20) {
           $pdf->setY(135); // Y hvor indhold i kassen starter
        } elseif (count($fag) > 18) {
           $pdf->setY(145); // Y hvor indhold i kassen starter
        } elseif (count($fag) > 14) {
           $pdf->setY(150); // Y hvor indhold i kassen starter
        } else {
           $pdf->setY(154); // Y hvor indhold i kassen starter
        }

        $pdf->SetFontSize(12);
        for($i = 0; $i < count($almene); $i ++) {
           $pdf->Text($content_center + 3, $pdf->y + 6 + ($i * 6), $almene[$i]->getName());
        }
        for($i =  0; $i < count($idraet); $i ++) {
           $pdf->Text($content_center - 3 - $pdf->getStringWidth($idraet[$i]->getName()), $pdf->y + 6 + ($i * 6), $idraet[$i]->getName());
        }

        $pdf->setY(215); // Y hvor dato sted er
        $pdf->SetFontSize(14);
        $pdf->Cell(0, 20, 'Vejle, ' . $dato_slut, 0, 2, "C");

        $pdf->setY(245); // Y hvor linjen er
        $pdf->Line($content_center - 30, $pdf->y, $content_center + 30, $pdf->y);
        $pdf->setY(245); // Y hvor underskriver er
        $pdf->Cell(0, 6, $forstander_navn, 0, 2, "C");
        $pdf->Cell(0, 6, 'Forstander', 0, 2, "C");

        $pdf->Output();
    }
}