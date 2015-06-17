<?php
include_once __DIR__ . '/include/config.php';
session_start();
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _TRABAJO_LOGIC_PATH;
require_once _UTILITIES_PATH;

define('IN_PHPBB', true);

global $user,$auth;

# your php extension
$phpEx = substr(strrchr(__FILE__, '.'), 1);
$phpbb_root_path = _APP_PATH.'/phpBB3/';

/* includes all the libraries etc. required */
require($phpbb_root_path ."common.php");
$user->session_begin();
$auth->acl($user->data);

/* the file with the actual goodies */
require($phpbb_root_path ."includes/functions_user.php");

$do_logout = request_var('logout',false);

if($do_logout) {
    destroyAndRedirect("014",$user);
}

if (session_status() == PHP_SESSION_NONE) {
    //-- there is no session. Lets leave everything as it is

}else{
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        // last request was more than 30 minutes ago
        destroyAndRedirect("002",$user);
    }

    $ParticipanteLogicObject = new ParticipanteLogic();
    $session_user_token = $_SESSION["user_token"];

    $ParticipanteTokenData = $ParticipanteLogicObject->validateUserToken($session_user_token);

    @$session_id_participante  = $ParticipanteTokenData["id"];

    if(is_null($ParticipanteTokenData)) {
        //-- This token is invalid, we should destroy the session and redirect to login.
        destroyAndRedirect("001",$user);
    }

    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp


    $participante_ya_subio_trabajos = $ParticipanteLogicObject->tieneTrabajoSubido($session_id_participante);
    $participante_data = $ParticipanteLogicObject->obtenerParticipante($session_id_participante);


    $auth->login($participante_data["email"], $participante_data["password"], 1, 1, 0);

}

function destroyAndRedirect($c,$user) {

    $user->session_kill();
    $user->session_begin();

    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header("Location: /login.php?err=".$c);


}