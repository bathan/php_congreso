<?php

include_once __DIR__ . '/../include/config.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _UTILITIES_PATH;

$p_logic = new ParticipanteLogic();

$participante = $p_logic->obtenerParticipante($_GET["id"]);

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
								<h2>Editar Usuario</h2>
								<form action="" method="get"><table border="0" align="center" cellpadding="6" cellspacing="6">
								  <tr>
								    <td align="left">Nombre</td>
								    <td align="left"><label for="textfield"></label>
							        <input name="nombre" type="text" id="nombre" value="<?=$participante["nombre"];?>" ></td>
							      </tr>
								  <tr>
								    <td align="left">Apellido</td>
								    <td align="left"><input name="apellido" type="text" id="apellido" value="<?=$participante["apellido"];?>"></td>
							      </tr>
								  <tr>
								    <td align="left">DNI</td>
								    <td align="left"><input name="dni" type="text" id="dni" value="<?=$participante["dni"];?>"></td>
							      </tr>
								  <tr>
								    <td align="left">Localidad</td>
								    <td align="left"><input name="localidad" type="text" id="localidad" value="<?=$participante["localidad"];?>"></td>
							      </tr>
								  <tr>
								    <td align="left">Email</td>
								    <td align="left"><input name="email" type="text" id="email" value="<?=$participante["email"];?>"></td>
							      </tr>
								  <tr>
								    <td align="left">Escuela</td>
								    <td align="left"><input name="escuela" type="text" id="escuela" value="<?=$participante["escuela"];?>"></td>
							      </tr>
								  <tr>
								    <td align="left">Nivel</td>
								    <td align="left">
                                        <select name="nivel" id="nivel">
                                            <option value="Primario" <? if($participante["nivel"]=='Primario') { echo "selected"; } ?>>Primario</option>
                                            <option value="Secundario" <? if($participante["nivel"]=='Secundario') { echo "selected"; } ?>>Secundario</option>
                                            <option value="Estudiantes" <? if($participante["nivel"]=='Estudiantes') { echo "selected"; } ?>>Estudiantes</option>
                                            <option value="Otros" <? if($participante["nivel"]=='Otros') { echo "selected"; } ?>>Otros</option>
                                        </select>
                                    </td>
							      </tr>
								  <tr>
								    <td align="left">Foros</td>
								    <td align="left">(foros en los que participo)</td>
							      </tr>
								  <tr>
								    <td align="left">Trabajo</td>
								    <td align="left"><a href="#">trabajo.pdf</a> (# votos)</td>
							      </tr>
								  <tr>
								    <td align="left">&nbsp;</td>
								    <td align="left"><a href="#" class="button scrolly" id="btn_guardar">Guardar</a></td>
							      </tr>
							  </table></form>
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

	</body>

    <script language="JavaScript">

        $(document).ready(function() {

            $('#btn_guardar').click(function (e) {

                 //-- Obtener los datos del form
                var nombre = $('#nombre').val();
                var apellido = $('#apellido').val();
                var dni = $('#dni').val();
                var localidad = $('#localidad').val();
                var email = $('#email').val();
                var nivel = $('#nivel').val();
                var escuela = $('#escuela').val();
                var action ='update_info';
                var user_token = '<?=$participante["user_token"];?>';

                 var dataString = JSON.stringify({
                        nombre: nombre,
                        apellido: apellido,
                        dni: dni,
                        localidad: localidad,
                        email: email,
                        nivel:  nivel,
                        escuela:  escuela,
                        action: action,
                        user_token: user_token
                    });

                var posting = $.post( "/form_actions/participantes/index.php", dataString );

                // Put the results in a div
                posting.done(function( data ) {
                    var response = jQuery.parseJSON(data);
                    if(response.status=='error') {
                        alert('Se ha producido un error al actualizar los datos del participante');
                    }

                    if(response.status=='ok') {
                        alert('Participante actualizado con éxito');
                    }
                });
            });
        });
    </script>
</html>