<?php
XML_RPC2_Backend::setBackend('php');
class VIH_Intranet_Controller_Elevforeningen_Index extends k_Controller
{
    function GET()
    {
        $db = new DB_Sql;
        $db->query("SELECT aargange FROM elevforeningen_jubilar ORDER BY id DESC");
        if ($db->nextRecord()){
            $selected = unserialize($db->f('aargange'));
        }
        
        $credentials = array('private_key' => 'L9FtAdfAu8QwLSChGZehzeZwiAhXNwsqwWIMZF4avCw6jY6HN2G', 'session_id' => session_id());
        $contact_client = new IntrafacePublic_Contact_XMLRPC_Client($credentials, false);
        foreach ($contact_client->getKeywords() AS $key=>$value) {
            $input .= '<label><input type="checkbox" name="jubilar[]" value="'.$value['id'].'" ';
            if (in_array($value['id'], $selected)) $input .= ' checked="checked"';
            $input .= '/> '.$value['keyword'].' </label><br />';
        }
        
        return '
            <h1>Elevforeningen</h1>
            <form method="post" action="'.$this->url().'">
                <fieldset>
                    '.$input.'
                </fieldset>
                <input type="submit" value="Gem" />
            </form>
            ';
    }
    
    function POST()
    {
        $input = '';
        $selected = array();
        
        if (!empty($_POST)) {
            $db = new DB_Sql;
            $db->query("INSERT INTO elevforeningen_jubilar SET date_created = NOW(), aargange = '".serialize($_POST['jubilar'])."'");
        }

        throw new k_http_Redirect($this->url());        
    }
}
/*
require('../include_intranet.php');
require('IntrafacePublic/Contact/XMLRPC/Client.php');

$input = '';
$selected = array();

if (!empty($_POST)) {

    $db = new DB_Sql;
    $db->query("INSERT INTO elevforeningen_jubilar SET date_created = NOW(), aargange = '".serialize($_POST['jubilar'])."'");
}
$db = new DB_Sql;
$db->query("SELECT aargange FROM elevforeningen_jubilar ORDER BY id DESC");
if ($db->nextRecord()){
    $selected = unserialize($db->f('aargange'));
}

$credentials = array('private_key' => 'L9FtAdfAu8QwLSChGZehzeZwiAhXNwsqwWIMZF4avCw6jY6HN2G', 'session_id' => session_id());
$contact_client = new IntrafacePublic_Contact_XMLRPC_Client($credentials, false);
foreach ($contact_client->getKeywords() AS $key=>$value) {
    $input .= '<label><input type="checkbox" name="jubilar[]" value="'.$value['id'].'" ';
    if (in_array($value['id'], $selected)) $input .= ' checked="checked"';
    $input .= '/> '.$value['keyword'].' </label><br />';
}

$tpl = new Template(PATH_TEMPLATE_INTRANET);
$tpl->set('title', 'Forside');
$tpl->set('content_main', '
    <h1>Elevforeningen</h1>
    <form method="post" action="'.$_SERVER['PHP_SELF'].'">
        <fieldset>
            '.$input.'
        </fieldset>
        <input type="submit" value="Gem" />
    </form>
    ');

*/
?>

