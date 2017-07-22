<?php 
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){
	require '../../clases/mysqlidb.main.php';
	require '../../clases/seg/seg_builder.class.php';
	require '../../clases/builders.class.php';

	$Con = new Builders();

	//preparo
	$user 	 = $Con->Perfil->id_from_salt($_POST['wey']);
	$anuncio = $Con->resolver_enigma($_POST['electo']);

	$mensaje = array('0' => 'Desactivado', '1' => 'Activo');
	$Con->where('id',$anuncio);
	$campos = array('ussr','activo');
	$salida = $Con->getOne('anuncios',$campos);

	//el user que envia es el propietario del anuncio
	if($salida['ussr'] == $user){

		if($salida['activo'] == 1){$new_activo = 0;
								   $fecha_dess = $Con->now;
		}else{					   $new_activo = 1;	
								   $fecha_dess = '';}

		$campo = array('activo' 		=> $new_activo,
					   'fecha_inactivo' => $fecha_dess);
		$Con->where('id',$anuncio);
		$Con->update('anuncios',$campo);


		echo $mensaje[$new_activo];



	}


}





?>