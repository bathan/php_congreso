<?php
include_once __DIR__ . '/../include/config.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _UTILITIES_PATH;

$pl = new ParticipanteLogic();
$counts = $pl->getPartipanteCounts();


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

				

				

				<!-- Contact -->
					<section id="contact" class="four">
						<div class="container">

							<header>
								<h2>Enviar Emails</h2>
							</header>

							<p>Elementum sem parturient nulla quam placerat viverra
							mauris non cum elit tempus ullamcorper dolor. Libero rutrum ut lacinia
							donec curae mus.</p>

							<form id="theform" >
								<div class="row">
									<div class="6u 12u$(mobile)"><input type="text" name="subject" id="subject" placeholder="Asunto" /></div>
									<div class="6u 12u$(mobile)">
                                            <select name="nivel" id="nivel">
									            <option value="0" selected>Seleccione Nivel</option>
                                                <option value="all">Todos los niveles</option>
									            <option value="Primario">Nivel Primario</option>
									            <option value="Secundario">Nivel Secundario</option>
									            <option value="Estudiante">Estudiantes</option>
                                                <option value="Otros">Otros</option>
								            </select>
									  
									  
									</div>
									<div class="12u$">
										<textarea name="body" id="body" placeholder="Mensaje" ></textarea>
									</div>
									<div class="12u$">
										<input type="button" id="enviar" value="Enviar Mensaje" />
									</div>
								</div>
							</form>

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

        <script>
        $(document).ready(function() {

            $('#theform').keypress(function(event){
                var target_id = event.target.id;

                if (event.keyCode == 10 || event.keyCode == 13) {
                    if(target_id != 'body') {
                        event.preventDefault();
                    }
                }

            });

            $('#enviar').click(function (e) {
                doTheSending();
            });

            var am_i_sending = false;

            function doTheSending() {

                if(am_i_sending) {
                    return;
                }

                var theCounts = <?=json_encode($counts);?>;
                var nivel = $('#nivel').val();
                var subject = $('#subject').val();
                var body = $('#body').val();

                //-- Primeras Validaciones
                if(nivel==0) {
                    alert('Debe seleccionar un Nivel');
                    return;
                }

                if(subject=='') {
                    alert('Debe ingresar el asunto del email');
                    return;
                }

                if(body=='') {
                    alert('Debe ingresar el mensaje del email');
                    return;
                }

                //-- So far, so good
                var confirmed = false;

                if(nivel=='all') {
                    var totalRecipients = theCounts.participantes;
                }else{
                    var totalRecipients = theCounts.niveles[nivel];
                }

                if (typeof(totalRecipients) == 'undefined' || totalRecipients==0) {
                    alert('No hay inscriptos en nivel '+nivel);
                    return;
                }

                if(confirm('Está por enviar un email a '+totalRecipients+' inscriptos. ¿Está seguro que desea continuar?')) {

                    var dataString = JSON.stringify({action: 'email_users',nivel: nivel,subject:subject,body:body});

                    var posting = $.post( "/form_actions/participantes/index.php", dataString );
                    var current_text = $('#enviar').val();

                    $('#enviar').prop('value','Enviando mensajes, aguarde por favor.');
                    $('#enviar').attr('disabled',true);
                    am_i_sending = true;

                    // Put the results in a div
                    posting.done(function( data ) {
                        var response = jQuery.parseJSON(data);
                        if(response.status=='error') {
                            alert('Se ha producido un error al enviar los emails');
                        }

                        if(response.status=='ok') {
                            alert('Emails enviados con éxito');
                        }

                        $('#enviar').prop('value',current_text);
                        $('#enviar').attr('disabled',false);
                        am_i_sending = false;

                    });


                }

            }
        });
        </script>
            
        
            
  
    </body>
</html>