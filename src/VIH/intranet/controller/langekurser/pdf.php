<?php
/**
 * @author Lars Olesen <lars@legestue.net>
 * @require fpdf-library <http://www.fpdf.org>
 *
 * Udskydning skal være 4, 1, 2, 3
 *
 * +-----+-----+
 * |     |     |
 * |  3  |  0  |
 * |     |     |
 * +-----+-----+
 * +-----+-----+
 * |     |     |
 * |  1  |  2  |
 * |     |     |
 * +-----+-----+
 *
 */
/*
if (empty($_GET['id']) OR !is_numeric($_GET['id'])) {
    die('Ingen adgang');
}

require_once '../include_intranet.php';

$kursus = new VIH_Model_LangtKursus($_GET['id']);

# beskrivelsen skal deles op og regnes ud, hvor meget, der kan være på hver side.
# det der ikke kan være på midtersiderne skal være på bagsiden.

$pdf = new VIH_PdfBrochure();
$pdf->SetTitle($kursus->get('navn'));
$pdf->SetSubject('Kursusbeskrivelse: ' . $kursus->get('navn'));
$pdf->SetAuthor('Lars Olesen, Vejle Idrætshøjskole');
$pdf->SetCreator('Lars Olesen, Vejle Idrætshøjskole');
$pdf->SetDisplayMode('fullpage', 'two');
$pdf->SetKeywords('keyword');
$pdf->VIHContent($kursus->get('beskrivelse'));
$pdf->Output();
*/
?>
