<?php
require_once _PARTICIPANTE_ENTITY_PATH;
require_once _TRABAJO_ENTITY_PATH;
require_once _VOTO_ENTITY_PATH;

class TrabajoLogic {

    public function __construct() {

    }
    /*
     * Agrega un participante a la bbdd
     */
    public function agregarTrabajo(Array $formData,Array $filesData) {

        try {

            $allowed_extensions = ['doc','docx'];

            $name = $filesData["theFile"]["name"];
            $file_extension = @end(explode(".", $name)); # extra () to prevent notice

            if(!in_array($file_extension,$allowed_extensions)) {
                throw new Exception("Solo se permite archivos de Word (.doc,.docx)");
            }

            $datos_requeridos = ['titulo_trabajo','id_participante'];

            $datos_faltantes = [];

            foreach($datos_requeridos as $dr) {
                if(!in_array($dr,array_keys($formData))) {
                    $datos_faltantes[] = $dr;
                }
            }

            if(count($datos_faltantes)>0) {
                throw new \Exception("Error validando datos del trabajo. Faltan los siguientes datos: ".implode(",",$datos_faltantes));
            }

            //-- Validate File title
            $id_participante = $formData["id_participante"];
            $titulo_trabajo = $formData["titulo_trabajo"];


            $trabajo_actual = TrabajoEntity::fromDatabaseByParticipante($id_participante);
            if(!is_null($trabajo_actual)) {
                throw new Exception("Ya existe un trabajo para este participante. ".$trabajo_actual["titulo_trabajo"]. " subido el  ".$trabajo_actual["created_date"]);
            }

            $uploaded_file = $filesData['theFile'];

            if($uploaded_file["size"] > 0) {
                $file = $uploaded_file["tmp_name"];
                $original_file_name = $uploaded_file["name"];
                $fs_name = date("YmdHis")."_trabajo_".$id_participante.".".$file_extension;
                $target_file = _APP_PATH."/uploads/".$fs_name;

                if (move_uploaded_file($file, $target_file)) {
                    //echo "The file ". basename( $original_file_name). " has been uploaded.";
                    $t = new TrabajoEntity();
                    $t->fromArray([
                                "id_participante"=>$id_participante,
                                "nombre_original"=>$original_file_name,
                                "nombre_fs"=>$fs_name,
                                "titulo_trabajo"=>$titulo_trabajo
                                ]);

                    return $t->toDatabase();

                } else {
                    throw new Exception("Lo sentimos, ha ocurrido un error al subir su trabajo.");
                }

            }else {
                throw new Exception("El archivo está vacío");
            }

        }catch(\Exception $e) {
            throw $e;
        }

    }

    public function votarTrabajo($id_participante,$id_trabajo) {
        try {

            //-- Revisar si este participante no ha votado ya a este trabajo
            $voto_previo = VotoEntity::getVotoByParticipanteAndTrabajo($id_participante,$id_trabajo);
            if($voto_previo) {
                throw new Exception("Solo se permite 1 voto por trabajo");
            }

            $new_voto = new VotoEntity();
            $new_voto->id_participante = $id_participante;
            $new_voto->id_trabajo = $id_trabajo;
            $new_voto->toDatabase();

            //-- Recalcular los votos de este trabajo
            $votos = VotoEntity::listVotosByTrabajo($id_trabajo);

            $t = new TrabajoEntity();
            $t->fromDatabase($id_trabajo);
            $t->votos = count($votos);
            $t->update();



        }catch(\Exception $e) {
            throw $e;
        }
    }

    public function comentarTrabajo($id_trabajo,$comentarios) {
        try {

            $t = new TrabajoEntity();
            $t->fromDatabase($id_trabajo);
            $t->comentarios = $comentarios;
            $t->update();

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public function enviarDevolucionTrabajo($id_trabajo) {
        try {
            $t = new TrabajoEntity();
            $t->fromDatabase($id_trabajo);

            $p = new ParticipanteEntity();
            $p->fromDatabase($t->id_participante);

            $participante = $p->toArray();

            $nombre_y_apellido = $participante["nombre"]." ".$participante["apellido"];


            try {
                $subject = '=?UTF-8?Q?' . quoted_printable_encode('Devolución trabajo subido UTELPa.') . '?=';
                Utilities::sendEmail($participante["email"],$nombre_y_apellido,nl2br($t->comentarios),$t->comentarios,$subject);
            }catch(Exception $e) {
                throw $e;
            }

        }catch(Exception $e) {

        }
    }

    public function listarTrabajosParaParticipante($participante) {

        $listado = TrabajoEntity::listTrabajos(0,-1);

        //-- Hay que devolver solamente los trabajos de participantes del mismo nivel
        $trabajos = [];

        foreach($listado["rows"] as $trabajo ) {

            $id_participante_trabajo = $trabajo["id_participante"];
            $p = new ParticipanteEntity();
            $p->fromDatabase($id_participante_trabajo);

            if(strtolower($p->nivel)==strtolower($participante["nivel"]) && $trabajo["id_participante"] != $participante["id"]) {
                //-- Revisamos si el trabajo ya fué votado por este ñato
                $voto = VotoEntity::getVotoByParticipanteAndTrabajo($participante["id"],$trabajo["id"]);
                $trabajo["votado"] = !is_null($voto);
                $trabajos[$trabajo["id"]] = $trabajo;

            }
        }

        return $trabajos;
    }

    public function obtenerTrabajo($id) {
        try {
            $te = new TrabajoEntity();
            $te->fromDatabase($id);
            return $te->toArray();
        }catch(\Exception $e) {
            throw $e;
        }
    }

    public function obtenerTrabajoDeParticipante($id_participante) {
        try {
            return TrabajoEntity::fromDatabaseByParticipante($id_participante);

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public function enviarEmailTrabajoRecibido($id_trabajo) {

        $pl = new ParticipanteLogic();

        $trabajo = $this->obtenerTrabajo($id_trabajo);
        $participante = $pl->obtenerParticipante($trabajo["id_participante"]);

        $nombre_y_apellido = $participante["nombre"]." ".$participante["apellido"];

        $body_html = "Compañeras y compañeros,<br/><br/>
Hemos recibido la experiencia pedagógica, la misma será leída por un grupo de docentes con distintas trayectorias de formación que enviarán por mail una devolución sobre el trabajo presentado. Tener en cuenta que la experiencia debe encuadrarse dentro de la pedagogía emancipadora.<br/><br/>
Esta experiencia será compartida (mediante una presentación de 15' como máximo) en la Sede que participes.<br/><br/>
Por favor te recomendamos chequear regularmente el correo electrónico, ya que es el principal medio que utilizaremos como vía de contacto.<br/><br/>
Saludos cordiales,<br/>
<strong>UTELPa</strong><br/>";

        $body_plain  = preg_replace('#<br\s*/?>#i', "\n", $body_html);
        $body_plain = strip_tags($body_plain);

        $email_sent = false;

        try {
            $subject = '=?UTF-8?Q?' . quoted_printable_encode('Hemos recibido su trabajo.') . '?=';

            $email_sent = Utilities::sendEmail($participante["email"],$nombre_y_apellido,$body_html,$body_plain,$subject);
        }catch(Exception $e) {

        }

        return $email_sent;

    }


}

