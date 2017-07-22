<?php

include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	if($_POST['elegido']){
		
		$periodo 		= $_POST['periodo'];
		$anuncio_bruto  = $_POST['elegido'];

		//includes correspondientes
		resolve_includes();
		//inicio de objetos
		$Fechas = new Fechas();	
		$Prodd 	= new Productos();
		$Con 	= new Sql_operations();

		//si tiene menos de tres imagenes no puede continuar
		$Con->where('id_e',$anuncio_bruto);
		$salida = $Con->get('anuncios_img');
		$cuantos = count($salida);

		if($cuantos < 3){
			echo 'Necesita un minimo de tres imagenes en el anuncio';
		}else{

			$anuncio = $Con->resolver_enigma($anuncio_bruto);
			$cols = array('id','provincia','tipo_venta');
			$Con->where('id',$anuncio);
			if($salida = $Con->getOne('anuncios',$cols)){

				//recibiremos array con info del producto
				$producto = $Prodd->get_product('star_area',$periodo);
				echo $Prodd->inputs_for_paypal($producto);				

				//===============================================================================
				$periodo_incluye_hoy = $periodo - 1;
				//obtenemos tabla correspondiente
				$Fechas->decidir_tabla(NULL, $salida['provincia'], NULL);
				$primera_disp    = $Fechas->primeraFecha($anuncio);
				$fecha_final     = $Fechas->periodo($primera_disp, $periodo_incluye_hoy);	

				echo $Fechas->mostrar_precio_detallado($primera_disp, $fecha_final, $producto);		
				
				echo'   <input type="hidden" name="zona"    	 value="'.$salida['provincia'].'" />
						<input type="hidden" name="seccion"      value="'.$salida['tipo_venta'].'" />
						<input type="hidden" name="anuncio"      value="'.$anuncio_bruto.'"/>
						<input type="hidden" name="tienda"       value="star_area"/>
				 		<input type="hidden" name="periodo" 	 value="'.$periodo.'"/>
						<input type="hidden" name="fecha_inicio" value="'.$primera_disp.'"/>
				  		<input type="hidden" name="fecha_final"  value="'.$fecha_final.'"/>';
				echo 	$Con->termandcon();
				echo'  	<div class="foot-btn">
						  	<input class="btn btn-default" type="submit" value="comprar">	
					  	</div>';
			}
		}//minos de 3 imagenes 
	}

}
?>