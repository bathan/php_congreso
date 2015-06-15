<?php

include_once __DIR__ . '/../include/config.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _TRABAJO_LOGIC_PATH;
require_once _UTILITIES_PATH;

$p_logic = new ParticipanteLogic();
$t_logic = new TrabajoLogic();

$participante = $p_logic->obtenerParticipante($_GET["id"]);

try {
    $trabajo = $t_logic->obtenerTrabajoDeParticipante($_GET["id"]);
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
								<h2>Trabajo</h2>
								<table border="0" align="center" cellpadding="6" cellspacing="6">
								  <tr>
								    <td align="right">Titulo:</td>
								    <td align="left"><a href="/download.php?id_trabajo=<?=$trabajo["id"];?>&fa=1"><?=$trabajo["titulo_trabajo"]?></a></td>
							      </tr>
								  <tr>
								    <td align="right">Autor:</td>
								    <td align="left"><?=$participante["nombre"]." ".$participante["apellido"]; ?></td>
							      </tr>
								  <tr>
								    <td align="right">Votos:</td>
								    <td align="left"><?=$trabajo["votos"]?> votos</td>
							      </tr>
								  <tr>
								    <td align="right" valign="top">Devolución:</td>
								    <td align="left"><div class="12u$">
                                            <textarea name="commentarios" id="commentarios" placeholder="Mensaje" cols="40" rows="20" ><?=$comentarios?></textarea>
                                        </div></td>
							      </tr>
								  <tr>
								    <td align="left">&nbsp;</td>
								    <td align="left">&nbsp;</td>
							      </tr>
								  <tr>
								    <td align="left"><input type="button" name="btn_guardar" id="btn_guardar" value="Guardar" /></td>
								    <td align="left"><input type="button" name="btn_enviar" id="btn_enviar" value="Enviar Devolución" /></td>
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

            var id_trabajo = '<?=$trabajo["id"];?>';

            $('#btn_enviar').click(function (e) {

                $('#btn_enviar').attr("disabled", true);

                var comments = $('#commentarios').val();

                if(commentarios=='') {
                    $('#btn_enviar').attr("disabled", false);
                    alert('Debe completar la devolución antes de guardarla');
                    return;
                }

                //-- Si llegamos a este momento es que todas las validaciones han sido correctas.
                var dataString = JSON.stringify({
                                id_trabajo: id_trabajo,
                                comments: comments,
                                action: 'send_trabajo_comment'
                            });

                var posting = $.post( "/form_actions/participantes/index.php", dataString );

                // Put the results in a div
                posting.done(function( data ) {
                    var response = jQuery.parseJSON(data);

                    if(response.status=='error') {
                        alert('Se ha producido un error al enviar la devolución. '+ response.data);
                    }

                    if(response.status=='ok') {
                        alert('La devolución ha sido enviada con éxito al participante');
                        location.reload();
                    }

                    $('#btn_guardar').attr("disabled", false);
                });
            });


            $('#btn_guardar').click(function (e) {

                $('#btn_guardar').attr("disabled", true);

                var comments = $('#commentarios').val();

                if(commentarios=='') {
                    $('#btn_guardar').attr("disabled", false);
                    alert('Debe completar la devolución antes de guardarla');
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
                        alert('Se ha producido un error al guardar la devolución. '+ response.data);
                    }

                    if(response.status=='ok') {
                        alert('La devolución ha sido almacenada con éxito');
                        location.reload();
                    }

                    $('#btn_guardar').attr("disabled", false);
                });



             });

        });

    </script>
	</body>

    
</html>