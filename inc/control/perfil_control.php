<?php

if (!empty($_SERVER['HTTP_REFERER'])) {
  $url = $_SERVER['HTTP_REFERER'];
}

//incluimos clase e iniciamos objeto feliz
require 'inc/clases/procesos/perfil_controller.class.php';	
$Controller = new perfil_controller();

//usuario recien registrado, nos lo cargamos y que valide
if(!empty($_SESSION['new_user'])){
	cerrar_sesion();
	header('location: index.php?accion=val');
	exit;
}


//para entrar aqui debe estar logeado siempre
if(!empty($_SESSION['user_id'])){

	if( ($_GET['perfil'] == '3') || (!empty($_GET['controlator'])) ){

		//eliminando anuncio o borrador
		if(!empty($_GET['controlator'])){
			//el ya distingue si borrar un anuncio o un borrador
			$Controller->controlador_juvilador();
			header('Location: '.$url);
			exit;
		}

		//tramando algo con imagenes de anuncio
		if($_GET['perfil'] == '3'){ 
			//eliminando imagen de anuncio
			if( (!empty($_GET['art'])) && (!empty($_GET['delimg'])) ){
				$Controller->delete_img_from_index($_GET['art'],$_GET['delimg']);
				header('Location: '.$url);
				exit;
			}		
			if( (!empty($_GET['art'])) && (!empty($_GET['prince'])) ){
				$Controller->promote_img_from_index($_GET['art'],$_GET['prince']);
				header('Location: '.$url);
				exit;
			}
		}

	}





	if( ($_GET['perfil'] == '20') || ($_GET['perfil'] == '21') ){

		$Alerta = new alertas_user();
		//elimina especializacion del propio usuario
		if($_GET['perfil'] == '20'){
			if(!empty($_GET['delete'])){
				$Alerta->eliminar_especializacion($_GET['delete']);
				header('Location: '.$url);	
				exit;
			}
		}
		//descarta alerta no interesante para el usuario
		if($_GET['perfil'] == '21'){
			if(!empty($_GET['descartar'])){
				$Alerta->descartar_alerta($_GET['descartar'],'descarte');
				header('Location: '.$url);
				exit;
			}
		}

	}




}

?>