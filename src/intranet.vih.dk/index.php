<?php
require_once 'config.local.php';
require_once 'Ilib/ClassLoader.php';
require_once 'VIH/functions.php';
require_once 'VIH/configuration.php';
require_once 'konstrukt/konstrukt.inc.php';
require_once 'bucket.inc.php';

require_once('Doctrine/lib/Doctrine.php');
spl_autoload_register(array('Doctrine', 'autoload'));

class k_SessionIdentityLoader implements k_IdentityLoader
{
    function load(k_Context $context)
    {
        if ($context->session('identity')) {
            return $context->session('identity');
        }
        return new k_Anonymous();
    }
}

class NotAuthorizedComponent extends k_Component
{
    function dispatch()
    {
        return new k_TemporaryRedirect($this->url('/login', array('continue' => $this->requestUri())));
    }
}

$factory = new VIH_Intranet_Factory();
$container = new bucket_Container($factory);

if (realpath($_SERVER['SCRIPT_FILENAME']) == __FILE__) {
    $components = new k_InjectorAdapter($container, new VIH_Intranet_Document);
    $components->setImplementation('k_DefaultNotAuthorizedComponent', 'NotAuthorizedComponent');
    $identity_loader = new k_SessionIdentityLoader();
    k()
    ->setComponentCreator($components)
    ->setIdentityLoader($identity_loader)
    ->run('VIH_Intranet_Controller_Root')
    ->out();
}

/*
 $application = new VIH_Intranet_Controller_Root();

 $application->registry->registerConstructor('database', create_function(
 '$className, $args, $registry',
 'return new pdoext_Connection("mysql:dbname=".DB_NAME.";host=" . DB_HOST, DB_USER, DB_PASSWORD);'
 ));

 $application->registry->registerConstructor('database:db_sql', create_function(
 '$className, $args, $registry',
 'return new DB_Sql();'
 ));

 $application->registry->registerConstructor('database:pear', create_function(
 '$className, $args, $registry',
 '$db_options= array("debug"       => 2);
 $db = DB::connect(DB_DSN, $db_options);
 if (PEAR::isError($db)) {
 die($db->getMessage());
 }
 $db->setFetchMode(DB_FETCHMODE_ASSOC);
 $db->query("SET time_zone=\"-01:00\"");
 return $db;
 '
 ));

 $application->registry->registerConstructor('database:mdb2', create_function(
 '$className, $args, $registry',
 '$options= array("debug" => 0);
 $db = MDB2::factory(DB_DSN, $options);
 if (PEAR::isError($db)) {
 die($db->getMessage());
 }
 $db->setOption("portability", MDB2_PORTABILITY_NONE);
 $db->setFetchMode(MDB2_FETCHMODE_ASSOC);
 $db->exec("SET time_zone=\"-01:00\"");
 return $db;
 '
 ));

 $application->registry->registerConstructor('intraface:kernel', create_function(
 '$className, $args, $registry',
 '$kernel = new VIH_Intraface_Kernel;
 $kernel->setting = new VIH_Intraface_Setting;
 $kernel->intranet = new VIH_Intraface_Intranet;
 $kernel->user = new VIH_Intraface_User;
 return $kernel;'
 ));

 $application->registry->registerConstructor('table:langtkursus_periode', create_function(
 '$className, $args, $registry',
 'return new pdoext_TableGateway("langtkursus_fag_periode", $registry->get("database"));'
 ));

 $application->registry->registerConstructor('liveuser', create_function(
 '$className, $args, $registry',
 'return new VIH_Intranet_User;'
 ));

 $application->registry->registerConstructor('doctrine', create_function(
 '$className, $args, $registry',
 'return Doctrine_Manager::connection(DB_DSN);'
 ));

 $application->registry->registerConstructor('intraface:filehandler:gateway', create_function(
 '$className, $args, $registry',
 'return new Ilib_Filehandler_Gateway($registry->get("intraface:kernel"));'
 ));


 $application->dispatch();
 */