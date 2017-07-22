<?php

    $project_name 	= 'PisosMallorca';
	$anterior 	  	= '';
	$posterior 		= '';
	$title 			= '';
	$description 	= '';
	$keywords 		= '';


    if(!empty($_GET['accion'])){

		if($_GET['accion'] == 'val'){$posterior =' | Validacion de usuario';}
		if($_GET['accion'] == 'log'){$posterior =' | Acceso de usuario';}
		if($_GET['accion'] == 'reg'){$posterior =' | Registro de usuario';}
		if($_GET['accion'] == 'los'){$posterior =' | Recuperar contraseña';}	

    }

    if(!empty($_GET['perfil'])){
    	$anterior = 'Panel de usuario | ';
    }

    if(!empty($_GET['perfil_mda'])){
    	$anterior = 'Panel Master | ';
    }

?>


















    	<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="description" content="Real Admin - Bootstrap Admin Template">
		<meta name="author" content="Łukasz Holeczek">
		<meta name="keyword" content="Real, Admin, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
	    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="assets/ico/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon" href="assets/ico/favicon.png">

	    <title><?php echo $anterior . $project_name . $posterior;  ?></title>