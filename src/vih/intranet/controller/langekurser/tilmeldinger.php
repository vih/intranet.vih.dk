<?php
class VIH_Intranet_Controller_LangeKurser_Tilmeldinger extends k_Controller
{
    function GET()
    {
        if ($this->GET['format'] == 'excel') {
            return $this->excel();
        }

        $kursus = new VIH_Model_LangtKursus($this->context->name);
        $tilmeldinger = $kursus->getTilmeldinger();

        $this->document->title = 'Tilmeldinger til ' . $kursus->get('kursusnavn');
        $this->document->options = array($this->url('../../') => 'Alle kurser');


        $data = array('tilmeldinger' => $tilmeldinger,
                      'caption' => 'Tilmeldinger');

        return $list->fetch('langekurser/tilmeldinger-tpl.php');
    }

    function getKursus()
    {
        return new VIH_Model_LangtKursus((int)$this->context->name);
    }

    function excel()
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


    function forward($name)
    {
        if ($name == 'adresseliste') {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Adresseliste($this, $name);
            return $next->handleRequest();
        } else {
            $next = new VIH_Intranet_Controller_LangeKurser_Tilmeldinger_Show($this, $name);
            return $next->handleRequest();
        }
    }
}