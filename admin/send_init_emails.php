<?php

include_once __DIR__ . '/../include/config.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _UTILITIES_PATH;

$p_logic = new ParticipanteLogic();
$counts = $p_logic->getPartipanteCounts();

$participantes_json = '';
$participantes = $p_logic->listParticipantes(0,-1);

foreach($participantes["rows"] as $p) {
    $participantes_json[] = $p["id"];
}

$participantes_json = json_encode($participantes_json);

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

<div style="padding: 10px;">
    <h2>Enviar emails a todos los participantes informando que el area de usuarios está dispoible.</h2>
    <input type="button" name="btn_go" id="btn_go" value="Enviar emails" />
    <span>
        <p>
        Participantes registrados : <?=$counts["participantes"];?>
        </p>
    </span>
    <span>
        Emails enviados : <span id="counter">0</span>
    </span>

</div>
<script>


    $(document).ready(function() {
        var gl_emails_enviados = 0;

        $('#btn_go').click(function (e) {

            if(confirm('Está seguro que desea enviar el email de inicio a <?=$counts["participantes"];?> participantes?')) {
                $('#btn_go').attr("disabled", true);
                sendEmails();
                $('#btn_go').attr("disabled", false);

            }
        });

    function sendEmails() {
         //-- Si llegamos a este momento es que todas las validaciones han sido correctas.
        var dataString = JSON.stringify({
                        action: 'send_init_email'
                    });

        var posting = $.post( "/form_actions/participantes/index.php", dataString );

        // Put the results in a div
        posting.done(function( data ) {
            var response = jQuery.parseJSON(data);

            if(response.status=='error') {
              //-- nada che
            }

            if(response.status=='ok') {
                gl_emails_enviados = response.emails_sent;
            }

            $('#counter').text(gl_emails_enviados);
        });
    }
});
</script>
</body>
</html>