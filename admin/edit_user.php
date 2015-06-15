<?php

include_once __DIR__ . '/../include/config.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _TRABAJO_LOGIC_PATH;
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


	</body>

    
</html>