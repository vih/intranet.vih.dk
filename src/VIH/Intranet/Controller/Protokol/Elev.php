<?php
class VIH_Intranet_Controller_Protokol_Elev extends k_Component
{
    protected $db;
    protected $template;

    function __construct(DB_common $db, k_TemplateFactory $template)
    {
        $this->db = $db;
        $this->template = $template;
    }

    function map($name)
    {
        if ($name == 'indtast') {
            return 'VIH_Intranet_Controller_Protokol_Item';
        }
    }

    function renderHtml()
    {
        $type_key = $this->context->getTypeKeys();

        if (is_numeric($this->query('sletbillede'))) {
            $fields = array('date_updated', 'pic_id');
            $values = array('NOW()', 0);

            $sth = $this->db->autoPrepare('langtkursus_tilmelding', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $this->query('id'));
            $res = $this->db->execute($sth, $values);

            if (PEAR::isError($res)) {
                throw new Exception($res->getMessage());
            }
        }

        $form = new HTML_QuickForm;
        $form->addElement('hidden', 'id', $this->name());
        $form->addElement('file', 'userfile', 'Fil');
        $form->addElement('submit', null, 'Upload');

        if ($form->validate()) {
            $file = new VIH_FileHandler;
            if($file->upload('userfile')) {
                $fields = array('date_updated', 'pic_id');
                $values = array('NOW()', $file->get('id'));

                $sth = $this->db->autoPrepare('langtkursus_tilmelding', $fields, DB_AUTOQUERY_UPDATE, 'id = ' . $form->exportValue('id'));
                $res = $this->db->execute($sth, $values);

                if (PEAR::isError($res)) {
                    throw new Exception($res->getMessage());
                }

                return new k_SeeOther($this->url('./'));
            }
        }

        $tilmelding = new VIH_Model_LangtKursus_Tilmelding($this->name());

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
            $extra_html .= ' <br /><a href="'.$stor.'">stor</a> <a href="'.url('./') . '?sletbillede=' .$this->name().'" onclick="return confirm(\'Er du sikker\');">slet billede</a>';
        }

        $res = $this->db->query('SELECT *, DATE_FORMAT(date_start, "%d-%m %H:%i") AS date_start_dk, DATE_FORMAT(date_end, "%d-%m %H:%i") AS date_end_dk FROM langtkursus_tilmelding_protokol_item WHERE tilmelding_id = ' . (int)$this->name() . ' ORDER BY date_start DESC, date_end DESC');

        if (PEAR::isError($res)) {
            throw new Exception($res->getMessage());
        }

        $data = array('items' => $res,
                      'type_key' => $type_key,
                      'vis_navn' => false);

        $this->document->setTitle($tilmelding->get('navn'));
        $this->document->addOption('Ret', $this->url('../../../langekurser/tilmeldinger/' . $tilmelding->get('id')));
        $this->document->addOption('Indtast', $this->url('indtast'));
        $this->document->addOption('Tilmelding', $this->url('../../../langekurser/tilmeldinger/' . $tilmelding->get('id')));
        $this->document->addOption('Fag', $this->url('../../../langekurser/tilmeldinger/' . $tilmelding->get('id') . '/fag'));
        $this->document->addOption('Holdliste', $this->context->url());
        $this->document->addOption('Diplom', $this->url('../../../langekurser/tilmeldinger/' . $tilmelding->get('id') . '/diplom'));

        $tpl = $this->template->create('protokol/liste');

        return '<div style="border: 1px solid #ccc; padding: 0.5em; float: right;">' .   $extra_html . '</div>
            ' . $tpl->render($this, $data);
    }
}