<?php  
//utilizado para modificar info del usuario (particular o empresa)
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	includes_perfil_info();	//traigo includes
	$Perfil = new seg_builder();	
	$campos = array($_POST['campo'] => $_POST['titulo']);

	$Perfil->where('salt',$_POST['elegido']);
	$Perfil->update('usuarios',$campos);

}

?>