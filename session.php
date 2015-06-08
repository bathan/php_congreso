<?php
include_once __DIR__ . '/include/config.php';
session_start();
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _UTILITIES_PATH;


if (session_status() == PHP_SESSION_NONE) {
    //-- there is no session. Lets leave everything as it is

}else{
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        // last request was more than 30 minutes ago
        destroyAndRedirect("002");
    }

    $ParticipanteLogicObject = new ParticipanteLogic();
    $session_user_token = $_SESSION["user_token"];

    $ParticipanteTokenData = $ParticipanteLogicObject->validateUserToken($session_user_token);

    @$session_id_participante  = $ParticipanteTokenData["id"];

    if(is_null($ParticipanteTokenData)) {
        //-- This token is invalid, we should destroy the session and redirect to login.
        destroyAndRedirect("001");
    }

    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
}

function destroyAndRedirect($c) {

    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header("Location: /login.php?err=".$c);
}