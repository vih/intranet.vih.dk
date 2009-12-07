<?php
class VIH_Intranet_Controller_Langekurser_Tilmeldinger extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        if ($this->GET['format'] == 'excel') {
            return $this->renderElx();
        }

        $kursus = new VIH_Model_LangtKursus($this->context->name());
        $tilmeldinger = $kursus->getTilmeldinger();

        $this->document->setTitle('Tilmeldinger til ' . $kursus->get('kursusnavn'));
        $this->document->options = array($this->url('../../') => 'Alle kurser');


        $data = array('tilmeldinger' => $tilmeldinger,
                      'caption' => 'Tilmeldinger');

        $tpl = $this->template->create('langekurser/tilmeldinger');
        return $tpl->render($this, $data);
    }

    function getKursus()
    {
        return new VIH_Model_LangtKursus((int)$this->context->name());
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
    	foreach ($this->getKursus()->getTilmeldinger() AS $deltager) {
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


    function map($name)
    {
        if ($name == 'adresseliste') {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Adresseliste';
        } else {
            return 'VIH_Intranet_Controller_Langekurser_Tilmeldinger_Show';
        }
    }
}