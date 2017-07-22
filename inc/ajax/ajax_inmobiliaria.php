<?php
//ajax encargado de consulta de anuncios en pagina propia de inmobiliaria
include 'ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){


	//includes para busqueda
	includes_busqueda();
	//especifico para busqueda empresa
	require '../clases/public/empresa.class.php';  	//empresa

	//objetos
	$Con = new salida_anuncios();

	if(!empty($_POST['empresa'])){
		$Empresa = new Empresa();
	}

	echo $Con->paginacion_anuncios($Empresa);

}else{
	var_dump('fallo');
}

?>