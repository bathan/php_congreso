<?php
require_once _PARTICIPANTE_ENTITY_PATH;

class ParticipanteLogic {

    const PASSWORD_LENGTH = 8;

    public function __construct() {

    }
    /*
     * Agrega un participante a la bbdd
     */
    public function agregarParticipante(Array $datos) {

        try {

            //-- Definimos un password inicial
            $datos['password'] = $this->createInitialPassword($datos);

            //-- Validamos los datos
            $this->validarDatosParticipante($datos);

            //-- Insertar en la bbdd
            $p = new ParticipanteEntity();

            $p->fromArray($datos);

            $new_id = $p->toDatabase();

            //-- Creamos el Token del Usuario
            $this->actualizarParticipante($new_id,['user_token'=>$this->createUserToken($new_id)]);

            //-- Enviamos email al participante avisando que se dió de alta correctamente
            $this->sendWelcomeEmail($new_id);

            return $new_id;

        }catch(\Exception $e) {
            throw $e;
        }

    }

    public function actualizarParticipante($id,Array $datos_a_actualizar) {
        try {

            //-- Insertar en la bbdd
            $p = new ParticipanteEntity();
            $p->fromDatabase($id);

            foreach($datos_a_actualizar as $d=>$v) {
                if(property_exists($p,$d) && $v != '') {
                    $p->$d=$v;
                }
            }

            $p->update();

            //-- Si mandó a actualizar el Email tenemos que volver a generarle el token
            if(isset($datos_a_actualizar['email']) && $datos_a_actualizar['email']!='') {
                $p->user_token = $this->createUserToken($id);
                $p->update();
            }


        }catch(\Exception $e) {
            throw $e;
        }
    }
    private function validarDatosParticipante(Array &$datos) {

        //-- Revisar que no falten datos requeridos
        $datos_requeridos = ['nombre','apellido','dni','email'];

        $datos_faltantes = [];

        foreach($datos_requeridos as $dr) {
            if(!in_array($dr,$datos_requeridos)) {
                $datos_faltantes[] = $dr;
            }
        }

        if(count($datos_faltantes)>0) {
            throw new \Exception("Error validando datos del participante. Faltan los siguientes datos: ".implode(",",$datos_faltantes));
        }

        if(!Utilities::isValidEmail($datos["email"])) {
            throw new \Exception("Error validando datos del participante. El email no es valido.");
        }

        if(ParticipanteEntity::emailExists($datos["email"])) {
            throw new \Exception("Error validando datos del participante. El email ya se encuentra registrado.");
        }

        //-- Acomodamos las mayusculas/minusculas de algunos campos
        /*
        $campos = ['localidad','nombre','apellido'];
        foreach($campos as $c) {
            if(isset($datos[$c]) && strlen($datos[$c])>0) {
                $datos[$c] = ucwords(strtolower($datos[$c]));
            }
        }
        */

        return;
    }

    /*
     * Login del participante usando el email y el password
     */
    public function loginParticipante($email,$password) {

        try {
            $p = new ParticipanteEntity();
            $p->fromDatabaseWithCredentials($email,$password);
            $this->actualizarParticipante($p->id,['last_login'=>date("Y-m-d H:i:s")]);

            return $p->toArray();
        }catch(\Exception $e) {
            throw $e;
        }
    }

    /*
     * Cambiar el password del participante
     */
    public function changePassword($id,$oldPassword,$newPassword) {
        //-- Obtenemos el usuario por id
        $p = new ParticipanteEntity();
        $p->fromDatabase($id);

        //-- Validamos contraseña vieja vs nueva
        if($p->password != $oldPassword) {
            throw new \Exception("La contraseña anterior no es correcta.");
        }

        //-- Actualizamos el participante con la nueva contraseña
        $p->password = $newPassword;
        $p->update();
    }

    /*
     * Crear un password random para el usuario
     */
    public function createInitialPassword($datos,$length=self::PASSWORD_LENGTH) {

        $string = '';
        //-- Armamos un gran string con todos los datos del usuario concatenados
        foreach($datos as $k=>$v) {
            $string .= $v;
        }
        //-- Quitamos caracteres NO alfanumericos
        $string = preg_replace("/[^A-Za-z0-9 ]/", '', $string);

        //-- Quitamos espacios
        $string = str_replace(' ','',$string);
        

        //-- Devolvemos una porción del string
        return substr(str_shuffle($string),0,$length);

    }


    /*
     * Generar un token de usuario que será utilizado para operaciones via email
     */
    public function createUserToken($id) {

        try {
            $p = $this->obtenerParticipante($id);

            $data_para_token = ["id"=>$id,"email"=>$p["email"],"created"=>date("Y-m-d H:i:s"),"env_secret"=>_TOKEN_SECRET];

            $token = Utilities::generate_signed_request($data_para_token,_ENCODING_SECRET);

            return $token;

        }catch(\Exception $e) {
            throw $e;
        }

    }

    /*
    * Generar un token de usuario que será utilizado para operaciones via email
    */
    public function getUserToken($id) {

        try {
            $p = $this->obtenerParticipante($id);
            return $p['user_token'];

        }catch(\Exception $e) {
            throw $e;
        }

    }

    /*
     * Validar el token del usuario
     */
    public function validateUserToken($token) {
        try {
            $data_from_token = Utilities::parse_signed_request($token,_ENCODING_SECRET);
            if($data_from_token['env_secret']!=_TOKEN_SECRET) {
                return null;
            }
            return $data_from_token;
        }catch(\Exception $e) {
            return false;
        }
    }

    /*
     * Envía email de bienvenida al usuario
     */

    public function sendWelcomeEmail($id) {

        $participante = $this->obtenerParticipante($id);
        $nombre_y_apellido = $participante["nombre"]." ".$participante["apellido"];

        $body_html = "[NOMBRE],<br/><br>Gracias por inscribirte al Congreso Pedag&oacute;gico UTELPa 2015. ¡Felicitaciones!<br/><br/>Pr&oacute;ximamente, cuando ya se encuentre disponible la Plataforma de trabajo, te estaremos enviando un mail con un usuario y una clave personal para que puedas ingresar a la misma.<br/><br/>Cordialmente.<br/><br/><strong>UTELPa.</strong>";
        $body_plain = "[NOMBRE]\nGracias por inscribirte al Congreso Pedagógico UTELPa 2015. ¡Felicitaciones!\nPróximamente, cuando ya se encuentre disponible la Plataforma de trabajo, te estaremos enviando un mail con un usuario y una clave personal para que puedas ingresar a la misma.\nCordialmente.\nUTELPa.";

        $body_html = str_replace('[NOMBRE]',$participante["nombre"],$body_html);
        $body_plain = str_replace('[NOMBRE]',$participante["nombre"],$body_plain);

        try {
            Utilities::sendEmail($participante["email"],$nombre_y_apellido,$body_html,$body_plain,'Bienvenida/o al Congreso UTELPa 2015.');
        }catch(Exception $e) {

        }


    }

    /*
     * Obtiene un Array con los datos de un participante
     */
    public function obtenerParticipante($id) {
        $p = new ParticipanteEntity();
        $p->fromDatabase($id);
        return $p->toArray();
    }

    /*
     * Elimina un participante
     */
    public function eliminarParticipante($id) {
        if(intval($id) >0 ) {
            $p = new ParticipanteEntity();
            $p->fromDatabase($id);
            $p->delete();
        }else{
            throw new \Exception("No ha provisto id de participante a eliminar");
        }

    }

    /**
     * Devuelve una lista de participantes acorde a los filtros seleccionados
     * @param int $from
     * @param $limit
     * @param array $filtros
     * @return array
     * @throws \Exception
     */
    public function listParticipantes($from=0,$limit=_DEFAULT_LIST_LIMIT,Array $filtros=null,Array $orden = null) {

        $this->validarFiltros($filtros);

        try {
            if(is_null($orden)) {
                $orden = ["c"=>"id","d"=>"ASC"];
            }
            return ParticipanteEntity::listParticipantes($from,$limit,$filtros,$orden);
        }catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Devuelve el total de participantes segun los filtros seleccionados
     * @param int $from
     * @param $limit
     * @param array $filtros
     * @return array
     * @throws \Exception
     */
    public function countParticipantes(Array $filtros=null) {

        $this->validarFiltros($filtros);

        try {
            return ParticipanteEntity::listParticipantes(0,null,$filtros,null,true);
        }catch(\Exception $e) {
            throw $e;
        }
    }

    private function validarFiltros(Array $filtros) {
        if($filtros && count($filtros)>0) {
            //-- Validar que no manden fitros cualquiera
            $valid_filters = ['nombre','apellido','dni','localidad','email','nivel','magic'];

            foreach($filtros as $columna=>$valor) {
                if(!in_array($columna,$valid_filters)) {
                    throw new \Exception("Filtro ".$columna." es invalido");
                }
            }
        }
    }

    public function getPartipanteCounts() {

        $pe = New ParticipanteEntity();

        $list = $pe->listParticipantes(0,-1);

        $counts = ["localidades"=>0,"escuelas"=>0,"participantes"=>0];

        $escuelas = [];
        $localidades = [];
        $niveles = [];
        $counts["participantes"] = count($list["rows"]);

        foreach($list["rows"] as $p_id=>$p) {
            $escuelas[] = strtolower($p["escuela"]);
            $localidades[] = strtolower($p["localidad"]);
            @$niveles[$p["nivel"]]++;
        }

        $escuelas = array_unique($escuelas);
        $localidades = array_unique($localidades);

        $counts["localidades"] = count($localidades);
        $counts["escuelas"] = count($escuelas);
        $counts["niveles"] = $niveles;

        return $counts;

    }



}

