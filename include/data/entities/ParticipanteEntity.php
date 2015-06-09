<?php
require_once _CONGRESO_DATA_ACCESS_PATH;
require_once _UTILITIES_PATH;

class ParticipanteEntity {

    const NIVEL_PRIMARIO = 'Primario';
    const NIVEL_SECUNDARIO = 'Secundario';
    const NIVEL_ESTUDIANTES = 'Estudiantes';
    const NIVEL_OTROS = 'Otros';

    public $id;
    public $nombre;
    public $apellido;
    public $dni;
    public $localidad;
    public $email;
    public $nivel;
    public $password;
    public $escuela;
    public $created_date;
    public $last_update;
    public $last_login;
    public $user_token;

    public function __construct() {

    }

    public function fromArray(Array $datos) {
        if(count($datos)>0) {
            Utilities::populateClassFromArray($this,$datos);
        }else{
            throw new \Exception("No hay información para popular al Participante");
        }
    }

    public function fromDatabase($id) {
        try {
            $db = new CongresoDataAccess();
            $q = "select * from participantes where id='".$db->escape($id)."'";
            $db_resource = $db->executeAndFetchSingle($q);
            if($db_resource) {
                Utilities::populateClassFromArray($this,$db_resource);
            }else{
                throw new \Exception("No se encuentra al participante ".$id,1000);
            }

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public function fromDatabaseByEmail($email) {
        try {
            $db = new CongresoDataAccess();
            $q = "select * from participantes where LOWER(email)='".$db->escape(strtolower($email))."'";
            $db_resource = $db->executeAndFetchSingle($q);
            if($db_resource) {
                Utilities::populateClassFromArray($this,$db_resource);
            }else{
                throw new \Exception("No se encuentra al participante con email ".$email,1000);
            }

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public function toArray() {
        return json_decode($this->toJSON(),true);
    }

    public function toJSON() {
        if($this->id > 0) {
            $json_string = json_encode(get_object_vars($this),JSON_PRETTY_PRINT);
        }else{
            throw new \Exception("No hay participante seleccionado");
        }
        return $json_string;
    }



    public function toDatabase() {

        $db = new CongresoDataAccess();

        $q = "INSERT INTO participantes (nombre,apellido,dni,localidad,escuela,email,nivel,password,created_date) values ";
        $q .= "(";
        $q .= "'".$db->escape($this->nombre)."',";
        $q .= "'".$db->escape($this->apellido)."',";
        $q .= "'".$db->escape($this->dni)."',";
        $q .= "'".$db->escape($this->localidad)."',";
        $q .= "'".$db->escape($this->escuela)."',";
        $q .= "'".$db->escape($this->email)."',";
        $q .= "'".$db->escape($this->nivel)."',";
        $q .= "'".$db->escape($this->password)."',";
        $q .= "CURRENT_TIMESTAMP)";

        $new_id = $db->execute($q,$num_rows,true);

        return $new_id;
    }

    public function update() {
        $db = new CongresoDataAccess();

        $q = " UPDATE participantes SET ";
        $q .= "nombre='".$db->escape($this->nombre)."',";
        $q .= "apellido='".$db->escape($this->apellido)."',";
        $q .= "dni='".$db->escape($this->dni)."',";
        $q .= "localidad='".$db->escape($this->localidad)."',";
        $q .= "escuela='".$db->escape($this->escuela)."',";
        $q .= "email='".$db->escape($this->email)."',";
        $q .= "nivel='".$db->escape($this->nivel)."',";
        $q .= "password='".$db->escape($this->password)."',";
        $q .= "user_token='".$db->escape($this->user_token)."',";
        $q .= "last_login='".$db->escape($this->last_login)."',";
        $q .= "last_update=CURRENT_TIMESTAMP ";
        $q .= "where id=".$this->id;

        $db->execute($q);
    }

    public function delete() {
        $db = new CongresoDataAccess();

        $q = " insert into participantes_borrados  select *,CURRENT_TIMESTAMP as deleted_date from participantes where id=".$this->id;
        $db->execute($q);
        $q = "delete from participantes where id=".$this->id;
        $db->execute($q);
    }

    public function fromDatabaseWithCredentials($email,$password) {
        try {
            $db = new CongresoDataAccess();
            $q = "select * from participantes where email='".$db->escape($email)."' and password='".$db->escape($password)."'";
            $db_resource = $db->executeAndFetchSingle($q);
            if(!is_null($db_resource)) {
                Utilities::populateClassFromArray($this,$db_resource);
            }else{
                throw new \Exception("Credenciales incorrectas");
            }
        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function emailExists($email) {
        try {
            $db = new CongresoDataAccess();
            $q = "select * from participantes where LOWER(email)='".$db->escape(strtolower($email))."'";
            $db_resource = $db->executeAndFetchSingle($q);
            if($db_resource) {
               return true;
            }else{
                return false;
            }
        }catch(\Exception $e) {
            throw $e;
        }
    }

    /*
     * Devuelve una lista de objetos Participante en base a los filtros provitos
     */
    public static function listParticipantes($from=0,$limit=_DEFAULT_LIST_LIMIT,Array $filtros=null,Array $orden = null,$count=false) {
        try {
            $db = new CongresoDataAccess();

            $response_array = ["orderby"=>$orden,"rows"=>[]];

            if($count) {
                $q = "select count(*) as the_count from participantes";
            }else{
                $q = "select * from participantes ";
            }

            $q .= " WHERE 1=1 ";

            $magic_filter_value = null;

            if(!is_null($filtros) && count($filtros)>0) {


                $filter_array = [];

                foreach($filtros as $campo=>$valor) {
                    if($campo=='magic') {
                        $magic_filter_value = $valor;
                        continue;
                    }
                    $filter_array[]= $campo." like '%".$valor."%'";
                }

                if(count($filter_array)>0) {
                    $q .= " AND ";
                    $q .= implode(' AND ',$filter_array);
                }

            }

            if($magic_filter_value) {
                //-- Find by all fields
                $q .= " AND ( ";

                $q .= " ( nombre like '%$magic_filter_value%' ) OR ";
                $q .= " ( apellido like '%$magic_filter_value%' ) OR ";
                $q .= " ( dni like '%$magic_filter_value%' ) OR ";
                $q .= " ( localidad like '%$magic_filter_value%' ) OR";
                $q .= " ( escuela like '%$magic_filter_value%' ) ";
                $q .= " ) ";
            }


            if(!$count && $orden && count($orden)>0) {
                if($orden["c"]=='nivel') {
                    $orden["c"] = ' CAST(nivel as char) ';
                }
                $q .=" ORDER BY ".$orden["c"]." ".$orden["d"]." ";
            }

            if(!$count && $limit > 0) {
                if($from==0) {
                    $q .= " LIMIT ".$limit;
                }else{
                    $q .= " LIMIT ".$from.",".$limit;
                }
            }

            if(_APP_DEBUG) {
            //    echo "<pre>".$q."</pre>";
            }

            if($count) {
                $db_resource = $db->executeAndFetchSingle($q);
                $response_array = $db_resource["the_count"];
            }else{
                $db_resource = $db->executeAndFetch($q);
                if(count($db_resource)>0) {
                    $rows = [];
                    foreach($db_resource as $res) {
                        $p = new ParticipanteEntity();
                        Utilities::populateClassFromArray($p,$res);
                        $rows[$res["id"]] = $p->toArray();
                    }

                    $response_array["rows"] = $rows;
                }
            }

            return $response_array;

        }catch(\Exception $e) {
            throw $e;
        }
    }

}