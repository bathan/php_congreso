<?php
session_start();

include_once __DIR__ . '/../include/config.php';
require_once _PARTICIPANTE_LOGIC_PATH;
require_once _UTILITIES_PATH;


$pl = new ParticipanteLogic();

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Register Form - Test</title>
    <script src="/js/jquery-1.11.2.min.js"></script>
</head>
<body>
<h2>Register Form Test</h2>
<form name="login_form" action="../form_actions/participantes/" method="POST">
    <?php
        $campos = ['nombre','apellido','dni','email','nivel','localidad'];

        foreach($campos as $c) {
            echo $c.":".'<input type="text" id="'.$c.'" name="'.$c.'"><br>'."\n";
        }

    ?>
    <input type="hidden" id="action" name="action" value="register">
    <input type="submit" value="do_register">
</form>

<hr/>

<h2>Update Info Form Test</h2>
<form name="login_form" action="../form_actions/participantes/" method="POST">
    <?php
    $campos = ['id','user_token','nombre','apellido','dni','email','password','password_confirm'];

    foreach($campos as $c) {
        echo $c.":".'<input type="text" id="'.$c.'" name="'.$c.'"><br>'."\n";
    }

    ?>
    <input type="hidden" id="action" name="action" value="update_info">
    <input type="submit" value="do_login">
</form>

<hr/>

<h2>Update Info Form Test</h2>
<form name="forum_user_add" action="../form_actions/participantes/" method="POST">
    user token: <input type="text" id="user_token" name="user_token" value="" />

    <input type="hidden" id="action" name="action" value="forum_add">
    <input type="submit" value="do_login">
</form>



</body>
</html>

