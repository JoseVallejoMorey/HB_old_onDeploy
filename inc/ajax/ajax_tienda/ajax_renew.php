<?php
//renovando (star, special, banners)
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){
	
	//includes correspondientes	
	resolve_includes(true);

	//objetos necesarios
	$Fechas = new fechas();
	$Con  	= new builders();
	$Prodd 	= new Productos();
	$Perfil = new seg_builder();

	$id_electo = $_POST['electo'];
	$usuario = $Perfil->id_from_salt($_POST['saltador']);

	$Fechas->where('id',$id_electo);
	if($salida = $Fechas->getOne('reservas_efectivas')){

		if($usuario == $salida['user']){
			$datetime1 = new DateTime($salida['fecha_inicio']);
			$datetime2 = new DateTime($salida['fecha_final']);
			$interval = $datetime1->diff($datetime2);
			$dias = $interval->format('%r%a');

			$periodo 		= $dias + 1;		//periodo oficial
			$periodo_sumar 	= $dias + 0;		//periodo a sumar
			$fecha_minima 	= $Fechas->periodo($salida['fecha_final'],1);
			$producto 	  	= $Prodd->get_product($salida['reserva'],$periodo);


			if($salida['reserva'] == 'star_area'){
				//obtenemos tabla correspondiente
				$Fechas->decidir_tabla(NULL, $salida['especificacion'], NULL);
				$renuevo = $salida['anuncio'];
				//si es star; provincia
				echo '<input type="hidden" name="zona"    value="'.$salida['especificacion'].'" />
					  <input type="hidden" name="anuncio" value="'.md5($salida['anuncio']).'"/>
					  <input type="hidden" name="tienda"  value="star_area"/>';
			}else if($salida['reserva'] == 'special'){
				//obtenemos tabla correspondiente
				$Fechas->decidir_tabla($salida['especificacion'], NULL, NULL);
				$renuevo = $usuario;				
				//es special; seccion
				echo '<input type="hidden" name="tienda" value="special_area"/>';
			}else if($salida['reserva'] == 'banner'){
				//obtenemos tabla correspondiente
				$Fechas->decidir_tabla(NULL, NULL, $salida['especificacion']);
				$renuevo = $usuario;				
				//es banner; tipo de banner 
				echo '<input type="hidden" name="tienda" value="banner"/>
					  <input type="hidden" name="tipo_bann" value="'.$salida['especificacion'].'"/>';
				
			}

			//fechas
			$primera_disp = $Fechas->primeraFecha($renuevo,$fecha_minima);	//primera fecha disponible
			$fecha_final  = $Fechas->periodo($primera_disp, $periodo_sumar);//fecha final	

			//inputs para paypal
			echo $Prodd->inputs_for_paypal($producto);

			//los hidden comunes a los tres tipos de service
			echo '<input type="hidden" name="fecha_inicio" value="'.$primera_disp.'"/>
			 	  <input type="hidden" name="fecha_final"  value="'.$fecha_final.'"/>
			 	  <input type="hidden" name="periodo" 	   value="'.$periodo.'"/>';

			echo $Fechas->mostrar_precio_detallado($primera_disp, $fecha_final, $producto);
			echo $Con->termandcon();
			echo'<div class="foot-btn">
					<input class="btn btn-default" type="submit" value="comprar">
				</div>';
		}
	}
}

?>