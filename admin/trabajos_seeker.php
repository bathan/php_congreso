<?php

include_once __DIR__ . '/../include/config.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _UTILITIES_PATH;


$pl = new ParticipanteLogic();

$limit  = _DEFAULT_LIST_LIMIT;
$page = 1;
$pages = 1;

$filtro_magic = isset($_GET["filtro_magic"]) ? $_GET["filtro_magic"] : "";


try {
    $orderBy = [];

    if(isset($_GET["sort_field"]) && strlen($_GET["sort_field"])>0) {
        $orderBy["c"] = $_GET["sort_field"];
        $orderBy["d"] = $_GET["sort_direction"];
    }else{
        $orderBy["c"] = "id_trabajo";
        $orderBy["d"] = "asc";
    }

    $filtros = [];

    foreach($_GET as $field=>$value) {
        if(strstr($field,'filtro_') && $value!='') {
            //-- Its a Filter
            $filtros[str_replace('filtro_','',$field)] = $value;
        }
    }


    $from = 0;
    $limit = isset($_GET["limit"]) ? $_GET["limit"] : _DEFAULT_LIST_LIMIT;

    if($limit==0) {
        $limit = null;
    }

    $total = $pl->countParticipantesConTrabajos($filtros);


    if($total > 0) {

        if($limit) {
            // How many pages will there be
            $pages = ceil($total / $limit);

            // What page are we currently on?
            $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
                'options' => array(
                    'default'   => 1,
                    'min_range' => 1,
                ),
            )));

            // Calculate the offset for the query
            $offset = ($page - 1)  * $limit;

            // Some information to display to the user
            $start = $offset + 1;
            $end = min(($offset + $limit), $total);
        }else{
            $start = 1;
            $end =1;
            $offset = 0;
        }

        $resultado = $pl->listParticipantesConTrabajos($offset,$limit,$filtros,$orderBy);

        $orderBy = $resultado["orderby"];

    }else{
        $resultado = [];
        $start= 0;
        $end = 0;
    }



}catch(Exception $e) {
    echo $e->getMessage();
}