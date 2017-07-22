<?php

if($_POST['act'] == 'log2'){
	require_once 'inc/clases/seg/seg_mda.class.php';
	$Con = new seg_mda();
	$_SESSION['action_error'] = $Con->login2();

}else if ($_POST['act'] == 'baja'){

	require_once 'inc/clases/alertas/alertas.class.php';
	require_once 'inc/clases/procesos/sql_operations.class.php';
	require_once 'inc/clases/procesos/master.class.php';		
	require_once 'inc/clases/seg/seg_baja.class.php';
	
	$Perfil = new seg_baja();
	$Perfil->tramitar_baja();

}else if($_POST['act'] == 'log'){
	require_once 'inc/clases/seg/seg_login.class.php';
	$Login = new login();	
	$_SESSION['action_error'] = $Login->login();

}else if($_POST['act'] == 'reg') {	
	require_once 'inc/clases/seg/seg_registro.class.php';
	$Registro = new registro();	
	$_SESSION['action_error'] = $Registro->regUser();

}else{

	require_once 'inc/clases/seg/seg_lost_pw.class.php';
	$Con = new Seg_lost_pw();

	if($_POST['act'] == 'lost'){			
		$_SESSION['action_error'] = $Con->lostPw();
	}else  if($_POST['act'] == 'rewrite') {	
		$_SESSION['action_error'] = $Con->rewrite_pw();

		//header('Location: index.php');
	}	

}


?>