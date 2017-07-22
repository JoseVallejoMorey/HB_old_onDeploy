<?php  
//utilizado para modificar info de empresa
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){


	includes_perfil_info();	//traigo includes
	$Perfil = new seg_builder();	
	$user   = $Perfil->id_from_salt($_POST['elegido']);
	$campos = array($_POST['campo'] => $_POST['titulo']);

	//modificar esta informacion
	$Perfil->where('id',$user);
	$Perfil->update('perfiles_emp',$campos);


}

?>