<?php

//objetos
$Config = new show_config();		//objeto show_config
$Con 	= new Usuarios();			//objeto Usuarios
$Perfil = $Config->Modulos->Perfil;	//objeto seg_builder

// <!-- start: Layout Settings / remove this div from your project -->
//include 'secciones/admin/admin_theme_config.php';
// <!-- end: Layout Settings -->
// <!-- start: Header -->
include 'secciones/admin/admin_nav.php';
// <!-- end: Header -->
// <!-- start: Main Menu -->
include 'secciones/admin/admin_aside.php';
// <!-- end: Main Menu -->
// <!-- start: Content -->
echo '<div class="main">';



//elije pagina de perfil
if(!empty($_GET['perfil'])){	$perfil = $_GET['perfil'];
}else{							$perfil = 1;				}

//perfil_menu viene definido por las directivas de tipo de usuario, el siguiente codigo
//despliega el menu y los menus desplegables, categoria - subcategoria 
$perfil_menu = $Config->directivas_permitidas();
foreach($perfil_menu as $value){
	if(!empty($value['id'])){
		if($value['id'] == $perfil){
			echo $Config->show_title($value);	//mostramos titulo de seccion
			include($value['ruta']);			//include archivo de seccion
		}		
	}
}

echo '</div>';
//<!-- end: Content -->	
		
		
		

		
//<!-- start: Footer -->
include 'secciones/admin/admin_footer.php';


?>