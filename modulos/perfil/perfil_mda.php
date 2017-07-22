<?php

//perfil para el administrtador de la web
$Config = new show_config();

// <!-- start: Header -->
include 'secciones/admin/admin_nav.php';
// <!-- end: Header -->
// <!-- start: Main Menu -->
include 'secciones/admin/admin_aside.php';
// <!-- end: Main Menu -->
// <!-- start: Content -->
echo '<div class="main">';

//menu seleccionado, sino 1
if(!empty($_GET['perfil_mda'])){
	$perfil = $_GET['perfil_mda'];
}else{
	$perfil = 1;
}

//var_dump($Config);
if( (!empty($_SESSION['mda_id'])) && ($_SESSION['user_id'] == '105') ){
//la sesion es correcta, continuamos

	//directivas definidas en db
	$perfil_menu = $Config->directivas_permitidas();

	foreach($perfil_menu as $value){
		if(!empty($value['id'])){
			if($value['id'] == $perfil){
				//var_dump($value);
				echo $Config->show_title($value);
				include($value['ruta']);
	}	}	}
}//session correcta

echo '</div>';
?>