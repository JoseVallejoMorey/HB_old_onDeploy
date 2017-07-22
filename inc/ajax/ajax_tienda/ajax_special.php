<?php

// utilizada para special 
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	$seccion = NULL;	
	//includes correspondientes
	resolve_includes(true);

	$Fechas = new Fechas();
	$Con    = new builders();
	$Prodd 	= new Productos();
	$Perfil = new seg_builder();	

	if(!empty($_POST['saltador'])){
		$saltador = $_POST['saltador'];
		$usuario = $Perfil->id_from_salt($saltador);
	}

	if(!empty($_POST['seccion'])){
		if( $_POST['seccion'] == 'alquiler temporal'){
			$_POST['seccion'] = 'alquiler';
		}
		$seccion = $_POST['seccion'];	
	}

	if(!empty($_POST['periodo'])){	$periodo = $_POST['periodo'];	}

	//comprobamos tenga almenos 4 anuncios
	$Con->where('ussr',$usuario);
	$c = $Con->get('anuncios');
	$cuantos = count($c);
	if($cuantos < 4){
		echo '<p class="msj_p">Necesita almenos 4 anuncios para participar en esta seccion</p>';

	}else{

	
		//sea cual sea el periodo comprado hay que restarle un dia, el dia de hoy cuenta
		$periodo_incluye_hoy = $periodo - 1;
		//obtenemos tabla correspondiente
		$Fechas->decidir_tabla($seccion, NULL, NULL);
		$primera_disp   = $Fechas->primeraFecha($usuario);
		$fecha_final    = $Fechas->periodo($primera_disp, $periodo_incluye_hoy);
		$producto 		= $Prodd->get_product('special',$periodo);

		echo $Fechas->mostrar_precio_detallado($primera_disp, $fecha_final, $producto);

		//inputs para paypal
		echo $Prodd->inputs_for_paypal($producto);

		echo' <input type="hidden" name="tienda" value="special_area"/>
			  <input type="hidden" name="'.$seccion.'" value="1"/>
			  <input type="hidden" name="periodo" value="'.$periodo.'"/>
			  <input type="hidden" name="fecha_inicio" value="'.$primera_disp.'"/>
			  <input type="hidden" name="fecha_final" value="'.$fecha_final.'"/>';
		echo  $Con->termandcon();
		echo' <div class="foot-btn">
			  	  <input class="btn btn-default" type="submit" value="comprar">	
			  </div>';
	}

}
?>