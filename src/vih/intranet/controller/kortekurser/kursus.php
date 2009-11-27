<?php
class VIH_Intranet_Controller_KorteKurser_Kursus extends k_Controller
{
    private $form;

    function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new HTML_QuickForm;
        $form->addElement('hidden', 'id', $this->name);
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');

        return ($this->form = $form);

    }

    function GET()
    {
        $db = $this->registry->get('database:pear');

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

        if (!empty($this->GET['sletbillede']) AND is_numeric($this->GET['sletbillede'])) {
                $fields = array('date_updated', 'pic_id');
                $values = array('NOW()', 0);

                $sth = $db->autoPrepare('kortkursus', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $_GET['id']);
                $res = $db->execute($sth, $values);

                if (PEAR::isError($res)) {
                    echo $res->getMessage();
                }
        }

        $extra_text = '';

        $kursus = new VIH_Model_KortKursus($this->name);
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

        $this->document->title = $kursus->get('navn');
        $this->document->options = array($this->url('../', array('filter' => $kursus->get('gruppe_id'))) => 'Tilbage til kurser',
                                         $this->url('edit') => 'Ret',
                                         $this->url('copy') => 'Kopier');

        return nl2br($kursus->get('beskrivelse')) . $extra_text . $extra_html;

    }

    function POST()
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

                throw new k_http_Redirect($this->url());
            }
        }
    }

    function forward($name)
    {
        if ($name == 'edit') {
            $next = new VIH_Intranet_Controller_KorteKurser_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'copy') {
            $next = new VIH_Intranet_Controller_KorteKurser_Copy($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'tilmeldinger') {
            $next = new VIH_Intranet_Controller_KorteKurser_Tilmeldinger($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'deltagere') {
            $next = new VIH_Intranet_Controller_KorteKurser_Deltagere($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'venteliste') {
            $next = new VIH_Intranet_Controller_KorteKurser_Venteliste_Index($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'adresselabels') {
            $next = new VIH_Intranet_Controller_KorteKurser_Lister_Adresselabels($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'deltagerliste') {
            $next = new VIH_Intranet_Controller_KorteKurser_Lister_Deltagerliste($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'drikkevareliste') {
            $next = new VIH_Intranet_Controller_KorteKurser_Lister_Drikkevareliste($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'ministeriumliste') {
            $next = new VIH_Intranet_Controller_KorteKurser_Lister_Ministerium($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'navneskilte') {
            $next = new VIH_Intranet_Controller_KorteKurser_Lister_Navneskilte($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'begyndere') {
            $next = new VIH_Intranet_Controller_KorteKurser_Lister_Begyndere($this, $name);
            return $next->handleRequest();
        }
    }
}

?>