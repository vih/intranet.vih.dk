<?php
/**
 * Diploma
 *
 * - name
 * - weeks
 * - activities
 * - date period
 * - subjects
 * - headmaster and signature
 *
 * @author Sune Jensen <sj@sunet.dk>
 */
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Pdfdiplom extends k_Component
{
    protected $fpdf;

    function __construct(FPDF $fpdf)
    {
        $this->fpdf = $fpdf;
    }

    function renderHtml()
    {
        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name());

        $forstander_navn = 'Ole Damgaard';
        $navn = $tilmelding->get('navn');
        $mdr = array('januar', 'februar', 'marts', 'april', 'maj', 'juni', 'juli', 'august', 'september', 'oktober', 'november', 'december');
        $dato_start = $tilmelding->get('dato_start_dk_streng');
        $dato_slut = $tilmelding->get('dato_slut_dk_streng');

        if ($tilmelding->get('tekst_diplom')) {
        	$overskrift_tekst = $tilmelding->get('ugeantal') . ' ugers ' . $tilmelding->get('tekst_diplom');
        } else {
            $overskrift_tekst = $tilmelding->get('ugeantal') . ' ugers højskoleophold';
        }

        $overskrift_tekst .= " på\nVejle Idrætshøjskole"; // skal v�re der

        // array with subjects

        $fag = $tilmelding->getFag();

        $foo = ceil(sizeof($fag)/2);
        $idraet = array_slice($fag, 0, $foo);
        $almene = array_slice($fag, $foo);

        $margin_left = 30;
        $margin_top = 50;
        $margin_right = 30;

        $pdf = $this->fpdf;
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
        // $pdf->Rect($pdf->x, $pdf->y, $content_width, 80); // sidste parameter er kassens h�jde

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