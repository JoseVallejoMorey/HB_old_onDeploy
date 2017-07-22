<?php

include 'ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	include '../vars.php';

	includes_simples();
	$Con = new builders(); 
	$lang = 'esp';

	if(!empty($_POST['lg'])) {
		if(array_key_exists($_POST['lg'],$__idiomas)){
			$lang = $_POST['lg'];
		}
	}

	if(!empty($_POST['inmueble'])) {
		$Con->where('tipo_inmueble',$_POST['inmueble']);
		$salida = $Con->get('subtipo_inmueble');
		echo '<option value="0">Todos</option>';

		foreach($salida as $value){
			echo'<option value="'.$value['id'].'">'.ucfirst($value[$lang]).'</option>';
		}	
	}

}

?>