<?php 

//nuevo index, nuevo todo :D
$Config = new show_config();
$Con = new Modulos();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
<?php 

include 'scripts/head/public/public_metas.php'; 
include 'scripts/head/public/public_css.php'; 

?>

	</head>
	<body class="static-sidebar">		

	
<?php
	include 'secciones/new_nav.php';
?>    

<!-- start: Main Menu -->
<div class="sidebar sidebar-hidden">
	<h5>Busqueda simple</h5>
	<div id="form-sidebar" class="sidebar-collapse">
		
		<?php //echo $Config->section_aside(); ?>						
	</div>
</div>


<?php 





echo $Config->obtener_seccion();


echo $Config->section_footer();	//footer ?> 

</body>
<?php
include 'scripts/head/cdn.php'; 
include 'scripts/head/public/public_js.php';
?>	
<div id="sujerencia" class="sujerencia-response sujerencia-off"></div>
</html>				