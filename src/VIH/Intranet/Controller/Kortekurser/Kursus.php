<?php
class VIH_Intranet_Controller_Kortekurser_Kursus extends k_Component
{
    private $form;

    private $db;
    protected $template;

    function __construct(DB_common $db, k_TemplateFactory $template)
    {
        $this->db = $db;
         $this->template = $template;
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

    function renderHtml()
    {
        $db = $this->db;

        /*
        if (!empty($_GET['copy'])) {
            $kursus = new VIH_Model_KortKursus($_GET['copy']);
            $new_kursus = new KortKursus();
            if ($id = $new_kursus->copy($kursus)) {
                header('Location: edit_kursus.php?id='.$id);
                exit;
            }
        }
        */

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
        $this->document->options = array($this->url('../', array('filter' => $kursus->get('gruppe_id'))) => 'Tilbage til kurser',
                                         $this->url('edit') => 'Ret',
                                         $this->url('copy') => 'Kopier');

        return nl2br($kursus->get('beskrivelse')) . $extra_text . $extra_html;

    }

    function postForm()
    {
        if ($this->getForm()->validate()) {
            $file = new VIH_FileHandler;
            if($file->upload('userfile')) {
                $fields = array('date_updated', 'pic_id');
                $values = array('NOW()', $file->get('id'));
                $sth = $db->autoPrepare('kortkursus', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $form->exportValue('id'));
                $res = $db->execute($sth, $values);

                if (PEAR::isError($res)) {
                    echo $res->getMessage();
                }

                return new k_SeeOther($this->url());
            }
        }
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
}