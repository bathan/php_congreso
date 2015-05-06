<?php
session_start();

include_once __DIR__ . '/../form_action_base.php';
include_once __DIR__ . '/participante_login.php';
include_once __DIR__ . '/participante_register.php';

/*
 * Este file es donde todos los POST de acciones de Participantes van a ir y dependiendo del action se definirá a donde va a parar
 */


if($_POST) {
    try {
        $base_form = new form_action_base($_POST);
        $participante_action = null;
        $result = null;

        switch($base_form->getAction()) {
            case form_action_base::ACTION_LOGIN_REGULAR:
            case form_action_base::ACTION_LOGIN_TOKEN: {
                $participante_action = new participante_login($_POST);

                $_SESSION["user_token"] = $participante_action->getParticipante()["user_token"];

                break;
            }
            case form_action_base::ACTION_REGISTER:
            case form_action_base::ACTION_UPDATE_INFO: {
                $participante_action = new participante_register($_POST);
                break;
            }
            case form_action_base::ACTION_LOGOUT: {
                session_destroy();
                $result = ["status"=>"ok"];
                break;
            }
            default: {
                break;
            }
        }

        if($participante_action) {
            $result = $participante_action->getResult();
        }

        echo json_encode($result,JSON_PRETTY_PRINT);

    }catch(Exception $e) {
        echo json_encode(["error"=>$e->getMessage(),"code"=>$e->getCode()],JSON_PRETTY_PRINT);
    }

}