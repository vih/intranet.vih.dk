<?php
define('PATH_ROOT', dirname(__FILE__) . '/../');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'vih');
define('DB_DSN', 'mysql://'.DB_USER.':'.DB_PASSWORD.'@'.DB_HOST.'/' . DB_NAME);
define('PATH_WWW', '/vih/hojskole/src/vih.dk/');
define('PATH_UPLOAD', PATH_ROOT . 'upload/');
define('PATH_UPLOAD_ORIGINAL', PATH_UPLOAD . 'devel\original\\');
define('PATH_UPLOAD_INSTANCE', PATH_UPLOAD . 'devel\instance\\');
define('PATH_INCLUDE', dirname(__FILE__) . '/../../../hojskole/src/' . PATH_SEPARATOR . dirname(__FILE__) . '/../' . PATH_SEPARATOR . dirname(__FILE__)."/../../hojskole/src/" . PATH_SEPARATOR .ini_get('include_path'));
define('FILE_VIEWER', '/hojskole/src/vih.dk/file.php');

set_include_path(PATH_INCLUDE);

$GLOBALS['elevforeningen_intraface_private_key'] = '';
$GLOBALS['users'] = array('test' => 'test');
$GLOBALS['twitter_username'] = '';
$GLOBALS['twitter_password'] = '';