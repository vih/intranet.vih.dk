<?php
class VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Show extends k_Controller
{
    function GET()
    {
        if (!empty ($this->GET['get_prices']) AND $this->GET['get_prices']) {
            $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->GET['get_prices']);
            if (!$tilmelding->getPriserFromKursus()) {
                throw new Exception('Tilmeldingen kunne ikke slettes');
            } else {
                throw new k_http_Redirect($this->url());
            }
        }
        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->name);
        if ($tilmelding->get('id') == 0) {
            throw new k_http_Response(404);
        }

        $historik = new VIH_Model_Historik('langekurser', $tilmelding->get("id"));
        $betalinger = new VIH_Model_Betaling('langekurser', $tilmelding->get("id"));

        $rater = $tilmelding->getRater();

        if (!empty($this->GET['action']) AND $this->GET['action'] == 'sendemail') {
            if ($tilmelding->sendEmail()) {
                if (!$historik->save(array('type' => 'kode', 'comment' => 'Kode sendt med e-mail'))) {
                    throw new Exception('Historikken kunne ikke gemmes');
                }
            } else {
                throw new Exception('E-mailen kunne ikke sendes');
            }
        } elseif (!empty($this->GET['action']) AND $this->GET['action'] == 'opretrater') {
            if (!$tilmelding->opretRater()) {
                throw new Exception('Raterne kunne ikke oprettes');
            } else {
                throw new k_http_Redirect($this->url());
            }
        } elseif(!empty($this->GET['registrer_betaling'])) {
            if($betalinger->save(array('type' => 'giro', 'amount' => $this->GET['beloeb']))) {
                $betalinger->setStatus('approved');
            } else {
                throw new Exception("Betalingen kunne ikke gemmes. Det kan skyldes et ugyldigt beløb", E_USER_ERROR);
            }
        } elseif(isset($this->GET['slet_historik_id'])) {
            $historik = new VIH_Model_Historik(intval($this->GET['slet_historik_id']));
            $historik->delete();
        }

        $tilmelding->loadBetaling();

        $this->document->title = 'Tilmelding';

        $opl_data = array('tilmelding' => $tilmelding);

        $pris_data = array('tilmelding' => $tilmelding);

        $betal_data = array('betalinger' => $betalinger->getList('not_approved'),
                            'caption' => 'Betalinger');

        $hist_data = array('tilmelding' => $tilmelding,
                           'historik' => $historik->getList());

        $data = array('tilmelding' => $tilmelding,
                      'oplysninger' => $this->render('vih/intranet/view/langekurser/tilmelding/oplysninger-tpl.php', $opl_data),
                      'prisoversigt' => $this->render('vih/intranet/view/langekurser/tilmelding/prisoversigt-tpl.php', $pris_data),
                      'betalinger' => $this->render('vih/intranet/view/tilmelding/betalinger-tpl.php', $betal_data),
                      'historik' => $this->render('vih/intranet/view/tilmelding/historik-tpl.php', $hist_data));

        // rater
        if (count($rater) > 0) {
            $rater_tpl = $this->registry->get('template');
            $rater_tpl->set('tilmelding', $tilmelding);
            $data['rater'] = $rater_tpl->fetch('langekurser/tilmelding/rater-tpl.php');
        } else {
            if ($tilmelding->kursus->antalRater() > 0) {
                $data['rater'] = '<p><a href="'.$this->url(null, array('get_prices' => $tilmelding->get('id'))).'">Hent priserne fra kurset</a>. Der er endnu ikke oprettet nogen rater <a href="'.$this->url(null, array('action' => 'opretrater')) . '">Opret &rarr;</a></p>';
            } else {
                $data['rater'] = '<p>Der er endnu ikke oprettet rater på selve kurset. Dem skal du lige oprette først <a href="'.$this->url('../../'.$tilmelding->getKursus()->get('id').'/rater').'">Opret &rarr;</a></p>';
            }
        }

        $data['message'] = '';

        if(isset($this->GET['download_file']) && $this->GET['download_file'] != "") {
            $data['message'] = '
                <div id="download_file">
                    <strong>Download:</strong> <a href="' . urldecode($this->GET['download_file']) . '">Hent fil</a> (<a href="' . urldecode($_GET['download_file']) . '">I dette vindue</a>)
                </div>
            ';
        }

        return $this->render('vih/intranet/view/langekurser/tilmelding-tpl.php', $data);

    }

    function forward($name)
    {
        if ($name == 'rater') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Rater($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'fag') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Fag($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'brev') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Brev($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'diplom') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Pdfdiplom($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'edit') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'delete') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Delete($this, $name);
            return $next->handleRequest();
        }
    }
}