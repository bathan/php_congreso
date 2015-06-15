<?php
include_once __DIR__ . '/include/config.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _TRABAJO_LOGIC_PATH;
require_once _UTILITIES_PATH;


$participante_id = $_GET["participante_id"];
$pl = new ParticipanteLogic();
$participante = $pl->obtenerParticipante($participante_id);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Trabajos</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="99%" border="0" align="left" cellpadding="0" cellspacing="0" id="trabajos">
    <?php
        $tl = new TrabajoLogic();
        $listado = $tl->listarTrabajosParaParticipante($participante);
    if(count($listado)>0) {
        foreach($listado as $trabajo) {
            $votado = $trabajo["votado"];

            ?>
            <tr>
                <td width="81%" align="left"><a href="download.php?id_trabajo=<?=$trabajo["id"];?>"><?=$trabajo["titulo_trabajo"];?></a></td>
                <td width="9%" align="right" >
                    <span class="<?=(($votado) ? "desactivado votado" :"votar");?> " >
                    <?php
                    if(!$votado) {
                        echo '<a href="javascript:votar('.$trabajo["id"].','.$participante_id.');">VOTAR</a>';}?></span>
                </td>
            </tr>
        <?php
        }
    }else{
        echo '<tr><td colspan="4">No se han subido trabajos aun</td></tr>';
    }

    ?>
</table>
<script src="js/jquery.min.js"></script>
<script type="text/javascript"> 

    function votar(id_trabajo,id_participante) {

        var dataString = JSON.stringify({
                        id_participante: id_participante,
                        id_trabajo: id_trabajo,
                        action: 'vote'
                    });

        var posting = $.post( "/form_actions/participantes/index.php", dataString );

        // Put the results in a div
        posting.done(function( data ) {
            var response = jQuery.parseJSON(data);

            if(response.status=='error') {
                alert('Se ha producido un error al votar por el trabajo. '+ response.data);
            }

            if(response.status=='ok') {
                alert('Su voto ha sido registrado con éxito');
                location.reload();
            }
        });


    }

		</script>
</body>
</html>