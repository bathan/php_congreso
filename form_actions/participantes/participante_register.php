<?php
require_once __DIR__ . '/../form_action_base.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _UTILITIES_PATH;

/**
 * Class participante_register
 */

class participante_register extends form_action_base{


    private $participante;

    public function __construct(Array $formData)
    {

        try {
            parent::__construct($formData);

            switch ($this->action) {
                case self::ACTION_REGISTER: {
                    $this->registerParticipante($formData);
                    break;
                }
                case self::ACTION_UPDATE_INFO: {
                    $this->updateParticipanteInfo($formData);
                    break;
                }

                default: {
                    break;
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /*
     * Login regular requiere de las variables email y password
     */
    private function registerParticipante(Array $formData) {

        try {

            //-- Validar Datos de Regirstro
            $this->validateRequiredFields(['nombre','apellido','dni','email']);

            //-- Validar Email
            if(!Utilities::isValidEmail($formData["email"])) {
                throw new Exception("Dirección de Email Invalida");
            }

            $p_logic = New ParticipanteLogic();
            $id_nuevo = $p_logic->agregarParticipante($formData);
            $p = $p_logic->obtenerParticipante($id_nuevo);

            $this->result = ["status"=>"ok","participante_token"=>$p["user_token"]];
        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }

    }

    private function updateParticipanteInfo(Array $formData) {

        try {

            //-- Validar Datos de Regirstro
            $this->validateRequiredFields(['user_token']);

            //-- Validate that this token is from today
            $p_logic = new ParticipanteLogic();
            $user_token = $p_logic->validateUserToken($formData['user_token']);

            if(is_null($user_token)) {
                throw new Exception("Token de usuario invalido");
            }

            //-- Validar Email
            if((isset($formData["email"]) && $formData["email"] != '') && !Utilities::isValidEmail($formData["email"])) {
                throw new Exception("Dirección de Email Invalida");
            }

            $p_logic->actualizarParticipante($user_token["id"],$formData);

            //-- Verificamos si se cambió el token y en tal caso enviamos el nuevo
            $new_token = $p_logic->getUserToken($user_token["id"]);

            $this->result = ["status"=>"ok"];

            if($new_token!= $formData["user_token"]) {
                $this->result["user_token"] = $new_token;
            }

        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }

    }

    public function getParticipante() {
        return $this->participante;
    }
}
