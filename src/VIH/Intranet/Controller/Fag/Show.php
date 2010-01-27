<?php
class VIH_Intranet_Controller_Fag_Show extends k_Component
{
    public $map = array('edit' => 'VIH_Intranet_Controller_Fag_Edit',
                        'delete' => 'VIH_Intranet_Controller_Fag_Delete');

    private $form;
    private $db;

    function __construct(DB $db)
    {
        $this->db = $db;
    }

    function map($name)
    {
        return $this->map[$name];
    }

    function renderHtml()
    {
        $db = $this->db;

        if (!empty($_GET['sletbillede']) AND is_numeric($_GET['sletbillede'])) {
            $fields = array('date_updated', 'pic_id');
            $values = array('NOW()', 0);

            $sth = $db->autoPrepare('langtkursus_fag', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $this->name());
            $res = $db->execute($sth, $values);

            if (PEAR::isError($res)) {
                echo $res->getMessage();
            }
        }

        $fag = new VIH_Model_Fag($this->name());

        $file = new VIH_FileHandler($fag->get('pic_id'));
        if ($file->get('id') > 0) {
            $file->loadInstance('small');
            $extra_html = $file->getImageHtml();
            if (!empty($extra_html)) {
                $extra_html .= ' <br /><a href="?sletbillede='.$fag->get('pic_id').'">slet billede</a>';
            }
        }
        if (empty($extra_html)) {
            $extra_html = $this->getForm()->toHTML();
        }

        $this->document->setTitle('Fag: ' . $fag->get('navn'));
        $this->document->options = array($this->url('/langekurser') => 'Kurser',
                                         $this->context->url() => 'Fagoversigt',
                                         $this->url('edit') => 'Ret');

        return '<div>'.vih_autoop($fag->get('beskrivelse')).'</div>' . $extra_html;
    }

    function postForm()
    {
        $db = $this->db;
        if ($this->getForm()->validate()) {
            $file = new VIH_FileHandler;
            if($file->upload('userfile')) {
                $fields = array('date_updated', 'pic_id');
                $values = array('NOW()', $file->get('id'));
                $sth = $db->autoPrepare('langtkursus_fag', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $this->name());
                $res = $db->execute($sth, $values);

                if (PEAR::isError($res)) {
                    throw new Exception($res->getMessage());
                }

                return new k_SeeOther($this->url());
            }
        }
        return $this->render();
    }

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm('fag', 'POST', $this->url());
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');

        return ($this->form = $form);
    }

    function renderPdf()
    {
        $fag = new VIH_Model_Fag($this->name());

        // beskrivelsen skal deles op og regnes ud, hvor meget, der kan være på hver side.
        // det der ikke kan være på midtersiderne skal være på bagsiden.

        $pdf = new VIH_PdfBrochure();
        $pdf->SetTitle($fag->get('navn'));
        $pdf->SetSubject('Fagbeskrivelse: ' . $fag->get('navn'));
        $pdf->SetAuthor('Lars Olesen, Vejle Idrætshøjskole');
        $pdf->SetCreator('Lars Olesen, Vejle Idrætshøjskole');
        $pdf->SetDisplayMode('fullpage', 'two');
        $pdf->SetKeywords('keyword');
        $pdf->VIHContent($fag->get('beskrivelse'));
        $pdf->Output();
    }
}
