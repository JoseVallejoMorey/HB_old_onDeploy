<?php


if(empty($_GET['lg'])){
	
	if(empty($_SESSION['lg'])){
		$_SESSION['lg'] = 'esp';
	}
	
}else{
	
	//verifico que idioma existe
	$idiomas_permitidos = array('esp','eng','ger');
	if(in_array($_GET['lg'],$idiomas_permitidos)){
		$_SESSION['lg'] = $_GET['lg'];
	}else{
		$_SESSION['lg'] = 'esp';
	}
	$u = explode('lg', $Config->url);
	$pasadaurl = $u[0];
	
	//var_dump($Config->url);
	//header('Location: '.$pasadaurl);
}


?>