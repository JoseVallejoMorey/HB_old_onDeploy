<?php

//clase hermana de Tienda, comparten funciones en reserva_builder
//y son idivisibles


//include_once'inc/clases/procesos/reserva_builder.class.php';

class Fechas extends reserva_builder {
	
	public $tabla;
	public $max;

	//constructor 
	public function __construct(){
			parent::__construct();
		}
	
	public function periodo($fecha_inicio, $periodo){
		
		$fechitas = explode("-", $fecha_inicio);
		$fecha_final  = date("Y-m-j", mktime(0, 0, 0, $fechitas['1'], $fechitas['2']+$periodo, $fechitas['0']));
		return $fecha_final;
	}//fin funcion
	
	
	
	//2-descerar le quita el 0 a un numero solo si esta a la izquierda		
	protected function descerar($cosa){
		$pos = strpos($cosa, '0');
		if ($pos === false) {$mes = $cosa;
		}else{
			if ($pos == 0){  $mes = str_replace("0","",$cosa);
			}else{			 $mes = $cosa; }	   
		}
		return $mes;
	}
	
	

	//convierte "yyyy-mm-dd" en un array "$dia de $mes de $YYYY"
	public function hacerLegible($fecha){
			
		$months = array( 
			 0 => '00',
			 1 => 'Enero', 		 2 => 'Febrero', 
			 3 => 'Marzo', 		 4 => 'Abril', 
			 5 => 'Mayo', 		 6 => 'Junio', 
			 7 => 'Julio', 	     8 => 'Agosto', 
			 9 => 'Septiembre', 10 => 'Octubre', 
			11 => 'Noviembre',  12 => 'Diciembre'
		);		  
				
		$segunda = explode("-", $fecha);
		$dia = $this->descerar($segunda['2']);
		$mes = $this->descerar($segunda['1']);
		if(empty($mes)){$mes = 0;}
				
		$vuelta = array( 1 => $dia, 2 => $months[$mes], 3 => $segunda['0']);
		
	return $vuelta;			
	}
	
	
	
	
	
	//primera fecha disponible para star_area, special_area y banners
	public function primeraFecha($user = NULL,$fecha_minima = NULL){
//$seccion = NULL,$zona = NULL,$banner = NULL,
		//fecha minima si es renovar, sino hoy
		if(!is_null($fecha_minima)){	$fecha_inicial = $fecha_minima;
		}else{							$fecha_inicial = $this->date;	}

		//obtenemos tabla correspondiente
		//$this->decidir_tabla($seccion,$zona,$banner);

		//seleccionamos fecha 
		$this->where('date',array('>=' => $fecha_inicial));
		$this->where('full',0);
		$this->orderBy("date","asc");

		//buscara en $tabla proxima fecha disponible(no ocupada por el)
		//si no encuentra o no existe creara una fecha nueva
		//y que pasara si existe pero esta full??????

		if($salida = $this->get($this->tabla)){

			$proximo = $this->proximo_hueco('',$this->tabla,$user);

			if(is_null($proximo)){
				//añado dia siguiente al ultimo en tabla
				$nuevo_dia = $this->crear_siguiente_existente($this->tabla, $user, NULL);
				return $nuevo_dia;

			}else{
				//este es el primer dia disponible para el
				return $proximo;
			}

		}else{
			$nuevo_dia = $this->crear_siguiente_existente($this->tabla, $user, NULL);
			return $nuevo_dia;
		}

	}
		
	



	//devolvera tabla correspondiente segun los datos
	public function decidir_tabla($seccion = NULL,$zona = NULL,$banner = NULL){
		if(!is_null($zona)){	
			$this->tabla ='reserva_star_'.$zona;
			$this->max = 3;
		}
		//se trata de un special_area, recibe seccion, this->tabla sobreescribe bien.
		if(!is_null($seccion)){		
			$this->tabla ='reserva_special_'.$seccion;
			$this->max = 8;
		}
		if(!is_null($banner)){
			//y si es renovar sera tabla tipo
			$this->tabla ='reserva_banners_'.$banner;
			$this->max = 8;
		}
	}























	//mostraremos precio y detalles
	//============================================================
	public function mostrar_precio_detallado($finicio = NULL, $ffinal = NULL, $prod, $new = NULL,$cant = NULL){
	
		$return ='<table id="precio_final" class="">';
		if(!is_null($finicio)){
			$inicio = $this->hacerLegible($finicio);
			$return .= '<tr><td >Su anuncio sera visible desde el </td>
						<td>'.$inicio[1].'/'.$inicio[2].'/'.$inicio[3].'</td></tr>';
		}

		if(!is_null($ffinal)){
			$final = $this->hacerLegible($ffinal);
			$return .= '<tr><td > Hasta el </td>
						<td>'.$final[1].'/'.$final[2].'/'.$final[3].'</td></tr>';
		}

		if(!is_null($cant)){
			$return .= '<tr><td>Cantidad</td><td>'.$cant.'</td></tr>';
		}
		if($prod['cantidad'] != '0'){
			$return .= '<tr><td>Anuncios</td><td>'.$prod['cantidad'].'</td></tr>';
		}
		if($prod['duracion'] != '0'){
			$return .= '<tr><td>Periodo</td><td>'.$prod['duracion'].' Dias</td></tr>';
		}
		if($prod['precio'] != '0'){
			if(!is_null($cant)){
				$total = $prod['precio']*$cant;
				$return .= '<tr><td>Precio</td><td>'.$total.' €</td></tr>';
			}else{
				$return .= '<tr><td>Precio</td><td>'.$prod['precio'].' €</td></tr>';
			}
		}

		if(!is_null($new)){
			$total = $new['precio'] + $prod['precio'];

			$return .= '<tr><td>'.$new['descripcion'].'</td><td>'.$new['precio'].' €</td></tr>';
			$return .= '<tr><td>Total</td><td>'.$total.' €</td></tr>';

		}






		$return .= '</table>';

		return $return;
	}




}



?>