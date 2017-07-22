<?php
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	include '../../vars.php';
	
	resolve_includes();

	$Con = new Sql_operations();

	$devolver ='';

	// se trae todas las poblaciones
	if($_POST['que'] == 1){
		$salida = $Con->get('municipios',NULL,'municipio');
		foreach ($salida as $key => $value) {
			$devolver .= '<option>'.$value['municipio'].'</option>';
	}	}


	//si es 2 se trae todos las zonas
	if($_POST['que'] == 2){
		if($salida = $Con->get('zonas',NULL,'zona')){
			foreach ($salida as $key => $value) {
				$devolver .= '<option>'.$value['zona'].'</option>';
	}	}	}




	//si es 3 se trae todos los subtipos
	if($_POST['que'] == 3){
		if($salida = $Con->get('subtipo_inmueble',NULL,'esp')){
			foreach ($salida as $key => $value) {
				$devolver .= '<option>'.$value['esp'].'</option>';
	}	}	}

	//tipos de venta
	if($_POST['que'] == 4){
		foreach ($__tipo_venta as $key => $value) {
			$devolver .= '<option>'.$value.'</option>';
	}	}

	//tipos de estado
	if($_POST['que'] == 5){
		foreach ($__estado as $key => $value) {
			$devolver .= '<option>'.$value.'</option>';
	}	}

	//se trae todos los extra	
	if($_POST['que'] == 6){
		if($salida = $Con->get('extras',NULL,'esp')){
			foreach ($salida as $key => $value) {
				$devolver .= '<option>'.$value['esp'].'</option>';
	}	}	}


	echo $devolver;

}

?>