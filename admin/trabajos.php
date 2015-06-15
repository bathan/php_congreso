<?php
include_once __DIR__ . '/trabajos_seeker.php';

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
					

				<!-- Portfolio -->
					<section id="portfolio" class="two">
						<div class="container">

							<header>
								<h2>Trabajos</h2>
							</header>

							<p><a href="javascript:doNivelSearch('');">Total</a> | <a href="javascript:doNivelSearch('primario');">Nivel Primario</a> |	<a href="javascript:doNivelSearch('secundario');">Nivel	Secundario</a> | <a href="javascript:doNivelSearch('estudiantes');">Estudiantes</a> | <a href="javascript:doNivelSearch('otros');">Otros</a>
                            <form id="theform" method="get">
                                <input type="hidden" name="sort_field" id="sort_field" value="<?=$orderBy["c"];?>"/>
                                <input type="hidden" name="sort_direction" id="sort_direction" value="<?=$orderBy["d"];?>"/>

                                <input type="text" name="filtro_magic" id="filtro_magic" placeholder="Buscar usuario" value="<?=$filtro_magic?>" style="padding-left:5px; width:250px">
                                <a href="#" id="magic_field_link"><span class="icon fa-search"></span></a>
                            </form></p>

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
							    <tr>
							      <td width="50%" align="left">

                                      <?php
                                        if($total>0) {
                                            ?>
                                            Mostrando <?= $start; ?>-<?= $end; ?> de <?= $total; ?> entradas

                                        <?php
                                        }

                                      if($total > 0) {

                                          $query_string = Utilities::purifyQueryString(['page']);

                                          // The "back" link
                                          $prevlink = ($page > 1) ? '<a href="?'.$query_string.'&page=1" title="First page">&laquo;</a> <a href="?'.$query_string.'&page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

                                          // The "forward" link
                                          $nextlink = ($page < $pages) ? '<a href="?'.$query_string.'&page=' . ($page + 1) . '">&rsaquo;</a> <a href="?'.$query_string.'&page=' . $pages . '" >&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';
                                      }else{
                                          $prevlink = null;
                                          $nextlink = null;
                                      }
                                      ?>
                                  </td>
							      <td width="50%" align="right"> <?php echo $prevlink."&nbsp;".$nextlink ?></td>

						        </tr>
						      </table>

							<div class="row">
							  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="participantes">
							    <tr class="firstRow">
							      <td width="80">Devolución</td>
							      <td><a href="javascript:sortTable('titulo');">Titulo<span class="icon fa-sort"></span></a></td>
							      <td><a href="javascript:sortTable('autor');">Autor <span class="icon fa-sort"></span></a></td>
							      <td width="80"><a href="javascript:sortTable('votos');">Votos<span class="icon fa-sort"></span></a></td>
							      <td><a href="javascript:sortTable('nivel');">Nivel <span class="icon fa-sort"></span></a></td>
						        </tr>
                                  <?php
                                  if($resultado && $resultado["rows"]>0) {
                                    foreach($resultado["rows"] as $p) {
?>

                                        <tr>
                                            <td align="center" valign="middle" class="centrado"><a href="edit_work.php?id=<?=$p["id"];?>" rel="leanModal"><span class="icon fa-eye"></span></a></td>
                                            <td class="izquierda"><?=$p["nombre"];?></td>
                                            <td class="izquierda"><?=$p["apellido"];?></td>
                                            <td class="izquierda"><?=$p["dni"];?></td>
                                            <td class="izquierda"><?=$p["nivel"];?></td>
                                        </tr>


                                    <?php
                                    }
                                  }else{
                                      ?>
                                      <tr>
                                          <td colspan="5"> No se encontraron participantes </td>
                                      </tr>

                                  <?php  } ?>


						      </table>
							  <table width="100%" border="0" cellspacing="0" cellpadding="0">
							    <tr>
							      <td width="50%" align="left"><div class="listados"><a href="/admin/export/excel/?limit=<?=($total+100);?>&<?=$_SERVER['QUERY_STRING']?>"><span class="icon fa-file-pdf-o"></span> Exportar</a></div></td>
							      <td width="50%" align="right">  <a href="#">&laquo;</a> <a href="#">&#8249;</a> <a href="#">&#8250;</a> <a href="#">&raquo;</a></td>
						        </tr>
						      </table>
						  </div>

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

        <script src="js/utilities.js"></script>
        <script src="js/inscriptos.js"></script>
        <script>




        function doNivelSearch(nivel) {
            var uri = cleanUri();
            var qs = updateQueryStringParameter(uri, 'filtro_nivel', nivel);
                document.location.href = qs;

        }

        function sortTable(sortField) {
            var sortDirection = $("#sort_direction").val();
            if(sortDirection=='ASC') {
                sortDirection='DESC';
            }else{
                sortDirection='ASC';
            }

            $("#sort_direction").val(sortDirection);
            $("#sort_field").val(sortField);

            var uri = cleanUri();
            var qs = updateQueryStringParameter(uri, 'sort_field', sortField);
                qs = updateQueryStringParameter(qs, 'sort_direction', sortDirection);
                document.location.href = qs;

        }


    </script>
        
            
  
    </body>
</html>