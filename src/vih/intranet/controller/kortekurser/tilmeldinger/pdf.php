<?php
require_once 'fpdf/fpdf.php';

class VIH_Intranet_Controller_KorteKurser_Tilmeldinger_Pdf extends k_Controller
{
    function GET()
    {
        return 'pdf';
    }

    function forward($name) {
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