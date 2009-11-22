<?php
require_once 'config.local.php';

set_include_path(PATH_INCLUDE);

require_once 'VIH/errorhandler.php';
set_error_handler('vih_error_handler');

require_once 'k.php';
require_once 'Ilib/ClassLoader.php';
require_once 'VIH/functions.php';
require_once 'VIH/configuration.php';
require_once('Doctrine/lib/Doctrine.php');
spl_autoload_register(array('Doctrine', 'autoload'));

if (!defined('DB_DSN')) {
    define('DB_DSN', 'mysql://' . DB_USER . ':' . DB_PASSWORD . '@' . DB_HOST . '/' . DB_NAME);
}

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

$application->registry->registerConstructor('template', create_function(
  '$className, $args, $registry',
  'require_once "Template/Template.php";
   return new Template(PATH_INCLUDE . "/vih/intranet/view/");
  '
));

$application->registry->registerConstructor('intraface:filehandler:gateway', create_function(
  '$className, $args, $registry',
  'return new Ilib_Filehandler_Gateway($registry->get("intraface:kernel"));'
));


$application->dispatch();
