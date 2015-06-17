<?php

require_once __DIR__ . '/../form_action_base.php';
require_once _PARTICIPANTE_LOGIC_PATH;

/**
 * Class participante_login
 */

class participante_login extends form_action_base{

    private $participante;

    public function __construct(Array $formData) {

        try {
            parent::__construct($formData);

            switch ($this->action) {
                case self::ACTION_LOGIN_REGULAR: {
                    $this->loginRegular($formData);
                    break;
                }
                case self::ACTION_LOGIN_TOKEN: {
                    $this->loginToken($formData);
                    break;
                }
                case self::ACTION_RETRIEVE_PASS: {
                    $this->retrievePassword($formData);
                    break;
                }
                case self::ACTION_FORUM_ADD: {
                    $this->result = $this->addUserToForum($formData);
                    break;
                }
                default: {
                    break;
                }
            }
        }catch(Exception $e) {
            throw $e;
        }

    }

    /*
     * Login regular requiere de las variables email y password
     */
    private function loginRegular(Array $formData) {

        try {
            $this->validateRequiredFields(['email','password']);
            $email = $formData['email'];
            $password = $formData['password'];

            $p_logic = New ParticipanteLogic();
            $p = $p_logic->loginParticipante($email,$password);

            unset($p['password']);
            $this->participante = $p;

            session_start();
            $_SESSION['USER_TOKEN'] = $p["user_token"];
            $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

            //-- Login To Forum
            $formData["user_token"] = $p["user_token"];

            //$forumResult = $this->addUserToForum($formData);
            $forumResult = [];

            $this->result = array_merge(["status"=>"ok","forum_result"=>$forumResult],$p);

        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }

    }
    /*
     * Login mediante token requiere que se pase un token valido
     */
    private function loginToken(Array $formData) {

        try {

            $this->validateRequiredFields(['token']);
            $token = $formData['token'];

            $p_logic = new ParticipanteLogic();
            if($token_data = $p_logic->validateUserToken($token)) {

                $id = $token_data["id"];

                $p = $p_logic->obtenerParticipante($id);

                //-- Since we are logging, lets return the whole participante except for the password
                unset($p['password']);
                $this->result = array_merge(["status"=>"ok"],$p);
                $this->participante = $p;

            }else{
                throw new Exception("El Token de usuario no es valido");
            }

        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }
    }

    private function retrievePassword(Array $formData) {

        try {

            $this->validateRequiredFields(['email']);

            $p_logic = new ParticipanteLogic();
            $participante = $p_logic->obtenerParticipantePorEmail($formData['email']);
            $p_logic->sendForgotPasswordEmail($participante);
            $this->result = ["status"=>"ok"];

        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }

    }

    public function getParticipante() {
        return $this->participante;
    }


    private function addUserToForum(Array $formData) {
        try {

            $p_logic = new ParticipanteLogic();

            $user_token = $p_logic->validateUserToken($formData['user_token']);

            if(is_null($user_token)) {
                throw new Exception("Token de usuario invalido");
            }

            //-- Validar Email
            if((isset($formData["email"]) && $formData["email"] != '') && !Utilities::isValidEmail($formData["email"])) {
                throw new Exception("Dirección de Email Invalida");
            }


            $participante = $p_logic->obtenerParticipante($user_token["id"]);


            define('IN_PHPBB', true);

            /* set scope for variables required later */
            global $phpbb_root_path;
            global $phpEx;
            global $db;
            global $config;
            global $user;
            global $auth;
            global $cache;
            global $template;
            global $request;
            global $symfony_request;
            global $phpbb_filesystem;
            global $phpbb_container;
            global $phpbb_dispatcher;

            # your php extension
            $phpEx = substr(strrchr(__FILE__, '.'), 1);
            $phpbb_root_path = _APP_PATH.'/foro/';

            /* includes all the libraries etc. required */
            require($phpbb_root_path ."common.php");
            $user->session_begin();
            $auth->acl($user->data);

            /* the file with the actual goodies */
            require($phpbb_root_path ."includes/functions_user.php");

            /* All the user data (I think you can set other database fields aswell, these seem to be required )*/
            $user_row = [
                'username' => $participante["email"],
                'user_password' => md5($participante["password"]),
                'user_email' => $participante["email"],
                'group_id' => 8, //-- Grupo de UTELPa en PHPBB
                'user_timezone' => '-3',
                'user_lang' => 'es',
                'user_type' => '0',
                'user_actkey' => '',
                'user_dateformat' => 'd M Y H:i',
                'user_style' => 1,
                'user_regdate' => time()];

            //-- CHeck if user exists
            $validation_result = validate_username($participante["email"]);

            if($validation_result != 'USERNAME_TAKEN') {
                /* Now Register user */
                $phpbb_user_id = user_add($user_row);
            }else{
                //-- This user already exists. We are ok!
            }

            $result = ["status"=>"ok","php_bb_user_id"=>$phpbb_user_id,"validation_result"=>$validation_result];


        }catch(Exception $e) {
            $result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }

        return $result;

    }

}

