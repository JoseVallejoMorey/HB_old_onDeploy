<?php

//comprando traduccion de anuncios
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	//includes correspondientes
	resolve_includes();
	//objetos necesarios
	$Fechas = new Fechas();
	$Prodd 	= new Productos();
	$Con  	= new Sql_operations();
	//variables
	$cantidad = $_POST['cantidad'];

	if($cantidad > 0){
		$producto = $Prodd->get_sumable('traduccion');
		//respuesta
		echo $Prodd->inputs_for_paypal($producto,$cantidad);
		echo $Fechas->mostrar_precio_detallado(NULL, NULL, $producto,NULL,$cantidad);	
		echo $Con->termandcon();
		echo '<div class="foot-btn">
				<input class="btn btn-default" type="submit" value="comprar">	
			  </div>';
	}else{
		echo 'Seleccione almenos un anuncio';
	}

	
}
?>