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
                $fs_name = date("Ymd_")."_trabajo_".$id_participante.".".$file_extension;
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

                    return $t->toDatabase();;

                } else {
                    throw new Exception("Lo sentimos, ha ocurrido un error al subir su trabajo.");
                }

                //$s3Path = CMSUtilities::putCategoryIcon($cms_cat_id,$file,$original_file_name);
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


        }catch(\Exception $e) {
            throw $e;
        }
    }




}

