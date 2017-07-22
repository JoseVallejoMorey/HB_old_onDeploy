<?php  
//utilizado para modificar logo de empresa
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	// require '../../clases/procesos/sql_operations.class.php';
	//require '../../clases/seg/seg_actions.class.php';
	includes_perfil_info();	//traigo includes
	$Perfil = new seg_builder();	

	//obtenemos usuario 
	$user = $Perfil->id_from_salt($_POST['saltador']);
	$tabla = 'perfiles_emp';
	//asignamos tabla



	//se obtiene informacion 
	$Perfil->where('id',$user);

	if($salida = $Perfil->getOne($tabla,'img')){

		if($_POST['action'] == 0){
			//pongo el logo
			echo '<img src="imagenes/logo/'.$salida['img'].'" />';
		}else{
			//pongo input
			echo '<input id="imagenes" type="file" name="imagen" />';

			echo '<div class="foot-btn">
			        <input  class="btn btn-default" type="submit" value="Guardar" />   
			      </div> ';
		}

	}	

}

?>