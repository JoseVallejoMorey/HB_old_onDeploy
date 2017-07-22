<?php

//formularios que provienen de mda

$control = array('estado','nu_link');
$pedidos = array('new_tradd','new_bann');

//cumpliendo con pedidos
if(in_array($_POST['mda_control'], $pedidos )){

require_once 'inc/clases/procesos/pedidos_process.class.php';
$Pedidos  = new PedidosProcess();	//info de pedidos y metodos

	//nueva traduccion de anuncio
	if($_POST['mda_control'] == 'new_tradd'){
		$Pedidos->procesar_traduccion();
	}	
	//nuevo banner asignado a usuario
	if($_POST['mda_control'] == 'new_bann'){
		require_once 'inc/clases/uploadimg.class.php';
		$Upimg 	  = new Upimg();		//funciones de imagen
		$Users 	  = new seg_builder();	//info de empresa para banner

		$Pedidos->procesar_info_new_bann($Upimg,$Users);
	}

//controles de la web
}else if(in_array($_POST['mda_control'], $control)){
	//cambiando el estado del portal
	if($_POST['mda_control'] == 'estado'){
		if(!empty($_POST['nuevo_estado'])){
			//comprobacion de sesion
			$Config->cambio_de_estado($_POST['nuevo_estado']);
		}
	//creando nuevos links autorizados
	}else if($_POST['mda_control'] == 'nu_link'){
		$Config->link_foot_storator($__links_posibles);
	}

}





?>