<?php
/**
  * Provides class dependency wiring for this application
  */
class VIH_Intranet_Factory
{
    public $template_dir;

    function new_k_TemplateFactory($c)
    {
        return new k_DefaultTemplateFactory(dirname(__FILE__) . '/view');
    }

    function new_pdoext_Connection($c)
    {
        return new pdoext_Connection("mysql:dbname=".DB_NAME.";host=" . DB_HOST, DB_USER, DB_PASSWORD);
    }

    function new_DB_Sql($c)
    {
        return new DB_Sql();
    }

    function new_DB_common($c)
    {
        $db_options= array("debug" => 2);
        $db = DB::connect(DB_DSN, $db_options);
        if (PEAR::isError($db)) {
            throw new Exception($db->getMessage());
        }
        $db->setFetchMode(DB_FETCHMODE_ASSOC);
        $db->query("SET time_zone=\"-01:00\"");
        $db->query('SET NAMES utf8');
        return $db;
    }

    function new_MDB2_Driver_Common($c)
    {
        $options= array("debug" => 0);
        $db = MDB2::factory(DB_DSN, $options);
        if (PEAR::isError($db)) {
            throw new Exception($db->getMessage());
        }
        $db->setOption("portability", MDB2_PORTABILITY_NONE);
        $db->setFetchMode(MDB2_FETCHMODE_ASSOC);
        $db->exec("SET time_zone=\"-01:00\"");
        $db->query('SET NAMES utf8');
        return $db;
    }

    function new_VIH_Intraface_Kernel($c)
    {
        $kernel = new VIH_Intraface_Kernel;
        $kernel->setting = new VIH_Intraface_Setting;
        $kernel->intranet = new VIH_Intraface_Intranet;
        $kernel->user = new VIH_Intraface_User;
        return $kernel;
    }

    function new_Intraface_Contact_XMLRPC_Client($c)
    {
        XML_RPC2_Backend::setBackend('php');
        $credentials = array('private_key' => $GLOBALS['elevforeningen_intraface_private_key'], 'session_id' => session_id());
        $debug = false;
        return $contact_client = new IntrafacePublic_Contact_XMLRPC_Client($credentials, $debug);
    }

    function new_Services_Twitter()
    {
        return new Services_Twitter($GLOBALS['twitter_username'], $GLOBALS['twitter_password']);
    }

    function new_Doctrine_Connection_Common()
    {
        $conn = Doctrine_Manager::connection(DB_DSN);
        //$conn->setAttribute("model_loading", "conservative");
        $conn->setCharset('utf8');
        return $conn;
    }
}