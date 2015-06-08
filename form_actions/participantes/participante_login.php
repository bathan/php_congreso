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
            $this->result = array_merge(["status"=>"ok"],$p);
            $this->participante = $p;

            session_start();
            $_SESSION['USER_TOKEN'] = $p["user_token"];
            $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp


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

    public function getParticipante() {
        return $this->participante;
    }
}

