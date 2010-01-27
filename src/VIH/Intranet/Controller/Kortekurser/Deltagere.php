<?php
class VIH_Intranet_Controller_Kortekurser_Deltagere extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $kursus = new VIH_Model_KortKursus($this->context->name());
        $deltagere = $kursus->getDeltagere();
        switch ($kursus->get('gruppe_id')) {
            case 1: // golf
                $keywords[] = 'golf';
            break;
            case 2: // øvrige
                $keywords[] = 'familie';
            break;
            case 3: // bridge
                $keywords[] = 'bridge';
            break;
            case 4: // golf og bridge
                $keywords[] = 'golf';
                $keywords[] = 'bridge';
            break;
            default:
                $keywords = array();
            break;
        }

        $this->document->setTitle('Deltagere på ' . $kursus->getKursusNavn());

        $this->document->options = array(
            $this->url('../tilmeldinger') => 'Gå til tilmeldingerne',
                $this->url('../deltagerliste') => 'Deltagerliste',
                $this->url('../adresselabels') => 'Adresselabels',
                $this->url('../drikkevareliste') => 'Drikkevareliste',
                $this->url('../navneskilte') => 'Navneskilte (pdf)',
                $this->url('../ministeriumliste') => 'Ministerium'
        );

        $data = array('vis_tilmelding' => 'ja',
                      'deltagere' => $deltagere,
                      'type' => $keywords,
                      'indkvartering' => $kursus->get('indkvartering'));

        $tpl = $this->template->create('kortekurser/deltagere');
        return '<p>Deltagerantal: ' . count($deltagere) . '</p>' . $tpl->render($this, $data);
    }

    function renderXls()
    {
        $workbook = new Spreadsheet_Excel_Writer();

        // sending HTTP headers
        $workbook->send($this->getKursus()->getKursusNavn());

        // Creating a worksheet
        $worksheet =& $workbook->addWorksheet('Deltagere');

        $format_bold =& $workbook->addFormat();
        $format_bold->setBold();
        $format_bold->setSize(8);

        $format_italic =& $workbook->addFormat();
        $format_italic->setItalic();
        $format_italic->setSize(8);

        $format =& $workbook->addFormat();
        $format->setSize(8);

        $i = 0;
        $worksheet->write($i, 0, 'Vejle Idrætshøjskole: ' . $this->getKursus()->getKursusNavn(), $format_bold);

        $i = 2;
    	foreach ($this->getKursus()->getDeltagere() AS $deltager) {
       		$worksheet->write($i, 0, $deltager->get('navn'), $style);
       		$worksheet->write($i, 1, $deltager->get('cpr'), $style);
       		$i++;
       	}

        $worksheet->hideGridLines();

        // Let's send the file
        $data = $workbook->close();

        $response = new k_http_Response(200, $data);
        $response->setEncoding(NULL);
        $response->setContentType("application/excel");
        /*
        $response->setHeader("Content-Length", strlen($data));
        $response->setHeader("Content-Disposition", "attachment;filename=\"\"");
        $response->setHeader("Content-Transfer-Encoding", "binary");
        $response->setHeader("Cache-Control", "Public");
        $response->setHeader("Pragma", "public");
        */
        throw $response;
    }

    function getKursus()
    {
        return new VIH_Model_KortKursus((int)$this->context->name());
    }
}