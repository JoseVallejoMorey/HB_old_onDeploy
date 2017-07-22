<?php

//second contiene algunas variables necesarias, iguales que en builders
//var_dump(scandir('../'));
//include_once'../mysqlidb.main.php';

class reserva_builder extends mysqlidb{

	public $now;
	public $date;
	public $tabla;
	public $base_name;


	//constructor
	public function __construct(){
		
		$this->now = date('Y-m-d H:i:s');
		$this->date = date('Y-m-d');
		$this->base_name = 'reservas';
		parent::__construct($this->base_name);
		
	}


	//buscara la ultima fecha existente en una tabla
	//y devolvera dia siguiente
	//=================================================
	private function buscar_ultima_existente($tabla){
		$this->orderBy('date','desc');
		if($last_reg_day = $this->getOne($tabla,'date')){
			//var_dump('hay fecha paratomar como referencia');
			$new_day = $this->periodo($last_reg_day['date'],1);
			//si el dia siguiente es anterior a hoy, return hoy 
			if($new_day < $this->date){
				return $this->date;
			}else{
				return $new_day;
			}	
		}else{
			//no hay fecha, añadir la de hoy
			return $this->date;
		}
	}

	//crea en $tabla la fecha indicada (reservando o no) y devuelve fecha
	protected function reservar_siguiente_dia($fecha, $tabla, $user, $reservar = NULL){
		//reservar siguiente dia
		if(strpos($tabla,'star')){		$campo ='e1';	}
		if(strpos($tabla,'special')){	$campo ='s1';	}
		if(strpos($tabla,'banners')){	$campo ='b1';	}

		if($reservar == true){
			//hay que reservar un campo para este user
			$cols = array('date' =>$fecha, $campo => $user);
		}else{
			//creamos la fecha pero no reservamos nada
			$cols = array('date' =>$fecha);
		}

		//insercamos fecha (con o sin resera)
		$this->insert($tabla, $cols);
			//die(var_dump($this));
			return $fecha;
		
	}

	protected function crear_siguiente_existente($tabla, $user, $reservar){
		$new_day = $this->buscar_ultima_existente($tabla);
		$nuevo_dia = $this->reservar_siguiente_dia($new_day, $tabla, $user, $reservar);
		return $nuevo_dia;
	} 

	//funcion que devolvera los campos correspondientes al tipo de tabla
	//==============================================================
	protected function campos_correspondientes($tabla){
		if(strpos($tabla,'star')){
			$campos = array('e1','e2','e3');
		}
		if(strpos($tabla,'special')){
			$campos = array('s1','s2','s3','s4','s5','s6','s7','s8');
		}
		if(strpos($tabla,'banners')){
			$campos = array('b1','b2','b3','b4','b5','b6','b7','b8');
		}
		return $campos;

	}


	//busca el primer campo que no sea user con fecha mas proxima
	//empleado en tienda y fechas
	protected function proximo_hueco($campo,$tabla,$user){
		$campos = $this->campos_correspondientes($tabla);

		if(!empty($campo)){

			$this->where($campo,array('not in' => array($user)));
			$this->where('full',0);
			$this->orderBy('date', 'asc');
			$salida = $this->getOne($tabla,'date');

			return $salida['date'];

		}else{
			//no hay campo descrito hay que especificarle todos
			foreach ($campos as $value) {
				$this->where($value,array('not in' => array($user)));
			}
			$this->where('full',0);
			$this->where('date',array('>=' => $this->date));
			$this->orderBy('date', 'asc');
			
			if($salida = $this->getOne($tabla)){
				return $salida['date'];
			}
			//$salida['date'] devolvera la primera fecha disponible para el

		}
	}//fin de proximo_hueco







}





?>