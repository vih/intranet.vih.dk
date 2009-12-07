<?php
class VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Show extends k_Component
{
    private $template;
    protected $templates;

    function __construct(Template $template, k_TemplateFactory $templates)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->name());

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
                throw new k_SeeOther($this->url());
            } else {
                throw new Exception("Betalingen kunne ikke gemmes. Det kan skyldes et ugyldigt beløb");
            }
        }

        $tilmelding->loadBetaling();

        $this->document->setTitle('Tilmelding #' . $tilmelding->getId());

        $tilm_tpl = $this->template;
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

        $betaling_tpl = $this->template;
        $betaling_tpl->set('caption', 'Afventende betalinger');
        $betaling_tpl->set('betalinger', $betalinger->getList('not_approved'));
        $betaling_tpl->set('msg_ingen', 'Der er ingen afventende betalinger.');

        $prisoversigt_tpl = $this->template;
        $prisoversigt_tpl->set('tilmelding', $tilmelding);


        $tilmelding = array('tilmelding' => $tilmelding,
        					'historik_object' => $historik,
        					'deltagere' => $this->render(dirname(__FILE__) . '/../../../view/kortekurser/deltagere-tpl.php', $data),
        					'status' => $tilmelding->get('status'),
                            'prisoversigt' => $prisoversigt_tpl->fetch('kortekurser/tilmelding/prisoversigt-tpl.php'),
        					'historik' => $this->render(dirname(__FILE__) . '/../../../view/tilmelding/historik-tpl.php', $historik),
        					'betalinger'=> $betaling_tpl->fetch('tilmelding/betalinger-tpl.php'));
        $tpl = $this->templates->create('kortekurser/tilmelding');

        return $tpl->render($this, $tilmelding);

        /*
        $tpl->set('content_sub', '
            <div class="status">
                ' . ucfirst($status) . '
            </div>
        ');
        */

    }

    function postForm()
    {
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->name());
        if(!empty($_POST['annuller_tilmelding'])) {
            $tilmelding->setStatus("annulleret");
        }
        throw new k_SeeOther($this->url());
    }

    function map($name)
    {
        if ($name == 'sendbrev') {
            return 'VIH_Intranet_Controller_Kortekurser_Tilmeldinger_SendBrev';
        }
    }

    function renderPdf()
    {
        require_once 'fpdf/fpdf.php';

        $data = file_get_contents(dirname(__FILE__) . '/udsendte_pdf/' . $name);

        $response = new k_http_Response(200, $data);
        $response->setEncoding(NULL);
        $response->setContentType("application/pdf");

        $response->setHeader("Content-Length", strlen($data));
        $response->setHeader("Content-Disposition", "attachment; filename=\"foobar.pdf\"");
        $response->setHeader("Content-Transfer-Encoding", "binary");
        $response->setHeader("Cache-Control", "Public");
        $response->setHeader("Pragma", "public");
        throw $response;
    }
}