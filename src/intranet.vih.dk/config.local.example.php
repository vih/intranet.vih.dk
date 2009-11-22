<?php
define('PATH_ROOT', 'c:\Users\Lars Olesen\workspace\vih\\');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'vih');
define('DB_DSN', 'mysql://root:@localhost/vih');
define('PATH_WWW', '/vih/hojskole/src/vih.dk/');
define('PATH_UPLOAD', PATH_ROOT . 'upload/');
define('PATH_UPLOAD_ORIGINAL', PATH_UPLOAD . 'devel\original\\');
define('PATH_UPLOAD_INSTANCE', PATH_UPLOAD . 'devel\instance\\');
define('PATH_INCLUDE', dirname(__FILE_) . '/../../../hojskole/src/' . PATH_SEPARATOR . dirname(__FILE__) . '/../' . PATH_SEPARATOR . dirname(__FILE__)."/../../hojskole/src/" . PATH_SEPARATOR .ini_get('include_path'));
define('FILE_VIEWER', '/hojskole/src/vih.dk/file.php');

