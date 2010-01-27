<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Fotogalleri_Show extends k_Component
{
    private $db;
    private $kernel;
    private $pear_db;
    protected $template;

    function __construct(k_TemplateFactory $template, DB $pear_db, MDB2_Driver_Common $db, VIH_Intraface_Kernel $kernel)
    {
        $this->db = $db;
        $this->kernel = $kernel;
        $this->pear_db = $pear_db;
        $this->template = $template;
    }

    function activate($active = 1)
    {
        $db = $this->db;
        $result = $db->query('UPDATE fotogalleri SET active = '.intval($active).' WHERE id = '.intval($this->name()));
        if (PEAR::isError($result)) {
            throw new Exception($result->getUserInfo());
        }
        return true;
    }

    function renderHtml()
    {
        $db = $this->db;
        $result = $db->query('SELECT id, description, DATE_FORMAT(date_created, "%d-%m-%Y") AS dk_date_created, active FROM fotogalleri WHERE id = '.intval($this->name()));
        if (PEAR::isError($result)) {
            throw new Exception($result->getUserInfo());
        }
        $row = $result->fetchRow();

        if(count($row) == 0) {
            throw new Exception('a valid id is needed');
        }

        // HACK start
        $kernel = $this->kernel;
        $kernel->session_id = $this->SESSION->getSessionId();
        // HACK end

        $appender = new VIH_AppendFile($kernel, 'fotogalleri', $row['id']);
        if(intval($this->query('return_redirect_id')) != 0) {
            $appender = new VIH_AppendFile($this->kernel, 'fotogalleri', $row['id']);
            $redirect = Ilib_Redirect::returns($this->session()->getSessionId(), $this->pear_db);

            foreach($redirect->getParameter('file_handler_id') AS $file_id) {
                $filehandler = new Ilib_FileHandler($kernel, $file_id);
                $appender->addFile($filehandler);
            }
        }

        if (intval($this->query('delete_append_file')) != 0) {
            $appender->delete($this->query('delete_append_file'));
        }

        $appender->getDBQuery()->setFilter('order_by', 'name');

        $photos = $appender->getList();

        for($i = 0, $max = count($photos); $i < $max; $i++) {
            $filehandler = new Ilib_Filehandler($kernel, $photos[$i]['file_handler_id']);
            $photos[$i]['icon_uri'] = $filehandler->get('icon_uri');
            $photos[$i]['icon_width'] = $filehandler->get('icon_width');
            $photos[$i]['icon_height'] = $filehandler->get('icon_height');
            $photos[$i]['url_delete'] = $this->url(NULL, array('delete_append_file' => $photos[$i]['id']));
        }
        $list = array('photos' => $photos);

        $redirect = Ilib_Redirect::go($this->SESSION->getSessionId(), $this->db);
        $url = $redirect->setDestination($this->url('/filemanager/selectfile', array('images' => 1)), $this->url());
        $redirect->askParameter('file_handler_id', 'multiple');

        $this->document->setTitle('Tilføj billeder til galleriet' . $row['description']);
        $this->document->options = array($url => 'Tilføj billeder',
                                         $this->url('../'), 'Tilbage');

        $tpl = $this->template->create('fotogalleri/fotoliste');
        return $tpl->render($data, $list);
    }

    function map($name)
    {
        if ($name == 'edit') {
            return 'VIH_Intranet_Controller_Fotogalleri_Edit';
        }

        if ($name == 'deactivate') {
            $this->activate(0);
            return new k_SeeOther($this->url('../'));

        }

        if ($name == 'activate') {
            $this->activate();
            return new k_SeeOther($this->url('../'));
        }
    }
}