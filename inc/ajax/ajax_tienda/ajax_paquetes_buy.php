<?php
//comprando paquete nuevo, envio precio y hiddens del form
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	include '../../precios.php';
	//includes correspondientes
	resolve_includes();
	//objetos necesarios
	$Fechas = new fechas();
	$Con  	= new builders();
	$Prodd 	= new Productos();

	if( ($_POST['anuncios'] != 'null') && ($_POST['duracion'] != 'null') ){
		//ningun nulo, le muestro precio y campo hidden con esa info
		$tipo = 'paquete'.$_POST['anuncios'].'_'.$_POST['duracion'];
		$producto = $Prodd->get_paquete($tipo);
		
		$fecha_inicio = $Fechas->date;
		$fecha_final  = strtotime ('+'.$_POST['duracion'].' month' , strtotime ($Fechas->date));
		$fecha_final  = date ( 'Y-m-j' , $fecha_final );

		//inputs para paypal
		echo $Prodd->inputs_for_paypal($producto);
		echo $Fechas->mostrar_precio_detallado($fecha_inicio, $fecha_final, $producto);
		echo $Con->termandcon();

		echo'<div class="foot-btn">
			<input class="btn btn-default" type="submit" value="comprar">
		</div>';
	}
	
}