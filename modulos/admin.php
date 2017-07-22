<?php 
$Config = new show_config();
?>


<!DOCTYPE html>
<html lang="en">
	<head>


<?php
	include 'scripts/head/admin/admin_metas.php';
	include 'scripts/head/admin/admin_css.php';	

?>
				
		<!-- Remove following comment to add Right to Left Support or add class rtl to body -->
		<!-- <link href="assets/css/style.rtl.min.css" rel="stylesheet"> -->

	    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
	</head>
	
	<body>


<?php

//obtiene la seccion que viene por $_GET (admin, perfil o perfilmda)
echo $Config->obtener_seccion();



//<!-- Modal -->
include 'secciones/admin/admin_modal.php';
//<!-- start: JavaScript-->
include 'scripts/head/cdn.php'; 
include 'scripts/head/admin/admin_js.php'; 

?>


	</body>
</html>								