<?php
include_once __DIR__ . '/../../include/config.php';

/*
 * Este file es el que se encargará de todas las acciones relacionadas a login de un participante
 */

if($_POST) {
    $formData = $_POST;
    $pl = null;
    try {
        $pl = new participante_login($formData);

        var_dump($pl->getParticipante());
        //-- Maybe Start Session and Store info?

    }catch(Exception $e) {
        echo "ERROR: ".$e->getMessage();
    }

}



/**
 * Class participante_login
 */

class participante_login {

    const ACTION_LOGIN_REGULAR = 'login';
    const ACTION_LOGIN_TOKEN = 'login_token';

    private $action;
    private $participante;

    public function __construct(Array $formData) {

        if(isset($formData["action"])) {

            try {
                $this->action = $formData["action"];

                switch($this->action) {
                    case self::ACTION_LOGIN_REGULAR: {
                        $this->participante = $this->loginRegular($formData);
                        break;
                    }
                    case self::ACTION_LOGIN_TOKEN: {
                        $this->participante = $this->loginToken($formData);
                        break;
                    }
                    default: {
                        break;
                    }
                }
            }catch(Exception $e) {
                throw $e;
            }

        }else{
            throw new Exception("[".get_class($this)."] Missing Action");
        }
    }

    /*
     * Login regular requiere de las variables email y password
     */
    private function loginRegular(Array $formData) {

        try {
            $this->validateRequiredFields($formData,['email','password']);
            $email = $formData['email'];
            $password = $formData['password'];

            $p_logic = New \Congreso\Logica\Participante();
            $p = $p_logic->loginParticipante($email,$password);
            return $p;
        }catch(Exception $e) {
            throw $e;
        }

    }
    /*
     * Login mediante token requiere que se pase un token valido
     */
    private function loginToken(Array $formData) {
        $this->validateRequiredFields($formData,['token']);
        $token = $formData['token'];

        $p_logic = new \Congreso\Logica\Participante();
        if($token_data = $p_logic->validateUserToken($token)) {
            $id = $token_data["id"];

            $p = $p_logic->obtenerParticipante($id);
            return $p;
        }else{
            throw new Exception("El Token de usuario no es valido");
        }

    }

    private function validateRequiredFields(Array $formData,Array $requiredFields)
    {
        $missing_fields = [];
        foreach($requiredFields as $rf) {
            if(!isset($rf,$formData)) {
                $missing_fields[] = $rf;
            }
        }
        if(count($missing_fields)>0) {
            throw new Exception("Campos requeridos faltantes: ".implode(",",$missing_fields));
        }
    }

    public function getParticipante() {
        return $this->participante;
    }
}