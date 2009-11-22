<?php
class VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Show extends k_Controller
{
    function GET()
    {
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->name);

        if (isset($this->GET['sletdeltager']) AND is_numeric($this->GET['sletdeltager'])) {
            $deltager = new VIH_Model_KortKursus_Tilmelding_Deltager($tilmelding, $this->GET['sletdeltager']);
            $deltager->delete();
        } elseif (!empty($this->GET['action']) AND $this->GET['action'] == 'sendemail') {
            if ($tilmelding->sendEmail()) {
                $historik = new VIH_Model_Historik('kortekurser', $tilmelding->get('id'));
                if (!$historik->save(array('type' => 'kode', 'comment' => 'Kode sendt med e-mail'))) {
                    throw new Exception('Historikken kunne ikke gemmes');
                }
            } else {
                throw new Exception('E-mailen kunne ikke sendes');
            }
        }

        if(isset($this->GET['slet_historik_id'])) {
            $historik = new VIH_Model_Historik(intval($this->GET['slet_historik_id']));
            $historik->delete();
        }

        $deltagere = $tilmelding->getDeltagere();
        $historik = new VIH_Model_Historik('kortekurser', $tilmelding->get("id"));
        $betalinger = new VIH_Model_Betaling('kortekurser', $tilmelding->get("id"));

        if(!empty($this->GET['registrer_betaling'])) {
            if($betalinger->save(array('type' => 'giro', 'amount' => $_GET['beloeb']))) {
                $betalinger->setStatus('approved');
                throw new k_http_Redirect($this->url());
            } else {
                throw new Exception("Betalingen kunne ikke gemmes. Det kan skyldes et ugyldigt beløb");
            }
        }

        $tilmelding->loadBetaling();

        $this->document->title = 'Tilmelding #' . $tilmelding->getId();

        $tilm_tpl = $this->registry->get('template');
        $tilm_data = array('message', '');
        if(isset($this->GET['download_file']) && $this->GET['download_file'] != "") {
            $tilm_tpl->set('message', '
                <div id="download_file">
                    <strong>Download:</strong> <a href="' . $this->url('sendbrev', array('create' => 'pdf', 'type' => $this->GET['type'])) . '">Hent fil</a>
                </div>
            ');
        }

        $data =   array('deltagere' => $deltagere,
        				'indkvartering' => $tilmelding->kursus->get('indkvartering'),
        				'type' => $tilmelding->get('keywords'),
        				'vis_slet' => 'ja');

        $historik = array('historik' => $historik->getList(),
        				  'tilmelding' => $tilmelding);

        $betaling_tpl = $this->registry->get('template');
        $betaling_tpl->set('caption', 'Afventende betalinger');
        $betaling_tpl->set('betalinger', $betalinger->getList('not_approved'));
        $betaling_tpl->set('msg_ingen', 'Der er ingen afventende betalinger.');

        $prisoversigt_tpl = $this->registry->get('template');
        $prisoversigt_tpl->set('tilmelding', $tilmelding);


        $tilmelding = array('tilmelding' => $tilmelding,
        					'historik_object' => $historik,
        					'deltagere' => $this->render(dirname(__FILE__) . '/../../../view/kortekurser/deltagere-tpl.php', $data),
        					'status' => $tilmelding->get('status'),
                            'prisoversigt' => $prisoversigt_tpl->fetch('kortekurser/tilmelding/prisoversigt-tpl.php'),
        					'historik' => $this->render(dirname(__FILE__) . '/../../../view/tilmelding/historik-tpl.php', $historik),
        					'betalinger'=> $betaling_tpl->fetch('tilmelding/betalinger-tpl.php'));
        return $this->render(dirname(__FILE__) . '/../../../view/kortekurser/tilmelding-tpl.php', $tilmelding);

        /*
        $tpl->set('content_sub', '
            <div class="status">
                ' . ucfirst($status) . '
            </div>
        ');
        */

    }

    function POST()
    {
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->name);
        if(!empty($_POST['annuller_tilmelding'])) {
            $tilmelding->setStatus("annulleret");
        }
        throw new k_http_Redirect($this->url());
    }

    function forward($name)
    {
        if ($name == 'edit') {
            $next = new VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'delete') {
            $next = new VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Delete($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'sendbrev') {
            $next = new VIH_Intranet_Controller_Kortekurser_Tilmeldinger_SendBrev($this, $name);
            return $next->handleRequest();
        }
    }
}
?>