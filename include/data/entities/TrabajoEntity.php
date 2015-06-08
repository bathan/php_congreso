<?php
require_once _CONGRESO_DATA_ACCESS_PATH;
require_once _UTILITIES_PATH;

class TrabajoEntity {

    public $id;
    public $id_participante;
    public $created_date;
    public $nombre_original;
    public $nombre_fs;
    public $titulo_trabajo;
    public $votos;

    public function __construct() {

    }

    public function fromArray(Array $datos) {
        if(count($datos)>0) {
            Utilities::populateClassFromArray($this,$datos);
        }else{
            throw new \Exception("No hay información para popular el Trabajo");
        }
    }
    public function fromDatabase($id) {
        try {
            $db = new CongresoDataAccess();
            $q = "select * from trabajos where id='".$db->escape($id)."'";
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
            throw new \Exception("No hay participante seleccionado");
        }
        return $json_string;
    }



    public function toDatabase() {

        $db = new CongresoDataAccess();

        $q = "INSERT INTO trabajos (id_participante,nombre_original,nombre_fs,titulo_trabajo,created_date) values ";
        $q .= "(";
        $q .= "'".$db->escape($this->id_participante)."',";
        $q .= "'".$db->escape($this->nombre_original)."',";
        $q .= "'".$db->escape($this->nombre_fs)."',";
        $q .= "'".$db->escape($this->titulo_trabajo)."',";
        $q .= "CURRENT_TIMESTAMP)";

        $new_id = $db->execute($q,$num_rows,true);

        return $new_id;
    }

    public function update() {
        $db = new CongresoDataAccess();

        $q = " UPDATE trabajos SET ";
        $q .= "nombre_original='".$db->escape($this->nombre_original)."',";
        $q .= "nombre_fs='".$db->escape($this->nombre_fs)."',";
        $q .= "titulo_trabajo='".$db->escape($this->titulo_trabajo)."',";
        $q .= "votos='".$db->escape($this->votos)."',";
        $q .= "last_update=CURRENT_TIMESTAMP ";
        $q .= "where id=".$this->id;

        $db->execute($q);
    }


    public static function fromDatabaseByParticipante($id_participante) {
        try {
            $db = new CongresoDataAccess();
            $q = "select * from trabajos where id_participante='".$db->escape($id_participante)."'";
            $db_resource = $db->executeAndFetchSingle($q);
            if(!is_null($db_resource)) {
                $x = new TrabajoEntity();
                Utilities::populateClassFromArray($x,$db_resource);
                return $x->toArray();
            }else{
                return null;
            }
        }catch(\Exception $e) {
            throw $e;
        }
    }


    /*
     * Devuelve una lista de objetos Participante en base a los filtros provitos
     */
    public static function listTrabajos($from=0,$limit=_DEFAULT_LIST_LIMIT,Array $filtros=null,Array $orden = null,$count=false) {
        try {
            $db = new CongresoDataAccess();

            $response_array = ["orderby"=>$orden,"rows"=>[]];

            if($count) {
                $q = "select count(*) as the_count from trabajos";
            }else{
                $q = "select * from trabajos ";
            }

            $q .= " WHERE 1=1 ";

            $magic_filter_value = null;

            if(!is_null($filtros) && count($filtros)>0) {


                $filter_array = [];

                foreach($filtros as $campo=>$valor) {
                    $filter_array[]= $campo." like '%".$valor."%'";
                }

                if(count($filter_array)>0) {
                    $q .= " AND ";
                    $q .= implode(' AND ',$filter_array);
                }

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