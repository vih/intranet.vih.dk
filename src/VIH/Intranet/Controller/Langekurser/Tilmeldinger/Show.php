<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger_Show extends k_Component
{
    private $template;
    protected $templates;

    function __construct(k_TemplateFactory $templates)
    {
        $this->templates = $templates;
    }

    function renderHtml()
    {
        if ($this->query('get_prices')) {
            $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->query('get_prices'));
            if (!$tilmelding->getPriserFromKursus()) {
                throw new Exception('Tilmeldingen kunne ikke slettes');
            } else {
                return new k_SeeOther($this->url());
            }
        }

        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->name());
        if ($tilmelding->get('id') == 0) {
            throw new k_http_Response(404);
        }

        $historik = new VIH_Model_Historik('langekurser', $tilmelding->get("id"));
        $betalinger = new VIH_Model_Betaling('langekurser', $tilmelding->get("id"));

        $rater = $tilmelding->getRater();

        if ($this->query('action') == 'sendemail') {
            if ($tilmelding->sendEmail()) {
                if (!$historik->save(array('type' => 'kode', 'comment' => 'Kode sendt med e-mail'))) {
                    throw new Exception('Historikken kunne ikke gemmes');
                }
            } else {
                throw new Exception('E-mailen kunne ikke sendes');
            }
        } elseif ($this->query('action') == 'opretrater') {
            if (!$tilmelding->opretRater()) {
                throw new Exception('Raterne kunne ikke oprettes');
            } else {
                return new k_SeeOther($this->url());
            }
        } elseif($this->query('registrer_betaling')) {
            if($betalinger->save(array('type' => 'giro', 'amount' => $this->query('beloeb')))) {
                $betalinger->setStatus('approved');
            } else {
                throw new Exception("Betalingen kunne ikke gemmes. Det kan skyldes et ugyldigt beløb", E_USER_ERROR);
            }
        } elseif ($this->query('slet_historik_id')) {
            $historik = new VIH_Model_Historik(intval($this->query('slet_historik_id')));
            $historik->delete();
        }

        $tilmelding->loadBetaling();

        $this->document->setTitle('Tilmelding');

        $opl_data = array('tilmelding' => $tilmelding);

        $pris_data = array('tilmelding' => $tilmelding);

        $betal_data = array('betalinger' => $betalinger->getList('not_approved'),
                            'caption' => 'Betalinger');

        $hist_data = array('tilmelding' => $tilmelding,
                           'historik' => $historik->getList());

        $opl_tpl = $this->templates->create('langekurser/tilmelding/oplysninger');
        $pris_tpl = $this->templates->create('langekurser/tilmelding/prisoversigt');
        $betal_tpl = $this->templates->create('tilmelding/betalinger');
        $his_tpl = $this->templates->create('tilmelding/historik');

        $data = array('tilmelding' => $tilmelding,
                      'oplysninger' => $opl_tpl->render($this, $opl_data),
                      'prisoversigt' => $pris_tpl->render($this, $pris_data),
                      'betalinger' => $betal_tpl->render($this, $betal_data),
                      'historik' => $his_tpl->render($this, $hist_data));

        // rater
        if (count($rater) > 0) {
            $rater_tpl = $this->templates->create('langekurser/tilmelding/rater');
            $rater_data = array('tilmelding' => $tilmelding);
            $data['rater'] = $rater_tpl->render($this, $rater_data);
        } else {
            if ($tilmelding->kursus->antalRater() > 0) {
                $data['rater'] = '<p><a href="'.$this->url(null, array('get_prices' => $tilmelding->get('id'))).'">Hent priserne fra kurset</a>. Der er endnu ikke oprettet nogen rater <a href="'.$this->url(null, array('action' => 'opretrater')) . '">Opret &rarr;</a></p>';
            } else {
                $data['rater'] = '<p>Der er endnu ikke oprettet rater på selve kurset. Dem skal du lige oprette først <a href="'.$this->url('../../'.$tilmelding->getKursus()->get('id').'/rater').'">Opret &rarr;</a></p>';
            }
        }

        $data['message'] = '';

        if($this->query('download_file') != "") {
            $data['message'] = '
                <div id="download_file">
                    <strong>Download:</strong> <a href="' . urldecode($this->query('download_file')) . '">Hent fil</a> (<a href="' . urldecode($this->query('download_file')) . '">I dette vindue</a>)
                </div>
            ';
        }

        $tpl = $this->templates->create('langekurser/tilmelding');
        return $tpl->render($this, $data);

    }

    function map($name)
    {
        if ($name == 'rater') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Rater';
        } elseif ($name == 'fag') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Fag';
        } elseif ($name == 'brev') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Brev';
        } elseif ($name == 'diplom') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Pdfdiplom';
        } elseif ($name == 'edit') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Edit';
        } elseif ($name == 'delete') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Delete';
        }
    }
}