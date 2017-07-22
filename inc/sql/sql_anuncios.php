<?php

include_once 'inc/clases/seg/seg_actions.class.php';	//seg sessions class
include_once 'inc/clases/procesos/sql_anuncios_pro.class.php';
require_once 'inc/clases/seg/seg_lost_pw.class.php';
include_once 'inc/clases/uploadimg.class.php';			//subiendo imagenes o logo

//objetos
$Con 	= new sql_anuncios_pro();
$Perfil = new seg_lost_pw();	//new_user_pw necesita metodos mas avanzados que builder

if (!empty($_SERVER['HTTP_REFERER'])) {
  $url = $_SERVER['HTTP_REFERER'];
}


if(in_array($_POST['form_to'],$permitidos['form_to'])){
	
	$form_to = $_POST['form_to'];
	$Con->tipo_usuario = $_SESSION['tipo'];

	//me guardo variables
	if(!empty($_POST['ussr'])){					$Con->user = $_POST['ussr'];
	}else if (!empty($_SESSION['user_id'])){	$Con->user = $_SESSION['user_id'];	}
	
	//se trara de un perfil de usuario
	// if(strstr($form_to,'perfil')){
		
	// 	if($_POST['form_to'] == 'perfil_cambiar_contrasena'){
	// 		//cambiar contraseña del usuario
	// 		$_SESSION['action_error'] = $Con->new_user_pw($Perfil);
	// 		//header('location:index.php?perfil=9');
	// 	}else if ($_POST['form_to'] == 'perfil_baja'){
	// 		//usuario quiere darse de baja

	// 	}else{
	// 		//creando o actualizando datos empresa o usuario
	// 		$Con->sql_perfil_preparador();
	// 		// header('Location: '.$url);
	// 	}
			
	// }
	if($form_to == 'perfil_cambiar_contrasena'){
		//cambiar contraseña del usuario
		$_SESSION['action_error'] = $Con->new_user_pw($Perfil);		
	}
	if($form_to == 'perfil_baja'){
		//usuario quiere darse de baja
	}

	if($form_to == 'empresa_fondo'){
		$Con->sql_logo_fondo('empresa_fondo');
	}
	if($form_to == 'logo_empresa'){
		$Con->sql_logo_fondo('perfiles_emp');
	}

	if($form_to == 'nuevo_agente'){
		$Con->sql_oficina_agentes('empresa_agentes');
	}
	if($form_to == 'nueva_oficina'){
		$Con->sql_oficina_agentes('empresa_oficinas');
		//header('Location: '.$url);
	}





	if($form_to == 'new_anuncio'){
		$Con->sql_anuncio_preparador();
	}
	if($form_to == 'lista_fotos'){
		$Con->sql_img_preparador();
		//header('Location: '.$url);
	}
	if($form_to == 'idiomas_anuncio'){
		$Con->sql_idiomas_preparador();
		header('Location: '.$url);
		exit;
	}
	if($form_to == 'anuncio_multi'){
		//nada
	}


}else{
	//raro, header y report
	header('Location: '.$url);
	exit;
}
		



?>