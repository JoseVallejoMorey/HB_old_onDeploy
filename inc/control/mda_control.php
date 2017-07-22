<?php 


if (!empty($_SERVER['HTTP_REFERER'])) {
  $url = $_SERVER['HTTP_REFERER'];
}


//actualiza un perfil de empresa para que sea visible
if(!empty($_GET['mpresa'])){

	$Perfil = new seg_sesion();
	//actualizamos el perfil de empresa a visible
	if($user = $Perfil->id_from_salt($_GET['mpresa'])){
		$Perfil->where('id',$user);
		$cols = array('visible' => 1);
		$Perfil->update('perfiles_emp',$cols);
	}
	header('Location: '.$url);
}



//se eliminara usuario forever
if(!empty($_GET['artmda'])){

	$Master = new Master();
	$Alerta = new Alertas();
	//identificamos al usuario y lo exterminamos
	if($user = $Perfil->id_from_salt($_GET['artmda'])){
		$Master->exterminar_usuario($user,$Alerta);
	}

	//header('Location: '.$url);
}

//elimina un anuncio
if(!empty($_GET['deletear'])){

	$Master = new Master();

	if($limpio = $Master->resolver_enigma($_GET['deletear'])){
		//se borraria anuncio si no lo impido
		//$Master->eliminar_anuncio_yamigos($limpio)
	}else{
		//var_dump('no puede borrarse el anuncio, no esta en enigma');
	}
}



if(!empty($_GET['mantenimiento'])){

	$Master = new Master();

	if($_GET['mantenimiento'] == 'reservas'){
		$Master->mantenimiento_reservas();
	}
	if($_GET['mantenimiento'] == 'paquetes'){
		//si hay paquete es que hay un paquete concreto sobre el que actuar
		if(!empty($_GET['paquete'])){
			$Master->Reserva->where('id',$_GET['paquete']);
			if($salida = $Master->Reserva->getOne('paquetes','estado')){
				if( (empty($salida['estado'])) || ($salida['estado'] == '') ){
					//desactivar
					$Master->desactivar_paquete($_GET['paquete'],2);
				}else{
					//activar
					$Master->activar_paquete($_GET['paquete']);
				}
			}
		}else{
			//solo mantenimiento=paquetes buscar caducados
			$Master->mantenimiento_paqueteria();
		}
		header('Location: index.php?perfil_mda=8');
	}

}

//desactivar secciones o directivas
if(!empty($_GET['secciones'])) {
	if(!empty($_GET['sujeto'])){
		$Config->activar_desactivar_secciones_directivas($_GET['secciones'],$_GET['sujeto']);
		//header('Location: '.$url);
	}
}

if(!empty($_GET['directivas'])){
	if(!empty($_GET['sujeto'])){
		$Config->activar_desactivar_secciones_directivas($_GET['directivas'],$_GET['sujeto']);
		//header('Location: index.php?perfil_mda=10');
	}
}

//activar o desactivar secciones de landing (general o especificas)
if( (!empty($_GET['landing_sec'])) || (!empty($_GET['landing_esp'])) ){
	if(!empty($_GET['sujeto'])){
		$Config->landing_interruptor($_GET['sujeto']);
	}
}

//activar o desactivar secciones de landing 
// if(!empty($_GET['landing_esp'])) {
// 	if(!empty($_GET['sujeto'])){
// 		$Config->landing_interruptor($_GET['sujeto']);
// 	}
// }








//recibira el link que quiere borrar y de donde borrarlo
if(!empty($_GET['link_operator'])){
	if(!empty($_GET['linkdel'])){
		$Config->link_deleteator($_GET['linkdel']);
		header('Location: '.$url);
	}
}

?>