<?php

include_once __DIR__ . '/../include/config.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _TRABAJO_LOGIC_PATH;
require_once _UTILITIES_PATH;

$p_logic = new ParticipanteLogic();

$participante = $p_logic->obtenerParticipante($_GET["id"]);

$t_logic = new TrabajoLogic();

try {
    $trabajo = $t_logic->obtenerTrabajoDeParticipante($participante["id"]);
    $comentarios = $trabajo["comentarios"];
}catch(Exception $e) {
    $trabajo = null;
}


?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Congreso UTELPa 2015 - Admin</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
        <!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollzer.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
        
	</head>
	<body>

		<!-- Header -->
			<?php
        include("header.php");
        ?>


		<!-- Main -->
	<div id="main">

				

				<!-- Portfolio -->
	  <section id="portfolio" class="two">
						<div class="container">

							<header>
								<h2>Perfil de  Usuario</h2>
								<table border="0" align="center" cellpadding="6" cellspacing="6">
								  <tr>
								    <td align="right">Nombre:</td>
								    <td align="left">
							        <?=$participante["nombre"];?></td>
							      </tr>
								  <tr>
								    <td align="right">Apellido:</td>
								    <td align="left"><?=$participante["apellido"];?></td>
							      </tr>
								  <tr>
								    <td align="right">DNI:</td>
								    <td align="left"><?=$participante["dni"];?></td>
							      </tr>
								  <tr>
								    <td align="right">Localidad:</td>
								    <td align="left"><?=$participante["localidad"];?></td>
							      </tr>
								  <tr>
								    <td align="right">Email:</td>
								    <td align="left"><?=$participante["email"];?></td>
							      </tr>
								  <tr>
								    <td align="right">Escuela:</td>
								    <td align="left"><?=$participante["escuela"];?></td>
							      </tr>
								  <tr>
								    <td align="right">Nivel:</td>
								    <td align="left">
                                        <?=$participante["nivel"];?>
                                    </td>
							      </tr>
								  <tr>
								    <td align="right">Foros:</td>
								    <td align="left">(foros en los que participo)</td>
							      </tr>
                                    <? if($trabajo) { ?>
								  <tr>
								    <td align="right">Trabajo:</td>
								    <td align="left"><a href="/download.php?id_trabajo=<?=$trabajo["id"];?>&fa=1"><?=$trabajo["titulo_trabajo"]?></a> (<?=$trabajo["votos"];?> votos)</td>
							      </tr>
                                        <tr>
                                            <td align="right" valign="center">Comentarios</td>
                                            <td align="left">
                                                <div class="12u$">
                                                    <textarea name="commentarios" id="commentarios" placeholder="Mensaje" cols="40" rows="20" ><?=$comentarios?></textarea>
                                                </div>
                                                <input type="button" name="UpdateComment" id="UpdateComment" value="Actualizar Comentario" />
                                            </td>
                                        </tr>
                                    <? } ?>
								  <tr>
								    <td align="left">&nbsp;</td>
								    <td align="left">&nbsp;</td>
							      </tr>
								  <tr>
								    <td align="left">&nbsp;</td>
								    <td align="left"><a href="inscriptos.php" class="button scrolly" id="btn_guardar">Regresar</a></td>
							      </tr>
							  </table>
                            </header>

							

		</div>
			</section>

		<!-- About Me --><!-- Contact --></div>

		<!-- Footer -->
			<div id="footer">

				<!-- Copyright -->
					<ul class="copyright">
						<li>&copy; UTELPa</li>
					</ul>

			</div>

    <script>
        $(document).ready(function() {

            <?php if($trabajo) { ?>


            $('#UpdateComment').click(function (e) {

                $('#UpdateComment').attr("disabled", true);

                var comments = $('#commentarios').val();
                var id_trabajo = '<?=$trabajo["id"];?>';

                if(commentarios=='') {
                    $('#UpdateComment').attr("disabled", false);
                        alert('Debe completar el comentario antes de guardarlo');
                        return;
                }

                //-- Si llegamos a este momento es que todas las validaciones han sido correctas.
                var dataString = JSON.stringify({
                                id_trabajo: id_trabajo,
                                comments: comments,
                                action: 'add_trabajo_comment'
                            });

                var posting = $.post( "/form_actions/participantes/index.php", dataString );

                // Put the results in a div
                posting.done(function( data ) {
                    var response = jQuery.parseJSON(data);

                    if(response.status=='error') {
                        alert('Se ha producido un error al guardar el comentario. '+ response.data);
                        $('#UpdateComment').attr("disabled", false);
                    }

                    if(response.status=='ok') {
                        alert('El comentario ha sido almacenado con éxito');
                        location.reload();
                    }
                });

                 $('#UpdateComment').attr("disabled", false);
             });

             <?php } ?>
        });

    </script>

	</body>

    
</html>