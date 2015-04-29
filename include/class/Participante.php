<?php
namespace Congreso\Logica;


class Participante {

    const PASSWORD_LENGTH = 8;

    public function __construct() {

    }
    /*
     * Agrega un participante a la bbdd creando un password usando el email y DNI como SALT
     */
    public function agregarParticipante(Array $datos) {

        try {
            //-- Definimos un password inicial
            $datos['password'] = $this->createInitialPassword($datos);

            //-- Validamos los datos
            $this->validarDatosParticipante($datos);

            //-- Insertar en la bbdd
            $p = new \Congreso\entities\Participante();
            $p->fromArray($datos);
            $new_id = $p->toDatabase();

            //-- Enviamos email al participante avisando que se dió de alta correctamente
            $this->sendWelcomeEmail($new_id);

            return $new_id;
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

        if(\Congreso\entities\Participante::emailExists($datos["email"])) {
            throw new \Exception("Error validando datos del participante. El email ya está registrado.");
        }

        //-- Acomodamos las mayusculas/minusculas de algunos campos
        $campos = ['localidad','nombre','apellido'];
        foreach($campos as $c) {
            if(isset($datos[$c]) && strlen($datos[$c])>0) {
                $datos[$c] = ucwords(strtolower($datos[$c]));
            }
        }

        return;
    }

    /*
     * Login del participante usando el email y el password
     */
    public function loginParticipante($email,$password) {

        try {
            $p = new \Congreso\entities\Participante();
            $p->fromDatabaseWithCredentials($email,$password);

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
        $p = new \Congreso\entities\Participante();
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
        preg_replace("/[^A-Za-z0-9 ]/", '', $string);

        //-- Devolvemos una porción del string
        return substr(str_shuffle($string),0,$length);

    }


    /*
     * Generar un token de usuario que será utilizado para operaciones via email
     */
    public function getUserToken($id) {

        try {
            $p = $this->obtenerParticipante($id);

            $data_para_token = ["id"=>$id,"email"=>$p["email"]];

            $token = Utilities::generate_signed_request($data_para_token,_ENCODING_SECRET);

            return $token;

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
            return $data_from_token;
        }catch(\Exception $e) {
            return false;
        }
    }

    /*
     * Envía email de bienvenida al usuario
     */

    public function sendWelcomeEmail($id) {

    }

    /*
     * Obtiene un Array con los datos de un participante
     */
    public function obtenerParticipante($id) {
        $p = new \Congreso\entities\Participante();
        $p->fromDatabase($id);
        return $p->toArray();
    }

    /*
     * Elimina un participante
     */
    public function eliminarParticipante($id) {
        if(intval($id) >0 ) {
            $p = new \Congreso\entities\Participante();
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
            return \Congreso\entities\Participante::listParticipantes($from,$limit,$filtros,$orden);
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
            return \Congreso\entities\Participante::listParticipantes(0,null,$filtros,null,true);
        }catch(\Exception $e) {
            throw $e;
        }
    }

    private function validarFiltros(Array $filtros) {
        if($filtros && count($filtros)>0) {
            //-- Validar que no manden fitros cualquiera
            $valid_filters = ['nombre','apellido','dni','localidad','email','nivel'];

            foreach($filtros as $columna=>$valor) {
                if(!in_array($columna,$valid_filters)) {
                    throw new \Exception("Filtro ".$columna." es invalido");
                }
            }
        }
    }


}

