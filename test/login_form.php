<?php
session_start();

include_once __DIR__ . '/../include/config.php';


$pl = new \Congreso\Logica\Participante();

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Login Form - Test</title>
    <script src="/js/jquery-1.11.2.min.js"></script>
</head>
<body>
<h2>Login Form Test</h2>
<form name="login_form" action="../form_actions/participantes/" method="POST">
    email: <input type="text" id="email" name="email"><br>
    password : <input type="password" id="password" name="password">
    <input type="hidden" id="action" name="action" value="login">
    <input type="submit" value="do_login_credentials">
</form>

<hr>
<h2>Token Login Form Test</h2>
<form name="login_form" action="../form_actions/participantes/" method="POST">
    token: <input type="text" id="token" name="token"><br>
    <input type="hidden" id="action" name="action" value="login_token">
    <input type="submit" value="do_login_token">
</form>

<hr/>
<h2>Login Form Test</h2>
<form name="login_form" action="../form_actions/participantes/" method="POST">
    <input type="hidden" id="action" name="action" value="logout">
    <input type="submit" value="do_logout">
</form>
</hr>
<script>
    function sortTable(sortField) {
        var sortDirection = $("#sort_direction").val();
        if(sortDirection=='ASC') {
            sortDirection='DESC';
        }else{
            sortDirection='ASC';
        }

        $("#sort_direction").val(sortDirection);
        $("#sort_field").val(sortField);
        $("#filter_form").submit();
    }
</script>

<?php
if(isset($_SESSION['user_token'])) {
    echo "<pre>SESSION TOKEN: ".$_SESSION['user_token']."</pre><br>";
}

$limit  = _DEFAULT_LIST_LIMIT;
$page = 1;
$pages = 1;

try {
    $orderBy = [];

    if(isset($_GET["sort_field"]) && strlen($_GET["sort_field"])>0) {
        $orderBy["c"] = $_GET["sort_field"];
        $orderBy["d"] = $_GET["sort_direction"];
    }

    $filtros = [];

    foreach($_GET as $field=>$value) {
        if(strstr($field,'filtro_') && $value!='') {
            //-- Its a Filter
            $filtros[str_replace('filtro_','',$field)] = $value;
        }
    }

    $from = 0;
    $limit = isset($_GET["limit"]) ? $_GET["limit"] : _DEFAULT_LIST_LIMIT;
    if($limit==0) {
        $limit = null;
    }

    $total = $pl->countParticipantes($filtros);

    if($total > 0) {
        // How many pages will there be
        $pages = ceil($total / $limit);

        // What page are we currently on?
        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));

        // Calculate the offset for the query
        $offset = ($page - 1)  * $limit;

        // Some information to display to the user
        $start = $offset + 1;
        $end = min(($offset + $limit), $total);

        $resultado = $pl->listParticipantes($offset,$limit,$filtros,$orderBy);

        $orderBy = $resultado["orderby"];

    }


}catch(Exception $e) {
    echo $e->getMessage();
}

?>
<form name="filter_form" id="filter_form" method="get">
    <input type="hidden" name="sort_field" id="sort_field" value="<?=$orderBy["c"];?>"/>
    <input type="hidden" name="sort_direction" id="sort_direction" value="<?=$orderBy["d"];?>"/>
    nombre : <input type="text" name="filtro_nombre" id="filtro_nombre" value="<?=@$filtros["nombre"]?>"/><br/>
    apellido : <input type="text" name="filtro_apellido" id="filtro_apellido" value="<?=@$filtros["apellido"]?>"/><br/>
    localidad : <input type="text" name="filtro_localidad" id="filtro_localidad" value="<?=@$filtros["localidad"]?>"/><br/>
    email : <input type="text" name="filtro_email" id="filtro_email" value="<?=@$filtros["email"]?>"/><br/>
    nivel : <input type="text" name="filtro_nivel" id="filtro_nivel" value="<?=@$filtros["nivel"]?>"/><br/>

    <select name="limit" id="limit">
        <option value="0">-- Todos --</option>
        <option value="10">10</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>

    <input type="submit" value="filtrar" />

    <br>
    <br>
    <?php
    if($total > 0) {

        $query_string = \Congreso\Logica\Utilities::purifyQueryString(['page']);

        // The "back" link
        $prevlink = ($page > 1) ? '<a href="?'.$query_string.'&page=1" title="First page">&laquo;</a> <a href="?'.$query_string.'&page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

        // The "forward" link
        $nextlink = ($page < $pages) ? '<a href="?'.$query_string.'&page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?'.$query_string.'&page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

        // Display the paging information
        echo '<div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';
    }

    ?>

<table cellpadding="2" cellspacing="2" width="600">
    <tr bgcolor="#a9a9a9">
        <td><a href="javascript:sortTable('id');">id</a></td>
        <td><a href="javascript:sortTable('nombre');">nombre</a></td>
        <td><a href="javascript:sortTable('apellido');">apellido</a></td>
        <td><a href="javascript:sortTable('dni');">dni</a></td>
        <td><a href="javascript:sortTable('localidad');">localidad</a></td>
        <td><a href="javascript:sortTable('email');">email</a></td>
        <td><a href="javascript:sortTable('nivel');">nivel</a></td>
        <td><a href="javascript:sortTable('created_date');">created_date</a></td>
        <td>user_token</td>
    </tr>
<?php

try {
    if($resultado["rows"]>0) {

        foreach($resultado["rows"] as $p) {
            echo '<tr bgcolor="#8fbc8f">';
            echo '<td>'.$p['id'].'</td>';
            echo '<td>'.$p['nombre'].'</td>';
            echo '<td>'.$p['apellido'].'</td>';
            echo '<td>'.$p['dni'].'</td>';
            echo '<td>'.$p['localidad'].'</td>';
            echo '<td>'.$p['email'].'</td>';
            echo '<td>'.$p['nivel'].'</td>';
            echo '<td>'.$p['created_date'].'</td>';
            echo '<td><pre style="font-size: xx-small; word-break: normal ">'.$p['user_token'].'</pre></td>';
            echo '<tr>';
        }

    }else{
        echo '<tr><td colspan="9"> sin resultados </td></tr>';
    }

}catch(Exception $e) {
    var_dump($e);
}
?>
</table>
</form>
</body>
</html>

