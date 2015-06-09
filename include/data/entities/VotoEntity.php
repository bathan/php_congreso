<?php
require_once _CONGRESO_DATA_ACCESS_PATH;
require_once _UTILITIES_PATH;

class VotoEntity {

    public $id;
    public $id_participante;
    public $id_trabajo;
    public $created_date;

    public function __construct() {

    }

    public function fromArray(Array $datos) {
        if(count($datos)>0) {
            Utilities::populateClassFromArray($this,$datos);
        }else{
            throw new \Exception("No hay información para popular el Voto");
        }
    }

    public function fromDatabase($id) {
        try {
            $db = new CongresoDataAccess();
            $q = "select * from votos where id='".$db->escape($id)."'";
            $db_resource = $db->executeAndFetchSingle($q);
            if($db_resource) {
                Utilities::populateClassFromArray($this,$db_resource);
            }else{
                throw new \Exception("No se encuentra el trabajo".$id,1000);
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
            throw new \Exception("No hay voto seleccionado");
        }
        return $json_string;
    }



    public function toDatabase() {

        $db = new CongresoDataAccess();

        $q = "INSERT INTO votos (id_participante,id_trabajo,created_date) values ";
        $q .= "(";
        $q .= "'".$db->escape($this->id_participante)."',";
        $q .= "'".$db->escape($this->id_trabajo)."',";
        $q .= "CURRENT_TIMESTAMP)";

        $new_id = $db->execute($q,$num_rows,true);

        return $new_id;
    }

    public static function listVotosByParticipante($id_participante) {
        try {

            $db = new CongresoDataAccess();

            $q = "select * from votos where id_particpante=".$id_participante;

            $db_resource = $db->executeAndFetch($q);
            $rows = [];
            if(count($db_resource)>0) {
                foreach($db_resource as $res) {
                    $p = new VotoEntity();
                    Utilities::populateClassFromArray($p,$res);
                    $rows[$res["id"]] = $p->toArray();
                }
            }

            return $rows;

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function listVotosByTrabajo($id_trabajo) {
        try {

            $db = new CongresoDataAccess();

            $q = "select * from votos where id_trabajo=".$id_trabajo;

            $db_resource = $db->executeAndFetch($q);
            $rows = [];
            if(count($db_resource)>0) {
                foreach($db_resource as $res) {
                    $p = new VotoEntity();
                    Utilities::populateClassFromArray($p,$res);
                    $rows[$res["id"]] = $p->toArray();
                }
            }

            return $rows;

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function getVotoByParticipanteAndTrabajo($id_participante,$id_trabajo) {
        try {
            $db = new CongresoDataAccess();
            $q = "select * from votos where id_participante='".$db->escape($id_participante)."' and id_trabajo='".$db->escape($id_trabajo)."'";
            $db_resource = $db->executeAndFetchSingle($q);
            if($db_resource) {
                $p = new VotoEntity();
                Utilities::populateClassFromArray($p,$db_resource);
                return $p;
            }else{
                return null;
            }

        }catch(\Exception $e) {
            throw $e;
        }
    }



}