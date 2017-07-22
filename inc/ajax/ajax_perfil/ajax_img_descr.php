<?php

include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){	
	
	if(!empty($_POST['elegido'])){
		
		includes_edit_anuncio();

		$cols = array('descripcion' => $_POST['descripcion']);
		$Con = new Usuarios();
		$Con->where('id',$_POST['elegido']);
		$Con->update('anuncios_img',$cols);
	}
}
?>