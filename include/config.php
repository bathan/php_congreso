<?php
date_default_timezone_set('America/Buenos_Aires');

require_once dirname(__FILE__) . "/config.local.php";
require_once dirname(__FILE__) . "/autoload.php";

$defaultValues = array(
    '_DB_NAME' => 'php_congreso',
 	'_DB_USER' => 'root',
    '_DB_PASS' => 'revoluti0n',
    '_DB_CONN_ERROR_RETRIES'=>2,
    '_DEFAULT_LIST_LIMIT'=>50,
);

//-- Redefinir con los defaultValues lo que no haya en config.local

foreach ($defaultValues as $name => $val) {
    if (!defined($name))
        define($name, $val);
}

//class paths
define("_BASE_DATA_ACCESS_PATH", _APP_PATH . "/include/data/BaseDataAccess.php");
define("_CONGRESO_DATA_ACCESS_PATH", _APP_PATH . "/include/data/CongresoDataAccess.php");

//Encoding Secret
define('_ENCODING_SECRET', 'MiViejaMulaYaNoEsLoQueEra');

if(defined('_APP_DEBUG') && _APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

function pretty_print($data) {
    foreach($data as $k=>$v) {
        echo "[".$k."] = ".$v."\n";
    }
}