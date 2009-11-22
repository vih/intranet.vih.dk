<?php
/**
 * Controller for the intranet
 */
class VIH_Intranet_Controller_Fotogalleri_Show extends k_Controller
{
    function activate($active = 1) 
    {
        $db = $this->registry->get('database:mdb2');
        $result = $db->query('UPDATE fotogalleri SET active = '.intval($active).' WHERE id = '.intval($this->name));
        if (PEAR::isError($result)) {
            throw new Exception($result->getUserInfo());
        }
        return true;
    }

    function GET()
    {
        $db = $this->registry->get('database:mdb2');
        $result = $db->query('SELECT id, description, DATE_FORMAT(date_created, "%d-%m-%Y") AS dk_date_created, active FROM fotogalleri WHERE id = '.intval($this->name));
        if (PEAR::isError($result)) {
            throw new Exception($result->getUserInfo());
        }
        $row = $result->fetchRow();

        if(count($row) == 0) {
            throw new Exception('a valid id is needed');
        }

        // HACK start
        $kernel = $this->registry->get('intraface:kernel');
        $kernel->session_id = $this->SESSION->getSessionId();
        // HACK end

        $appender = new VIH_AppendFile($kernel, 'fotogalleri', $row['id']);
        if(isset($this->GET['return_redirect_id']) && intval($this->GET['return_redirect_id']) != 0) {
            $appender = new VIH_AppendFile($this->registry->get('intraface:kernel'), 'fotogalleri', $row['id']);
            $redirect = Ilib_Redirect::returns($this->SESSION->getSessionId(), $this->registry->get('database:pear'));

            foreach($redirect->getParameter('file_handler_id') AS $file_id) {
                $filehandler = new Ilib_FileHandler($kernel, $file_id);
                $appender->addFile($filehandler);
            }
        }

        if(isset($this->GET['delete_append_file']) && intval($this->GET['delete_append_file']) != 0) {
            $appender->delete($this->GET['delete_append_file']);
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

        $redirect = Ilib_Redirect::go($this->SESSION->getSessionId(), $this->registry->get('database:mdb2'));
        $url = $redirect->setDestination($this->url('/filemanager/selectfile', array('images' => 1)), $this->url());
        $redirect->askParameter('file_handler_id', 'multiple');

        $this->document->title = 'Tilføj billeder til galleriet' . $row['description'];
        $this->document->options = array($url => 'Tilføj billeder',
                                         $this->url('../'), 'Tilbage');

        return $this->render('vih/intranet/view/fotogalleri/fotoliste-tpl.php', $list);
    }

    function forward($name)
    {
        if ($name == 'edit') {
            $next = new VIH_Intranet_Controller_Fotogalleri_Edit($this, $name);
            return $next->handleRequest();
        }

        if($name == 'deactivate') {
            $this->activate(0);
            throw new k_http_Redirect($this->url('../'));

        }

        if($name == 'activate') {
            $this->activate();
            throw new k_http_Redirect($this->url('../'));
        }
    }
}