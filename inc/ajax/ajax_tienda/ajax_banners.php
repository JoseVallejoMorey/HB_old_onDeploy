<?php

include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	//includes correspondientes
	resolve_includes(true);	
	//objetos
	$Fechas = new fechas();
	$Prodd 	= new Productos();
	$Con 	= new Sql_operations();
	$Perfil = new seg_builder();	


	if(!empty($_POST['seccion'])){$seccion = $_POST['seccion'];}
	if(!empty($_POST['periodo'])){$periodo = $_POST['periodo'];}

	if(!empty($_POST['saltador'])){
		$saltador = $_POST['saltador'];
		$Con->user = $Perfil->id_from_salt($saltador);
	}	

		
	//seleccion de tipo de banner y si es nuevo
	if(!empty($_POST['tipo_bann'])){
		if($_POST['tipo_bann']=='nuevo'){
			if(!empty($_POST['tipo_nuevo'])){
				$tipo  = $_POST['tipo_nuevo'];
				$nuevo = 'nuevo';
			}else{
				echo'Seleccione un tipo de banner';
				die;
			}
			
		}else{
			//renovar
			$tipo = $_POST['tipo_bann'];
			$nuevo ='renovar';
			//echo 'renovar un banner '.$tipo;	
		}

		if($tipo == 'superior'){	  $numero = 4; 
		}else if($tipo == 'lateral') {$numero = 4; 
		}else if($tipo == 'central') {$numero = 8; }


	}else{
		//tipo_bann es vacio
		echo'Seleccione un tipo de banner';
		die;
	}

		//obtenemos tabla correspondiente
		$Fechas->decidir_tabla(NULL, NULL, $tipo);	
		
		//hay que consultar si el usuario ha comprado
		$hoy 		  = date("Y-m-d", mktime(0, 0, 0,  date("m"),date("d"), date("Y")));
		$hoy_mas3 	  = $Fechas->periodo($hoy,3);
		$primera_disp = $Fechas->primeraFecha($Con->user);
		$producto 	  = $Prodd->get_banner($tipo,$periodo);
		$newbann 	  = $Prodd->get_product('banner',0);
		

		//si la fecha que tengo es menor que la de hoy mas tres dias tendre que guradar un
		//margen de tiempo para hacer el banner, asi que le añado tres dias a la fecha 
		//(todo esto solo si no tiene banner)
		if($nuevo == 'nuevo'){
			if($primera_disp < $Fechas->periodo($hoy,3)){
				$primera_disp = $Fechas->periodo($primera_disp,3);			
			}
		}

		$incluye_hoy = $periodo - 1;
		$fecha_final = $Fechas->periodo($primera_disp, $incluye_hoy);		

		//inputs para paypal
		echo $Prodd->inputs_for_paypal($producto);

		//precio final con banner nuevo si hay
		if($nuevo == 'renovar'){  
			$precio_final = $producto['precio'];
			$newbann = NULL;	//reseteamos para no añadirlo
		}else{					  
			$precio_final = $producto['precio'] + $newbann['precio'];
			//niputs para nuevo bann
			echo '<input type="hidden" name="new_bann" value="true"/>';

		}

		//el form se inicia en perfil_banners	
		echo'<input type="hidden" name="tienda" value="banner"/>
			 <input type="hidden" name="fecha_inicio" value="'.$primera_disp.'"/>
			 <input type="hidden" name="fecha_final" value="'.$fecha_final.'"/>';
			  
		echo $Fechas->mostrar_precio_detallado($primera_disp, $fecha_final, $producto, $newbann);

		echo $Con->termandcon();
		
		echo'<div class="foot-btn">
				<input class="btn btn-default" type="submit" value="comprar">
			</div>

	</div>';
}
?>