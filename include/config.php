<?php
date_default_timezone_set('America/Buenos_Aires');

require_once dirname(__FILE__) . "/config.local.php";
require_once dirname(__FILE__) . '/../lib/PHPMailer/PHPMailerAutoload.php';


$defaultValues = array(
    '_DB_NAME' => 'php_congreso',
 	'_DB_USER' => 'root',
    '_DB_PASS' => 'revoluti0n',
    '_DB_CONN_ERROR_RETRIES'=>2,
    '_DEFAULT_LIST_LIMIT'=>50,
    '_TOKEN_SECRET'=>'OnceTheStone',
    '_SMTP_SERVER'=>'mail.congresoutelpa.com.ar',
    '_SMTP_USER_NAME'=>'info@congresoutelpa.com.ar',
    '_SMTP_USER_PASS'=>'infocongreso',
    '_EMAIL_FROM'=>'info@congresoutelpa.com.ar',
    '_EMAIL_FROM_NAME'=>'Congreso UTELPA',

);

//-- Redefinir con los defaultValues lo que no haya en config.local

foreach ($defaultValues as $name => $val) {
    if (!defined($name))
        define($name, $val);
}

//class paths
define("_BASE_DATA_ACCESS_PATH", _APP_PATH . "/include/data/BaseDataAccess.php");
define("_CONGRESO_DATA_ACCESS_PATH", _APP_PATH . "/include/data/CongresoDataAccess.php");
//-- Entities
define("_PARTICIPANTE_ENTITY_PATH", _APP_PATH . "/include/data/entities/ParticipanteEntity.php");
define("_TRABAJO_ENTITY_PATH", _APP_PATH . "/include/data/entities/TrabajoEntity.php");
define("_VOTO_ENTITY_PATH", _APP_PATH . "/include/data/entities/VotoEntity.php");

//-- Logics
define("_PARTICIPANTE_LOGIC_PATH", _APP_PATH . "/include/class/ParticipanteLogic.php");
define("_TRABAJO_LOGIC_PATH", _APP_PATH . "/include/class/TrabajoLogic.php");
define("_UTILITIES_PATH", _APP_PATH . "/include/class/Utilities.php");

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