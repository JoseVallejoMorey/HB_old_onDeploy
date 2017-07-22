<?php 

include 'ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){
	if(!empty($_POST['precio_min'])){

		require '../clases/mysqlidb.main.php';
		require '../clases/seg/seg_builder.class.php';
		require '../clases/form_busqueda_builder.class.php';

		$Form = new busqueda_builder();
		//var_dump($_POST['precio_min']);
		echo $Form->buscador_precio('max',$_POST['precio_min']);

	}
}

?>