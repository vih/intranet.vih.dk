<?php
class VIH_Intranet_Controller_Protokol_Elev extends k_Controller
{
    function GET()
    {
        $type_key = $this->context->getTypeKeys();

        $db = $this->registry->get('database:pear');
        # delete
        if (!empty($_GET['delete']) AND is_numeric($_GET['delete'])) {
            $res =& $db->query('DELETE FROM langtkursus_tilmelding_protokol_item WHERE id = ' . $_GET['delete']);
        }

        if (!empty($_GET['sletbillede']) AND is_numeric($_GET['sletbillede'])) {
            $fields = array('date_updated', 'pic_id');
            $values = array('NOW()', 0);

            $sth = $db->autoPrepare('langtkursus_tilmelding', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $_GET['id']);
            $res = $db->execute($sth, $values);

            if (PEAR::isError($res)) {
                echo $res->getMessage();
            }
        }

        $form = new HTML_QuickForm;
        $form->addElement('hidden', 'id', $this->name);
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');

        if ($form->validate()) {
            $file = new VIH_FileHandler;
            if($file->upload('userfile')) {
                $fields = array('date_updated', 'pic_id');
                $values = array('NOW()', $file->get('id'));

                $sth = $db->autoPrepare('langtkursus_tilmelding', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $form->exportValue('id'));
                $res = $db->execute($sth, $values);

                if (PEAR::isError($res)) {
                    echo $res->getMessage();
                }

                throw new k_http_Redirect($this->url('./'));
            }
        }

        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->name);

        if ($tilmelding->get('id') == 0) {
            throw new k_http_Response(404);
        }

        $file = new VIH_FileHandler($tilmelding->get('pic_id'));
        $file->loadInstance('small');
        $extra_html = $file->getImageHtml($tilmelding->get('name'), 'width="100""');

        $file->loadInstance('medium');
        $stor = $file->get('file_uri');

        if (empty($extra_html)) {
            $extra_html = $form->toHTML();
        } else {
            $extra_html .= ' <br /><a href="'.$stor.'">stor</a> <a href="'.url('./') . '?sletbillede=' .$this->name.'" onclick="return confirm(\'Er du sikker\');">slet billede</a>';
        }

        $res =& $db->query('SELECT *, DATE_FORMAT(date_start, "%d-%m %H:%i") AS date_start_dk, DATE_FORMAT(date_end, "%d-%m %H:%i") AS date_end_dk FROM langtkursus_tilmelding_protokol_item WHERE tilmelding_id = ' . (int)$this->name . ' ORDER BY date_start DESC, date_end DESC');

        if (PEAR::isError($res)) {
            die($res->getMessage());
        }

        $data = array('items' => $res,
                      'type_key' => $type_key,
                      'vis_navn' => false);

        $this->document->title = $tilmelding->get('navn');
        $this->document->options = array(
            $this->url('/langekurser/tilmeldinger/' . $tilmelding->get('id')) => 'Ret',
            $this->url('indtast') => 'Indtast',
            $this->url('/langekurser/tilmeldinger/' . $tilmelding->get('id')) => 'Tilmelding',
            $this->url('/langekurser/tilmeldinger/' . $tilmelding->get('id') . '/fag') => 'Fag',
            $this->context->url() => 'Holdliste',
            $this->url('/langekurser/tilmeldinger/' . $tilmelding->get('id') . '/diplom') => 'Diplom'
        ); 

        return '<div style="border: 1px solid #ccc; padding: 0.5em; float: right;">' .   $extra_html . '</div>
            ' . $this->render('vih/intranet/view/protokol/liste-tpl.php', $data);

    }

    function forward($name)
    {
        if ($name == 'indtast') {
            $next = new VIH_Intranet_Controller_Protokol_Indtast($this, $name);
            return $next->handleRequest();
        }
    }
}