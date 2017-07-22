<?php

//trae los formularios de registro login log-pass y rewrite-pass
//ademas recibe codigo de validacion, validacion de cambiar pasword y validacion de baja

//http://localhost:8888/base12.7.4/index.php?accion=down&validation=832de9f77f876afb98fcefa057150dc5f7c10ccee353ea087384f96a8f36a2bb103c232b5ad9d1c450fbee54ad64dff429e07fa4536d544c8b7b23c42ca0d4f8

$sha = '';
$lost = '';		//perdio su contraseña
$opcion = '';   //boton de registro o login

//validacion de correo del usuario (codigo que llega para validar)
if($_GET['accion'] == 'val'){
	require_once 'inc/clases/seg/seg_validation.class.php';
	$Perfil = new seg_validation();
	echo $Perfil->validando_usuario();
}else if ($_GET['accion'] == 'down'){
	//validacion de codigo de baja
	include_once 'inc/clases/seg/seg_baja.class.php';
	$Baja = new seg_baja();
	echo $Baja->confirmacion_baja();


}else{

	if($_GET['accion'] == 'reg'){
		//registro del usuario
		$name = 'reg';
		$form = 'form_reg';
		$opcion = '<a class="btn btn-info pull-left" href="index.php?accion=log">Acceso</a>';
	}else if($_GET['accion'] == 'log'){
		//login del usuario
		$name = 'log';
		$form = 'form_login';
		$opcion = '<a class="btn btn-info pull-left" href="index.php?accion=reg">Registro</a>';
		$lost = '<span><a href="index.php?accion=los">Perdi mi contraseña</a></span>';
	}else if($_GET['accion'] == 'los'){

		if(!empty($_GET['validation'])){
			//usuario introduciendo nueva contraseña
			require_once 'inc/clases/seg/seg_lost_pw.class.php';
			$Con = new Seg_lost_pw();
			if($Con->validationSha($_GET['validation']) ==true){	$code = $_GET['validation'];
			}else{													$code = false;				}

			$name = 'rewrite';
			$form = 'form_rewrite';
			$sha = '<input type="hidden" name="macro_sha" value="'.$code.'" />';

		}else{
		//usuario perdio password
			$name = 'lost';
			$form = 'form_lost';
		}
	}

// $opciones ='<button class="btn btn-sm btn-primary"><a href="index.php">Atras</a></button>';
// $opciones .= '<button class="btn btn-sm btn-danger">'.$opcion.'</button>';

			            
$opciones ='<a class="btn btn-primary pull-left" href="index.php">Atras</a>';
$opciones .= $opcion;



	echo '	<div id="content2" class="col-sm-12 full">
			  <div class="row">
				<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 login-box-locked">';	

	//formulario que compartiran reg, log, lost y rew
	//===================================================
	echo '<form name="'.$form.'" id="'.$form.'" method="post" class="form form-horizontal" rel="form_act" >
			<input type="hidden" name="act" value="'.$name.'"/>
			'.$sha.'
			<input type="hidden" name="ip_enc" value="'. md5($_SERVER['REMOTE_ADDR']).'"/>
			<input type="hidden" name="aleatorio" value="'. $_SESSION['invisible']['token_key'].'"/>';

	include 'scripts/reg/form_'.$name.'.php';

	echo 		'<div id="error-container">';
					if(!empty($_SESSION['action_error'])){
						echo'<div id="" class="error-report alert alert-danger">'
						.$_SESSION['action_error'].'</div>';
					}
	echo 		'</div>';		

	echo '<div class="form-group form-actions">
			<div class="pull-left">'.$opciones.'</div>
			<span class="pull-left">'.$lost.'</span>
			<div class="pull-right">
			<input  class="btn btn-default" type="submit" value="enviar"/>
			</div>   
			
		  </div>';	

	echo 	'</form>';

	echo '</div></div></div>';
		


}

?>