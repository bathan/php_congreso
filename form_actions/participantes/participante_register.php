<?php
require_once __DIR__ . '/../form_action_base.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _TRABAJO_LOGIC_PATH;
require_once _UTILITIES_PATH;

/**
 * Class participante_register
 */

class participante_register extends form_action_base{


    private $participante;

    public function __construct(Array $formData,$files=null)
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
                case self::ACTION_DELETE_USER: {
                    $this->deleteUser($formData);
                    break;
                }
                case self::ACTION_EMAIL_USERS: {
                    $this->emailUsers($formData);
                    break;
                }
                case self::ACTION_UPLOAD_TRABAJO: {
                    $this->uploadTrabajo($formData,$files);
                    break;
                }
                case self::ACTION_VOTE: {
                    $this->voteTrabajo($formData);
                    break;
                }
                case self::ACTION_COMMENT_TRABAJO: {
                    $this->commentTrabajo($formData);
                    break;
                }
                case self::ACTION_SEND_COMMENT_TRABAJO: {
                    $this->sendTrabajoComment($formData);
                    break;
                }
                case self::ACTION_SEND_INIT_EMAIL: {
                    $this->sendInitEmail($formData);
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

    private function deleteUser(Array $formData) {
        try {

            $p_logic = new ParticipanteLogic();

            $user_token = $p_logic->validateUserToken($formData['user_token']);

            if(is_null($user_token)) {
                throw new Exception("Token de usuario invalido");
            }

            $p_logic->eliminarParticipante($user_token["id"]);

            $this->result = ["status"=>"ok"];

        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }
    }

    private function emailUsers($formData) {

        try {
            $p_logic = new ParticipanteLogic();

            $nivel_to_email = $formData['nivel'];

            $filters = [];
            if($nivel_to_email!='all') {
                $filters['nivel'] = $nivel_to_email;
            }

            $lista = $p_logic->listParticipantes(0,-1,$filters);

            $emails_enviados = [];

            foreach($lista["rows"] as $p) {
                $subject = $formData['subject'];
                $body = $formData['body'];

                $nombre_y_apellido = $p["nombre"]." ".$p["apellido"];

                foreach($p as $column=>$value) {
                    $body = str_replace("[".strtolower($column)."]",$value,$body);
                }
                $html_body = nl2br($body);
                $emails_enviados[] = ["recipient"=>$p["email"],"body_plain"=>$body,"body_html"=>$html_body];

                Utilities::sendEmail($p["email"],$nombre_y_apellido,$html_body,$body,$subject);
            }

            $this->result = ["status"=>"ok","emails_enviados"=>$emails_enviados];


        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }
    }

    private function uploadTrabajo($formData,$filesData) {

        try {

            $tl = new TrabajoLogic();
            $new_trabajo_id = $tl->agregarTrabajo($formData,$filesData);
            $email_enviado = $tl->enviarEmailTrabajoRecibido($new_trabajo_id);

            $this->result = ["status"=>"ok","trabajo_id"=>$new_trabajo_id,"email_enviado"=>$email_enviado];

        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }
    }

    private function voteTrabajo($formData) {

        try {

            //-- Validar Datos de Regirstro
            $this->validateRequiredFields(['id_participante','id_trabajo']);

            $id_participante = $formData["id_participante"];
            $id_trabajo = $formData["id_trabajo"];

            $tl = new TrabajoLogic();
            $tl->votarTrabajo($id_participante,$id_trabajo);

            $this->result = ["status"=>"ok"];

        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }
    }

    private function commentTrabajo($formData) {

        try {

            //-- Validar Datos de Regirstro
            $this->validateRequiredFields(['id_trabajo','comments']);

            $id_trabajo = $formData["id_trabajo"];
            $comment = $formData["comments"];

            $tl = new TrabajoLogic();
            $tl->comentarTrabajo($id_trabajo,$comment);

            $this->result = ["status"=>"ok"];

        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }
    }

    private function sendTrabajoComment($formData) {

        try {

            //-- Validar Datos de Regirstro
            $this->validateRequiredFields(['id_trabajo','comments']);

            $id_trabajo = $formData["id_trabajo"];
            $comment = $formData["comments"];

            //-- Guardamos la devolución para luego enviarla
            $tl = new TrabajoLogic();
            $tl->comentarTrabajo($id_trabajo,$comment);

            $tl->enviarDevolucionTrabajo($id_trabajo);

            $this->result = ["status"=>"ok"];

        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }
    }

    public function getParticipante() {
        return $this->participante;
    }

    public function sendInitEmail($formData) {
        try {

            $pl = new ParticipanteLogic();

            //-- Enviar email a todos los participantes
            $participantes = $pl->listParticipantes(0,-1);
            $emails_enviados = 0;
            foreach($participantes["rows"] as $p) {
                $id_participante = $p['id'];
                if($pl->sendSiteIsOpenEmail($id_participante)) {
                    $emails_enviados++;
                }
            }

            $this->result = ["status"=>"ok","emails_sent"=>$emails_enviados];

        }catch(Exception $e) {
            $this->result = ["status"=>"error","data"=>$e->getMessage(),"code"=>$e->getCode()];
        }
    }
}
