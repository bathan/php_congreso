<?php
require_once _PARTICIPANTE_ENTITY_PATH;
require_once _TRABAJO_ENTITY_PATH;
require_once _TRABAJO_PARTICIPANTE_ENTITY_PATH;

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

        /*
        if(strtolower($datos["nivel"])==strtolower('Estudiantes')) {
            throw new \Exception("No es posible inscribirse. Se ha completado el cupo para Estudiantes.");
        }
        */

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
    public function sendSiteIsOpenEmail($id) {

        $participante = $this->obtenerParticipante($id);
        $nombre_y_apellido = $participante["nombre"]." ".$participante["apellido"];

        $body_html = '[nombre],<br/><br>

Te informamos que ya se encuentra disponible el área de usuarios de la Plataforma Congreso UTELPa.<br>
Para acceder a la misma, al final de este mail te enviamos un usuario y password personal, el cual recomendamos no compartir. Una vez que ingreses a dicha sección dentro de la plataforma, encontrarás 2 nuevas pestañas en el menú de navegación llamadas "EXPERIENCIAS PEDAGÓGICAS" y "EJES TEMÁTICOS".<br><br>

Dentro de la pestaña "EXPERIENCIAS PEDAGÓGICAS" encontrarás los requisitos para acreditar al Congreso, subir tu trabajo a la plataforma, leer los trabajos de otros usuarios de tu mismo nivel y votarlos.<br><br>

Dentro de la pestaña "EJES TEMÁTICOS" podrás acceder a cada uno de los 4 ejes de trabajo, en los cuales encontrarás el material de lectura propuesto para participar de la Consigna de cada Eje dentro del Foro de discución.<br><br>

Para acceder a la sección de usuario seguí los siguientes pasos.<br><br>

1. Tomá nota de tu usuario: <strong>[email]</strong>  y tu password: <strong>[password]</strong><br>
2. Hace click <a href="http://www.congresoutelpa.com.ar/login.php">acá</a> e introducí el usuario y password del item anterior.<br><br>

Por favor te recomendamos chequear regularmente el correo electrónico, ya que es el principal medio que utilizaremos como vía de contacto.<br><br>

Saludos cordiales,<br>
<strong>UTELPa</strong><br>';

        foreach($participante as $col=>$val) {
            $body_html = str_replace("[".$col."]",$val,$body_html);
        }

        $body_plain  = preg_replace('#<br\s*/?>#i', "\n", $body_html);
        $body_plain = strip_tags($body_plain);

        $email_sent = false;
        try {
            $email_sent =Utilities::sendEmail($participante["email"],$nombre_y_apellido,$body_html,$body_plain,'Congreso UTELPa 2015, Área de usuarios disponible.');
        }catch(Exception $e) {

        }

        return $email_sent;

    }

    public function sendForgotPasswordEmail($participante) {

        $nombre_y_apellido = $participante["nombre"]." ".$participante["apellido"];

        $body_html = "[nombre],<br/><br/>
                      Recib&iacute;s este correo porque solicitaste <strong>RECUPERAR TU CONTRASEÑA</strong> en www.congresoutelpa.com.ar<br><br>
                      Te recordamos a continuación tu contraseña<br/><br/>
                      Usuario: [email]<br>
                      Clave: [password]<br><br>Saludos cordiales,<br><br>UTELPa.";

        $body_plain = "[nombre],\n\n
                      Recibís este correo porque solicitaste RECUPERAR TU CONTRASEÑA en www.congresoutelpa.com.ar\n\n
                      Te recordamos a continuación tu contraseña\n\n
                      Usuario: [email]\n
                      Clave: [password]\n\nSaludos cordiales,\n\nUTELPa.";

        foreach($participante as $key=>$val) {
            $body_html = str_replace("[".$key."]",$val,$body_html);
            $body_plain = str_replace("[".$key."]",$val,$body_plain);
        }


        try {
            $subject = '=?UTF-8?Q?' . quoted_printable_encode('Recupero de contraseña UTELPa.') . '?=';
            Utilities::sendEmail($participante["email"],$nombre_y_apellido,$body_html,$body_plain,$subject);
        }catch(Exception $e) {
            throw $e;
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
 * Obtiene un Array con los datos de un participante
 */
    public function obtenerParticipantePorEmail($email) {
        $p = new ParticipanteEntity();
        $p->fromDatabaseByEmail($email);
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

        if(!is_null($filtros)) {
            $this->validarFiltros($filtros);

        }

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
     * Devuelve una lista de participantes acorde a los filtros seleccionados pero si o si tienen que tener algún trabajo subido
     * @param int $from
     * @param $limit
     * @param array $filtros
     * @return array
     * @throws \Exception
     */
    public function listParticipantesConTrabajos($from=0,$limit=_DEFAULT_LIST_LIMIT,Array $filtros=null,Array $orden = null) {

        $this->validarFiltros($filtros);

        try {

            if(is_null($orden)) {
                $orden = ["c"=>"id","d"=>"ASC"];
            }

            return ParticipanteEntity::listParticipantesConTrabajos($from,$limit,$filtros,$orden);

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
    /**
     * Devuelve el total de participantes segun los filtros seleccionados
     * @param int $from
     * @param $limit
     * @param array $filtros
     * @return array
     * @throws \Exception
     */
    public function countParticipantesConTrabajos(Array $filtros=null) {

        $this->validarFiltros($filtros);

        try {

            return ParticipanteEntity::listParticipantesConTrabajos(0,null,$filtros,null,true);
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

    public function tieneTrabajoSubido($id_participante) {
        $trabajo_entity = TrabajoEntity::fromDatabaseByParticipante($id_participante);
        return (!is_null($trabajo_entity));
    }



}

