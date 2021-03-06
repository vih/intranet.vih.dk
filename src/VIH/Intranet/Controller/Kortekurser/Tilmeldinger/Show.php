<?php
class VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Show extends k_Component
{
    protected $templates;
    protected $fpdf;

    function __construct(FPDF $fpdf, k_TemplateFactory $templates)
    {
        $this->templates = $templates;
        $this->fpdf = $fpdf;
    }

    function map($name)
    {
        if ($name == 'sendbrev') {
            return 'VIH_Intranet_Controller_Kortekurser_Tilmeldinger_SendBrev';
        } elseif ($name == 'edit') {
            return 'VIH_Intranet_Controller_Kortekurser_Tilmeldinger_Edit';
        }
    }

    function renderHtml()
    {
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->name());

        if (is_numeric($this->query('sletdeltager'))) {
            $deltager = new VIH_Model_KortKursus_Tilmelding_Deltager($tilmelding, $this->query('sletdeltager'));
            $deltager->delete();
        } elseif ($this->query('action') == 'sendemail') {
            if ($tilmelding->sendEmail()) {
                $historik = new VIH_Model_Historik('kortekurser', $tilmelding->get('id'));
                if (!$historik->save(array('type' => 'kode', 'comment' => 'Kode sendt med e-mail'))) {
                    throw new Exception('Historikken kunne ikke gemmes');
                }
            } else {
                throw new Exception('E-mailen kunne ikke sendes');
            }
        }

        if ($this->query('slet_historik_id')) {
            $historik = new VIH_Model_Historik(intval($this->query('slet_historik_id')));
            $historik->delete();
        }

        $deltagere = $tilmelding->getDeltagere();
        $historik_object = new VIH_Model_Historik('kortekurser', $tilmelding->get("id"));
        $betalinger = new VIH_Model_Betaling('kortekurser', $tilmelding->get("id"));

        if ($this->query('registrer_betaling')) {
            if ($betalinger->save(array('type' => 'giro', 'amount' => $this->query('beloeb')))) {
                $betalinger->setStatus('approved');
                return new k_SeeOther($this->url());
            } else {
                throw new Exception("Betalingen kunne ikke gemmes. Det kan skyldes et ugyldigt beløb");
            }
        }

        $tilmelding->loadBetaling();

        $this->document->setTitle('Tilmelding #' . $tilmelding->getId());
        $this->document->addOption('Tilbage til liste', $this->url('../'));
        $this->document->addOption('Ret', $this->url('edit'));
        $this->document->addOption('Slet', $this->url(null, array('delete')));
        if ($tilmelding->get('email')) {
            $this->document->addOption('E-mail', $this->url('email'));
        }
        $this->document->addOption('Kundens side', KORTEKURSER_LOGIN_URI . $tilmelding->get('code'));

        $data = array(
            'deltagere' => $deltagere,
        	'indkvartering' => !$tilmelding->kursus->isFamilyCourse(), //show if not a family course
        	'type' => $tilmelding->get('keywords'),
        	'vis_slet' => 'ja');

        $historik = array(
            'historik' => $historik_object->getList(),
        	'tilmelding' => $tilmelding);

        $historik_tpl = $this->templates->create('tilmelding/historik');
        $betaling_data = array(
            'caption' => 'Afventende betalinger',
        	'betalinger' => $betalinger->getList('not_approved'),
        	'msg_ingen', 'Der er ingen afventende betalinger.');

        $prisoversigt_data = array('tilmelding' => $tilmelding);
        $prisoversigt_tpl = $this->templates->create('kortekurser/tilmelding/prisoversigt');
        $deltager_tpl = $this->templates->create('kortekurser/deltagere');
        $betaling_tpl = $this->templates->create('tilmelding/betalinger');

        $tilmelding = array(
            'tilmelding' => $tilmelding,
        	'historik_object' => $historik_object,
        	'deltagere' => $deltager_tpl->render($this, $data),
        	'status' => $tilmelding->get('status'),
            'prisoversigt' => $prisoversigt_tpl->render($this, $prisoversigt_data),
        	'historik' => $historik_tpl->render($this, $historik),
        	'betalinger'=> $betaling_tpl->render($this, $betaling_data));

        $tpl = $this->templates->create('kortekurser/tilmelding');

        return $tpl->render($this, $tilmelding);
    }

    function renderHtmlDelete()
    {
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->name());
        if ($tilmelding->delete()) {
            return new k_SeeOther($this->context->url('../'));
        }
    }

    function postForm()
    {
        $tilmelding = new VIH_Model_KortKursus_Tilmelding($this->name());
        if (!empty($_POST['annuller_tilmelding'])) {
            $tilmelding->setStatus("annulleret");
        }
        return new k_SeeOther($this->url());
    }

    function renderPdf()
    {
        $data = file_get_contents(dirname(__FILE__) . '/udsendte_pdf/' . $name);

        $response = new k_HttpResponse(200, $data);
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