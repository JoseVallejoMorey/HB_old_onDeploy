<?php
include 'ajax_friend.php';
// require '../clases/mysqlidb.main.php';
// require '../clases/seg/seg_builder.class.php';
// require '../clases/builders.class.php';

if( (requested() == true) && (tok_y_token() == true) ){

	includes_simples();

	$Con = new builders(); 

	if( (!empty($_POST['provincia'])) && (is_numeric($_POST['provincia'])) ){
		$id_prov = $_POST['provincia'];
		$Con->where('zona',$id_prov);

		$salida = $Con->get('municipios');
		echo '<option value="0">todos</option>';
		foreach($salida as $value){
			$selected = '';
			if($value['id'] == $_POST['poblacion']){$selected = 'selected';}
			echo'<option value="'.$value['id'].'" '.$selected.'>'.
			ucfirst($value['municipio']).'</option>';
		}	
	}	

}




	
?>