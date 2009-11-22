<?php
require_once 'fpdf/fpdf.php';

class B_FPDF extends FPDF {

    function b_fpdf($a, $b, $c) {
        FPDF::fpdf($a, $b, $c);
    }

    function footer() {
        //Go to 1.5 cm from bottom
        $this->SetY(-20);
        //Select Arial italic 8
        $this->SetFont('Arial','I',8);
        //Print centered page number
        $this->Cell(0,10,'Side '.$this->PageNo()." af {nb}",0,0,'R');
        return true;
    }

    function setY($value) {
        if($value > 0 && $value > $this->fh - 30) {
            $this->addPage();

        } else {
            FPDF::setY($value);
        }
    }
}


class VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Brev extends k_Controller
{
    function GET()
    {
        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->context->name);
        $tilmelding->loadBetaling();
        $historik = new VIH_Model_Historik('langekurser', $tilmelding->get("id"));
        $betalinger = new VIH_Model_Betaling('langekurser', $tilmelding->get("id"));

        if(isset($this->GET['create']) && $this->GET['create'] == 'pdf') {

            $font = 'Arial';
            $size = '12';
            $size_tabel = "10";

            $margin_left = 30;
            $margin_top = 30;
            $margin_right = 30;
            $margin_bottom = 30;

            $line_height = 6;

            $pdf = new B_FPDF('P','mm','A4');
            $pdf->Open();
            $pdf->AddPage();
            $pdf->SetFont($font,'', $size);
            $pdf->setMargins($margin_left, $margin_top, $margin_right);
            // $pdf->SetAutoPageBreak(1, $margin_bottom);
            $pdf->AliasNbPages();

            $content_width = $pdf->fw - $margin_left - $margin_right;

            $pdf->setY(30);

            $modtager = '#' . $tilmelding->get("id") . "\n" . $tilmelding->get("navn")."\n".$tilmelding->get("adresse")."\n".$tilmelding->get("postnr")."  ".$tilmelding->get("postby");
            $pdf->Write(5, $modtager);

            $pdf->setY(50);
            $pdf->Cell(0, 10, "Vejle, " . date('d-m-Y'), '', '', 'R');

            $pdf->setY(80);

            $pdf->SetFont($font,'B', $size);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'Kursus');
            $pdf->SetFont($font,'', $size_tabel);
            // $pdf->Line($pdf->getX(), $pdf->getY(), $pris_width, $pdf->getY());

            $pris_width = $margin_left + $content_width - 30; // Her kan der sættes lidt minus på, for at rykke højremargin længere ind på siden.

            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'Kursus');
            $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->kursus->getKursusNavn()), $pdf->getY(), $tilmelding->kursus->getKursusNavn());
            $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);

            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'Kursusstart');
            $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->kursus->getDateStart()->format('%d-%m-%Y')), $pdf->getY(), $tilmelding->kursus->getDateStart()->format('%d-%m-%Y'));
            $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);

            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'Kursusslut');
            $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->kursus->getDateEnd()->format('%d-%m-%Y')), $pdf->getY(), $tilmelding->kursus->getDateEnd()->format('%d-%m-%Y'));
            $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);

            $pdf->setY($pdf->getY() + 12);

            $pdf->SetFont($font,'B', $size);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'Prisoversigt');
            $pdf->SetFont($font,'', $size_tabel);
            // $pdf->Line($pdf->getX(), $pdf->getY(), $pris_width, $pdf->getY());

            $pris_width = $margin_left + $content_width - 30; // Her kan der sættes lidt minus på, for at rykke højremargin længere ind på siden.

            $pdf->SetFont($font,'', $size_tabel);
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'Tilmeldingsgebyr');
            $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->get('pris_tilmeldingsgebyr')), $pdf->getY(), $tilmelding->get('pris_tilmeldingsgebyr'));
            $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);

            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'Ugepris (' . round($tilmelding->get('pris_uge'), 0) . ' kr * ' . $tilmelding->get('ugeantal') . ' uger)');
            $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->get('pris_uge') * $tilmelding->get('ugeantal')), $pdf->getY(), $tilmelding->get('pris_uge') * $tilmelding->get('ugeantal'));
            $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);

            if ($tilmelding->get('pris_materiale') > 0) {
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), 'Materialegebyr');
                $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->get('pris_materiale')), $pdf->getY(), $tilmelding->get('pris_materiale'));
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);
            }

            if ($tilmelding->get('pris_rejsedepositum') > 0) {
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), 'Rejseforudbetaling');
                $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->get('pris_rejsedepositum')), $pdf->getY(), $tilmelding->get('pris_rejsedepositum'));
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);
            }
            if ($tilmelding->get('pris_rejserest') > 0) {
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), 'Restbeløb til rejse');
                $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->get('pris_rejserest')), $pdf->getY(), $tilmelding->get('pris_rejserest'));
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);
            }
            if ($tilmelding->get('pris_noegledepositum') > 0) {
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), 'Nøgledepositum');
                $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->get('pris_noegledepositum')), $pdf->getY(), $tilmelding->get('pris_noegledepositum'));
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);
            }

            if ($tilmelding->get('pris_afbrudt_ophold') > 0) {
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), 'Afbrudt ophold');
                $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->get('pris_afbrudt_ophold')), $pdf->getY(), $tilmelding->get('pris_afbrudt_ophold'));
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);
            }

            if ($tilmelding->get('aktiveret_tillaeg') > 0) {
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), 'Statsstøtte aktiveret');
                $pdf->Text($pris_width - $pdf->getStringWidth(round($tilmelding->get('aktiveret_tillaeg'))), $pdf->getY(), round($tilmelding->get('aktiveret_tillaeg')));
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);
            }

            if ($tilmelding->get('statsstotte') > 0) {
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), '- Indvandrerstøtte');
                $pdf->Text($pris_width - $pdf->getStringWidth('- ' . $tilmelding->get('statsstotte') * $tilmelding->get('ugeantal')), $pdf->getY(), '- ' . $tilmelding->get('statsstotte') * $tilmelding->get('ugeantal'));
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);
            }

            if ($tilmelding->get('kompetencestotte') > 0) {
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), '- Kompetencestøtte');
                $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->get('kompetencestotte') * $tilmelding->get('ugeantal')), $pdf->getY(), $tilmelding->get('kompetencestotte') * $tilmelding->get('ugeantal'));
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);
            }

            if ($tilmelding->get('elevstotte') > 0) {
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), '- Elevstøtte');
                $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->get('elevstotte') * $tilmelding->get('ugeantal')), $pdf->getY(), $tilmelding->get('ugeantal_elevstotte') * $tilmelding->get('elevstotte'));
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);
            }

            if ($tilmelding->get('kommunestotte') > 0) {
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), '- Kommunestøtte');
                $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->get('kommunestotte')), $pdf->getY(), $tilmelding->get('kommunestotte'));
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);
            }

            $pdf->SetFont($font,'B', $size_tabel);
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'I alt');
            $pdf->Text($pris_width - $pdf->getStringWidth($tilmelding->get("pris_total")), $pdf->getY(), $tilmelding->get("pris_total"));
            $pdf->Line($pdf->getX(), $pdf->getY() + 2, $pris_width, $pdf->getY() + 2);

            $pdf->setY($pdf->getY() + $line_height);

            $pdf->SetFont($font,'B', $size);
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'Betalingsrater');
            $pdf->SetFont($font,'', $size_tabel);

            $betalt = '';
            $rater_samlet = 0;
            $rater_samlet = $tilmelding->get('pris_tilmeldingsgebyr');

            $rater_1 = 75;
            $rater_2 = 150;
            $rater_3 = $content_width + $margin_left;

            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), $tilmelding->get("date_created_dk"));
            $pdf->Text($rater_1, $pdf->getY(), "Tilmeldingsgebyr");
            $pdf->Text($rater_2 - $pdf->getStringWidth(number_format($tilmelding->get('pris_tilmeldingsgebyr'), 2, ',', '.')), $pdf->getY(), number_format($tilmelding->get('pris_tilmeldingsgebyr'), 2, ',', '.'));
            if($tilmelding->get('betalt') >= $rater_samlet) {
                $pdf->Text($rater_3 - $pdf->getStringWidth("Betalt"), $pdf->getY(), "Betalt");
            }
            $pdf->Line($pdf->getX(), $pdf->getY() + 2, $rater_3, $pdf->getY() + 2);

            $i = 1;
            foreach ($tilmelding->getRater() AS $rate) {
                $rater_samlet += $rate["beloeb"];
                $betalt = '';
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), $rate['dk_betalingsdato']);
                $pdf->Text($rater_1, $pdf->getY(), $i.". rate");
                $pdf->Text($rater_2 - $pdf->getStringWidth(number_format($rate['beloeb'], 2, ',', '.')), $pdf->getY(), number_format($rate['beloeb'], 2, ',', '.'));
                if($tilmelding->get('betalt') >= $rater_samlet) {
                    $pdf->Text($rater_3 - $pdf->getStringWidth("Betalt"), $pdf->getY(), "Betalt");
                }
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $rater_3, $pdf->getY() + 2);
                $i++;
            }
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->SetFont($font,'B', $size);
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'Betalinger');
            $pdf->SetFont($font,'', $size_tabel);
            $betaling_width = $margin_left + $content_width;
            foreach ($betalinger->getList() AS $betaling) {
                $pdf->setY($pdf->getY() + $line_height);
                $pdf->Text($pdf->getX(), $pdf->getY(), $betaling->get('date_created_dk'));
                $pdf->Text($betaling_width - $pdf->getStringWidth(number_format($betaling->get('amount'), 2, ',', '.')), $pdf->getY(), number_format($betaling->get('amount'), 2, ',', '.'));
                $pdf->Line($pdf->getX(), $pdf->getY() + 2, $betaling_width, $pdf->getY() + 2);
            }

            $pdf->SetFont($font,'B', $size_tabel);
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), "Samlet betalt");
            $pdf->Text($betaling_width - $pdf->getStringWidth(number_format($tilmelding->get("betalt"), 2, ',', '.')), $pdf->getY(), number_format($tilmelding->get("betalt"), 2, ',', '.'));
            $pdf->Line($pdf->getX(), $pdf->getY() + 2, $betaling_width, $pdf->getY() + 2);
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), "Restance");
            $pdf->Text($betaling_width - $pdf->getStringWidth(number_format($tilmelding->get("saldo"), 2, ',', '.')), $pdf->getY(), number_format($tilmelding->get("saldo"), 2, ',', '.'));
            $pdf->Line($pdf->getX(), $pdf->getY() + 2, $betaling_width, $pdf->getY() + 2);
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->SetFont($font,'B', $size);
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'Betalingsoplysninger');
            $pdf->SetFont($font,'', $size_tabel);
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), "Du kan indbetale raterne ved kontooverførsel til vores konto i Jyske Bank: 7244-1469664");
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), "eller vha. Dankort på " . LANGEKURSER_LOGIN_URI . $tilmelding->get('code'));
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), "(husk forskel på små og store bogstaver)");
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->SetFont($font,'B', $size);
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), 'Bemærkninger');
            $pdf->SetFont($font,'', $size_tabel);
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), "Bemærk at individuel supplerende elevstøtte og kommunestøtte er skattepligtig B-indkomst.");
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), "I henhold til lovgivningen trækkes tilsagn om kommunestøtte, indvandrerstøtte og kompetencestøtte");
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), "tilbage, hvis eleven ikke gennemfører mindst 12 uger af et kursusforløb. Dette beløb skal altså");
            $pdf->setY($pdf->getY() + $line_height);
            $pdf->Text($pdf->getX(), $pdf->getY(), "efterbetales ved afbrydelse af kursus i utide.");
            $pdf->Output();
            exit;
        }

        if(isset($this->GET['send_pdf'])) {
            $historik = new VIH_Model_Historik('langekurser', $tilmelding->get("id"));
            $historik->save(array('type' => 'betalingsopgørelse', 'comment' => "Sendt via post"));
            throw new k_http_Redirect($this->context->url(null, array('download_file' => urlencode($this->url(null, array('create' => 'pdf'))))));
        }

        $opl_data = array('caption' => 'Tilmeldingsoplysninger',
                          'tilmelding' =>    $tilmelding);

        $pris_data = array('tilmelding' => $tilmelding);

        $rater_data = array('tilmelding' => $tilmelding);

        $betal_data = array('betalinger' => $betalinger->getList(),
                            'caption' => 'Betalinger',
                            'msg_ingen' => '<h2>Betalinger</h2><p>Der er endnu ikke foretaget nogen betalinger.</p>');


        $this->document->title = 'Betalingsoversigt';

        if ($tilmelding->antalRater() > 0 AND $tilmelding->rateDifference() > 0) {
            return '
                <h1>Betalingsopgørelsen kan kun når raterne stemmer</h1>
                <p>Du skal lige rette raterne til, inden du kan lave en betalingsoversigt.</p>
                <p><a href="'.$this->context->url('rater').'">Ret rater &rarr;</a></p>
            ';
        } elseif ($tilmelding->antalRater() > 0) {
            return '
                <h1>Betalingsopgørelse</h1>
                <p><a href="'.$this->url(null, array('send_pdf' => true)).'">Send brev</a>'
                . $this->render('vih/intranet/view/langekurser/tilmelding/oplysninger-tpl.php', $opl_data)
                . $this->render('vih/intranet/view/langekurser/tilmelding/prisoversigt-tpl.php', $pris_data)
                . $this->render('vih/intranet/view/langekurser/tilmelding/rater-tpl.php', $rater_data)
                . $this->render('vih/intranet/view/tilmelding/betalinger-tpl.php', $betal_data);
        } else {
            return '
                <h1>Betalingsopgørelsen mangler oplysninger om rater</h1>
                <p>Du skal først have oprettet rater til tilmeldingen, inden du kan lave en betalingsoversigt.</p>
                <p><a href="'.$this->url('../../../' . $tilmelding->getKursus()->get('id') . '/rater').'">Opret rater &rarr;</a></p>
            ';
        }

    }
}