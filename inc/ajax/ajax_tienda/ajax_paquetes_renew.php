<?php
//renovando (paquetes)
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	//includes correspondientes
	resolve_includes(true);

	//objetos necesatios
	$Fechas = new fechas();
	$Con  	= new builders();
	$Prodd 	= new Productos();
	$Perfil = new seg_builder();	

	$id_electo = $_POST['electo'];
	$usuario = $Perfil->id_from_salt($_POST['saltador']);

	$Fechas->where('id',$id_electo);
	if($salida = $Fechas->getOne('paquetes')){

		$fecha_minima = $Fechas->periodo($salida['fecha_final'],1);
		//$tipo = 'paquete'.$salida['paquete'].'_'.$salida['duracion'];
		$tipo = $salida['producto'];
		$producto = $Prodd->get_paquete($tipo,'id');

		//comportamiento distinto si es renovar o recomprar
		if($salida['estado'] == 3){
			// recomprar
			$acto = 'rebuy';
			//periodo contratado (que hay en db) apartir de fecha actual (hoy)
			$fecha_final  = strtotime ('+'.$salida['duracion'].' month' , strtotime ($Fechas->date));
			$fecha_inicio = $Fechas->date;

		}else{
			// renovar 
			$acto = 'renew';
			//periodo contratado (que hay en db) apartir de fecha final +1
			$fecha_final  = strtotime ('+'.$salida['duracion'].' month' , strtotime ($fecha_minima));
			$fecha_inicio = $fecha_minima;
		}
		//convertimos a un formato comprensible
		$fecha_final 	  = date ( 'Y-m-j' , $fecha_final );

		//renovacion o recompra, tu decides
		echo '<input type="hidden" name="'.$acto.'" value="'.$salida['id'].'"/>';
		//inputs para paypal
		echo $Prodd->inputs_for_paypal($producto);
		//detalles de la compra
		echo $Fechas->mostrar_precio_detallado($fecha_inicio, $fecha_final, $producto);
		//terminos y condiciones
		echo $Con->termandcon();

		echo'<div class="foot-btn">
				<input class="btn btn-default" type="submit" value="comprar">
			</div>';

	}

}

?>