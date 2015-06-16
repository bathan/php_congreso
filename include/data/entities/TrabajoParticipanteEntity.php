<?php
require_once _CONGRESO_DATA_ACCESS_PATH;
require_once _UTILITIES_PATH;

class TrabajoParticipanteEntity {

    public $id_trabajo;
    public $id_participante;
    public $titulo_trabajo;
    public $votos;
    public $nombre;
    public $apellido;
    public $nivel;

    public function __construct() {

    }

    public function fromArray(Array $datos) {
        if(count($datos)>0) {
            Utilities::populateClassFromArray($this,$datos);
        }else{
            throw new \Exception("No hay información para popular al Participante");
        }
    }


    public function toArray() {
        return json_decode($this->toJSON(),true);
    }

    public function toJSON() {

        $json_string = json_encode(get_object_vars($this),JSON_PRETTY_PRINT);
        return $json_string;
    }

}