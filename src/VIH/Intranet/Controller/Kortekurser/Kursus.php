<?php
class VIH_Intranet_Controller_Kortekurser_Kursus extends k_Component
{
    protected $form;
    protected $db;
    protected $template;

    function __construct(DB_common $db, k_TemplateFactory $template)
    {
        $this->db = $db;
        $this->template = $template;
    }

    function dispatch()
    {
        if ($this->getCourse()->get('id') == 0) {
            throw new k_PageNotFound();
        }
        return parent::dispatch();
    }

    function map($name)
    {
        if ($name == 'edit') {
            return 'VIH_Intranet_Controller_Kortekurser_Edit';
        } elseif ($name == 'copy') {
            return 'VIH_Intranet_Controller_Kortekurser_Copy';
        } elseif ($name == 'tilmeldinger') {
            return 'VIH_Intranet_Controller_Kortekurser_Tilmeldinger';
        } elseif ($name == 'deltagere') {
            return 'VIH_Intranet_Controller_Kortekurser_Deltagere';
        } elseif ($name == 'venteliste') {
            return 'VIH_Intranet_Controller_Kortekurser_Venteliste_Index';
        } elseif ($name == 'adresselabels') {
            return 'VIH_Intranet_Controller_Kortekurser_Lister_Adresselabels';
        } elseif ($name == 'deltagerliste') {
            return 'VIH_Intranet_Controller_Kortekurser_Lister_Deltagerliste';
        } elseif ($name == 'drikkevareliste') {
            return 'VIH_Intranet_Controller_Kortekurser_Lister_Drikkevareliste';
        } elseif ($name == 'ministeriumliste') {
            return 'VIH_Intranet_Controller_Kortekurser_Lister_Ministerium';
        } elseif ($name == 'navneskilte') {
            return 'VIH_Intranet_Controller_Kortekurser_Lister_Navneskilte';
        } elseif ($name == 'begyndere') {
            return 'VIH_Intranet_Controller_Kortekurser_Lister_Begyndere';
        }
    }

    function renderHtml()
    {
        $db = $this->db;

        if (is_numeric($this->query('sletbillede'))) {
                $fields = array('date_updated', 'pic_id');
                $values = array('NOW()', 0);

                $sth = $db->autoPrepare('kortkursus', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $_GET['id']);
                $res = $db->execute($sth, $values);

                if (PEAR::isError($res)) {
                    echo $res->getMessage();
                }
        }

        $extra_text = '';

        $kursus = new VIH_Model_KortKursus($this->name());
        $venteliste = new VIH_Model_Venteliste(1, $kursus->get('id'));
        $venteliste_list = $venteliste->getList();
        $venteliste_count = count($venteliste_list);

        if ($venteliste_count > 0) {
            $extra_text = '<p><a href="venteliste.php?kursus_id='.$kursus->get('id').'">Venteliste</a></p>';
        }

        $file = new VIH_FileHandler($kursus->get('pic_id'));
        if ($file->get('id') > 0) {
            $file->loadInstance('small');
            $extra_html = $file->getImageHtml();
            if (!empty($extra_html)) {
                $extra_html .= ' <br /><a href="?sletbillede='.$kursus->get('pic_id').'&amp;id='.$_GET['id'].'">slet billede</a>';
            }
        }
        if (empty($extra_html)) {
            $extra_html = $this->getForm()->toHTML();
        }

        $begynder = '';
        if ($kursus->get('gruppe_id') == 1) {
            $begynder = '<p>Begyndere: ' . $kursus->getBegyndere() . '</p>';
        }

        $this->document->setTitle($kursus->get('navn'));
        $this->document->addOption('Tilbage til kurser', $this->url('../', array('filter' => $kursus->get('gruppe_id'))));
        $this->document->addOption('Ret', $this->url('edit'));
        $this->document->addOption('Kopier', $this->url(null, array('copy')));

        return nl2br($kursus->get('beskrivelse')) . $extra_text . $extra_html;

    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $file = new VIH_FileHandler;
            if($file->upload('userfile')) {
                $fields = array('date_updated', 'pic_id');
                $values = array('NOW()', $file->get('id'));
                $sth = $this->db->autoPrepare('kortkursus', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $form->exportValue('id'));
                $res = $this->db->execute($sth, $values);

                if (PEAR::isError($res)) {
                    echo $res->getMessage();
                }

                return new k_SeeOther($this->url());
            }
        }
        return $this->render();
    }

    function renderHtmlCopy()
    {
        $kursus = new VIH_Model_KortKursus($this->name());
        $new_kursus = new VIH_Model_KortKursus();
        if ($id = $new_kursus->copy($kursus)) {
            return new k_SeeOther($this->url('../' . $id));
        }
        throw new Exception('Could not copy course');
    }


    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm;
        $form->addElement('hidden', 'id', $this->name());
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');

        return ($this->form = $form);
    }

    function getCourse()
    {
        return new VIH_Model_KortKursus($this->name());
    }
}