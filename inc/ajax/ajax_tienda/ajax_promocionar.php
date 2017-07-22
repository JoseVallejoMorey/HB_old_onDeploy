<?php
//este ajax sirve solo para informar al cliente del precio de lo que compra

include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	//includes que sean
	resolve_includes();
	//objetos
	$Con    = new builders();
	$Fechas = new fechas();
	$Prodd 	= new Productos();

	if( (!empty($_POST['periodo'])) && (!empty($_POST['cantidad'])) ){

		$periodo  	 = $_POST['periodo'];
		$cantidad 	 = $_POST['cantidad'];
		$producto 	 = $Prodd->get_promo($cantidad,$periodo);
		$fecha_final = $Fechas->periodo($Fechas->date, $periodo);

		//inputs para paypal
		echo $Prodd->inputs_for_paypal($producto);
		echo $Fechas->mostrar_precio_detallado($Fechas->date, $fecha_final, $producto);
		echo $Con->termandcon();
		echo '<div class="foot-btn">
				<input class="btn btn-default" type="submit" value="comprar">	
			  </div>';
	}
}

?>