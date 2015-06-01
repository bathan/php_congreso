<?php

include_once __DIR__ . '/../include/config.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _UTILITIES_PATH;

$p_logic = new ParticipanteLogic();
$counts = $p_logic->getPartipanteCounts();

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

				<!-- Intro -->
					<section id="top" class="one dark cover">
						<div class="container">

							<header>
								<h2 class="alt">Administración Web Congreso UTELPa 2015<br />
								</h2>
								Bienvenidos!
							</header>

							<footer>
								<a href="inscriptos.php" class="button scrolly"><?=$counts["participantes"];?> Inscriptos</a>
                                <br><br>

                                <?=$counts["localidades"];?> Localidades | <?=$counts["escuelas"];?> Escuelas
								
							</footer>

						</div>
					</section>

				
			</div>

		<!-- Footer -->
			<div id="footer">

				<!-- Copyright -->
					<ul class="copyright">
						<li>&copy; UTELPa</li>
					</ul>

			</div>

		
            
        
            
  
    </body>
</html>