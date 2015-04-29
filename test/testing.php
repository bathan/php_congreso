<?php
include_once __DIR__ . '/../include/config.php';


$p_logic = new \Congreso\Logica\Participante();
$datos = ["nombre"=>"juan","apellido"=>"perez","dni"=>12345678,"email"=>"juan@merman.com"];

try {
    /*
    $nuevo_id = $p_logic->agregarParticipante($datos);

    echo "****** nuevo id=".$nuevo_id."\n";

    $participante = $p_logic->obtenerParticipante($nuevo_id);

    echo "****** PARTICIPANTE RAW \n";

    pretty_print($participante);

    $oldPassword = $participante["password"];

    $p_logic->changePassword($nuevo_id,$oldPassword,"mierda_puta");

    $participante = $p_logic->obtenerParticipante($nuevo_id);

    echo "****** PARTICIPANTE UPDATED \n";

    pretty_print($participante);

    echo "****** PARTICIPANTE TOKEN\n";

    echo $token = $p_logic->getUserToken($nuevo_id);

    echo "***** PARTICIPANTE EMAIL Y ID EN BASE AL TOKEN\n";

    pretty_print($p_logic->validateUserToken($token));
*/
    $resultado = $p_logic->listParticipantes();

    var_dump($resultado);


}catch(Exception $e) {
    var_dump($e->getMessage());

}


//$p_logic->eliminarParticipante($nuevo_id);

