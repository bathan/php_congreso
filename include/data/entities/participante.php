<?php
namespace Congreso\entities;

require_once _CONGRESO_DATA_ACCESS_PATH;

use CongresoDataAccess;

class Participante {

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
    public $created_date;
    public $last_update;
    public $last_login;

    public function __construct() {

    }

    public function fromArray(Array $datos) {
        if(count($datos)>0) {
            $this->populateFromArray($this,$datos);
        }else{
            throw new \Exception("No hay información para popular al Participante");
        }
    }
    public function fromDatabase($id) {
        try {
            $db = new \CongresoDataAccess();
            $q = "select * from participantes where id='".$db->escape($id)."'";
            $db_resource = $db->executeAndFetchSingle($q);
            if($db_resource) {
                $this->populateFromArray($this,$db_resource);
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

    private function populateFromArray($class,$array) {
        foreach($array as $key=>$value) {

            if(property_exists($class,$key)) {
                $class->$key = $value;
            }
        }
    }

    public function toDatabase() {

        $db = new \CongresoDataAccess();

        $q = "INSERT INTO participantes (nombre,apellido,dni,localidad,email,nivel,password,created_date) values ";
        $q .= "(";
        $q .= "'".$db->escape($this->nombre)."',";
        $q .= "'".$db->escape($this->apellido)."',";
        $q .= "'".$db->escape($this->dni)."',";
        $q .= "'".$db->escape($this->localidad)."',";
        $q .= "'".$db->escape($this->email)."',";
        $q .= "'".$db->escape($this->nivel)."',";
        $q .= "'".$db->escape($this->password)."',";
        $q .= "CURRENT_TIMESTAMP)";

        $new_id = $db->execute($q,$num_rows,true);

        return $new_id;
    }

    public function update() {
        $db = new \CongresoDataAccess();

        $q = " UPDATE participantes SET ";
        $q .= "nombre='".$db->escape($this->nombre)."',";
        $q .= "apellido='".$db->escape($this->apellido)."',";
        $q .= "dni='".$db->escape($this->dni)."',";
        $q .= "localidad='".$db->escape($this->localidad)."',";
        $q .= "email='".$db->escape($this->email)."',";
        $q .= "nivel='".$db->escape($this->nivel)."',";
        $q .= "password='".$db->escape($this->password)."',";
        $q .= "last_update=CURRENT_TIMESTAMP ";
        $q .= "where id=".$this->id;

        $db->execute($q);
    }

    public function delete() {
        $db = new \CongresoDataAccess();
        $q = "delete from participantes where id=".$this->id;
        $db->execute($q);
    }

    public function fromDatabaseWithCredentials($email,$password) {
        try {
            $db = new \CongresoDataAccess();
            $q = "select * from participantes where email='".$db->escape($email)."' and password='".$db->escape($password)."'";
            $db_resource = $db->executeAndFetchSingle($q);
            if($db_resource) {
                $this->populateFromArray($this,$db_resource);
            }
        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function emailExists($email) {
        try {
            $db = new \CongresoDataAccess();
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

}