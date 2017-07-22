<?php

//control de sessiones
$Con = new Seg_sesion();

//destroy session y eliminacion en la db
if((!empty($_GET['destroy'])) && ($_GET['destroy'] ==1)){
	
	$salt = $Con->make_salt();
	//buscar si hay ese salt y eliminar bicho
	$Con->search_and_destroy($salt, 'desconexion');
	
	cerrar_sesion();

}

//si hay session comprobamos que sea correcta
if(!empty($_SESSION['login_string'])){

	if($Con->login_check()!=true){

		$salt = $Con->make_salt();	
		var_dump($salt);					//creamos salt
		$Con->search_and_destroy($salt, 'l_s-false');	//destruimos sesion indicada

		
		var_dump('cierre forzado');
		die;
		cerrar_sesion();

	}
}

if(empty($_GET)){
	if(isset($_SESSION['lg_cambio'])){	$_SESSION['lg_cambio'] = '';	}
}

//si estoy fuera de acction, o fuera de perfil 25 
//==================================================
if( (empty($_GET['accion'])) || ( (!empty($_GET['perfil'])) && ($_GET['perfil'] != '25')) ){
	//borrare la sesion
	if(!empty($_SESSION['action_error'])){	unset($_SESSION['action_error']);	}
}

//si detecto estas sessiones fuera de rango de la paginacion las elimino
//============================================================================
if((empty($_GET['pagg'])) && (empty($_GET['pag'])) && (empty($_GET['inmv']))){
	//se borraran sesiones
	if(!empty($_SESSION['busqueda'])){	unset($_SESSION['busqueda']);	}
}


?>