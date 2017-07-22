<?php  
//include_once 'inc/clases/builders.class.php';
//include_once'reserva/second.class.php';
//include_once 'seg/seg_sesion.class.php';

class Buscador extends builders{

	public $busq   = NULL; 		//array que contiene parametros de busqueda


	public function __construct(){
		parent::__construct();
		//parametors de busqueda para consulta
		//$this->all_extractor();
		$this->get_extractor();		
	}


	//crearemos lista blanca de paramentros validos
	//parametors de busqueda para consulta
	private function all_extractor(){
		//vienen por post (guardo en objeto y creo sessiones)
		if($_POST){
			$_SESSION['busqueda'] = array();
			foreach ($_POST as $key => $value) {
				$this->busq[$key] 	= $value;
			   	$_SESSION['busqueda'][$key] = $value;
			}
		//vienen por session (guardo)
		}else if( (!empty($_SESSION['busqueda'])) && (is_array($_SESSION['busqueda'])) ){
			foreach ($_SESSION['busqueda'] as $key => $value) {
				$this->busq[$key] = $value;
		}	}
	}
	
	private function get_extractor(){
		//var_dump($_GET);
		foreach ($_GET as $key => $value) {
			$this->busq[$key] = $value;
		}
	}

	//crivas en busquedas
	protected function crivar01(){
		$pro = 0;
		$mun = 0;
		if(isset($this->busq['provincia'])){$pro = $this->busq['provincia'];}
		if(isset($this->busq['municipio'])){$mun = $this->busq['municipio'];}		
		$return = array();
		//ni provincia ni municipio
		if( ($pro == 0) && ($mun == 0) ){
			//array seguira vacia
		} 
		//buscara provincia y guardo la ultima
		if( ($pro != 0) && ($mun == 0) ){
			$return[0] = 'provincia';
			$return[1] = $pro;
		} 
		//provincia si es nueva, municipios si es ma misma provincia
		if( ($pro != 0) && ($mun != 0) ){
			$return[0] = 'municipio';
			$return[1] = $mun;
		} 
		//todas las provincias
		if( ($pro == 0) && ($mun != 0) ){
			//array seguira vacia
		} 
		
		if(!empty($return)){
			$primera_parte = ' '.$return[0].'="'.$return[1].'" AND';
			return $primera_parte;
		}else{
			return '';
		}
	}



	protected function crivar02(){
	
		$tipo = 0;
		$subtipo = 0;
		if(isset($this->busq['tipo_inmueble'])){$tipo = $this->busq['tipo_inmueble'];}
		if(isset($this->busq['subtipo_inmueble'])){$subtipo = $this->busq['subtipo_inmueble'];}

		$return = array();
		
		if( ($tipo == 0) && ($subtipo == 0) ){
			//array seguira vacia
		}
		if( ($tipo != 0) && ($subtipo == 0) ){
			$return[0] = 'tipo_inmueble';
			$return[1] = $tipo;
		}
		if( ($tipo != 0) && ($subtipo != 0) ){
			$return[0] = 'subtipo_inmueble';
			$return[1] = $subtipo;
		}
		if( ($tipo == 0) && ($subtipo != 0) ){
			//array seguira vacia
		}
		
		if(!empty($return)){
			$segunda_parte = ' '.$return[0].'="'.$return[1].'" AND';
			return $segunda_parte;
		}else{
			return '';
		}
		
	}


	//sacara el tipo de operacion
	protected function tipo_operacion(){
		$return = '';
		if(isset($this->busq['operacion'])){
			$v = $this->busq['operacion'];
			$posibles = array('venta','alquiler');
			if(in_array($v, $posibles)){
				$return = ' tipo_venta="'.$v.'" AND';				
			}
		}
		return $return;
	}
	



	protected function busco_empresa(){
		$return = '';
		if( (isset($this->busq['empresa'])) && ($this->busq['empresa'] != '0') ){
			$e = $this->busq['empresa'];
				$return = ' nik_empresa="'.$e.'" AND';				
		}
		return $return;
	}






	//recibe un minimo y un maximo y devuelve un string de consulta valido
	//=============================================================
	protected function universal_min_max($que){

		$min = false;
		$max = false;
		if($que == 'precio'){
			//precio
			if(isset($this->busq['precio_min'])){$min = $this->busq['precio_min'];}
			if(isset($this->busq['precio_max'])){$max = $this->busq['precio_max'];}
		}else{
			//superficie
			if(isset($this->busq['superf_min'])){$min = $this->busq['superf_min'];}
			if(isset($this->busq['superf_max'])){$max = $this->busq['superf_max'];}			
		}



		if(!is_numeric($min)){$v_min = false;}else{$v_min = $min;}
		if(!is_numeric($max)){$v_max = false;}else{$v_max = $max;}

		if( ($v_min == false) && ($v_max == false) ){	//si los dos son false no se busca nada en precio
			$busqueda_v = '';
		}else if( ($v_min != false) && ($v_max != false) ){	//ninguna false se busca un between
			$busqueda_v = ' '.$que.' Between '.$v_min.' And '.$v_max.' AND';
		}else if ($v_min == false){		//no hay precio minimo buscamos cualquiera mas bajo del maximo
			$busqueda_v = ' '.$que.' <= '.$v_max.' AND';
		}else if ($v_max == false){		//no hay maximo, vale cualquiera mayor al minimo
			$busqueda_v = ' '.$que.' >= '.$v_min.' AND';
		}
		return $busqueda_v;
	}


	//filtros para el buscador
	protected function varios_filtros($filtro, $campo){
		$filtro_consulta = '';
		if(!empty($filtro)){
			if(count($filtro) > 1){
				$filtro_consulta .=" $campo in(";
				foreach($filtro as $key => $valor){
					$filtro_consulta .= "'$valor',";
				}	
					//le quito la coma del final
				$filtro_consulta = substr($filtro_consulta,0,-1);	
				$filtro_consulta .= ") AND";
			}else{
				$filtro_consulta =' '.$campo.'="'.$filtro[0].'" AND ';
			}			
		}

		

		return $filtro_consulta;
	}

	//devuelve minimo de rooms a buscar
	protected function min_rooms(){
		$rooms = 0;
		$busqueda_rooms = '';		

		if(isset($this->busq['rooms'])){$rooms = $this->busq['rooms'];}
		if( (is_numeric($rooms)) && ($rooms != 0) ){
			$busqueda_rooms = ' habitaciones >= '.$this->busq['rooms'].' AND';
		}
		return $busqueda_rooms;
	}

	//ordena por precio superficie fecha ASC DESC
	//=====================================================
	protected function ordenar_consulta(){

		$orderby = '';
		if(isset($this->busq['ordenar'])){
			$param = $this->busq['ordenar'];
			$orderby = 'ORDER BY';
			if($param == 1){		$orderby .= ' precio DESC';
			}else if($param == 2){	$orderby .= ' precio ASC';
			}else if($param == 3){	$orderby .= ' superficie DESC';
			}else if($param == 4){	$orderby .= ' superficie ASC';
			}else if($param == 5){	$orderby .= ' fecha_publicacion ASC';
			}else if($param == 6){	$orderby .= ' fecha_actualizacion DESC';
			}			
		}

		return $orderby;
	}


	//obtiene coincidencias para buscador (busqueda)
	public function getMatches($tabla, $campo, $que){
		
		$extirpo='id';
		
		if($tabla == 'anuncios'){
			$extirpo = $campo;
		}
		
		$this->where($campo, array('LIKE' => "%$que%"));
		$salida = $this->get($tabla);
		
		$municipios = '';
		foreach($salida as $value){
			$municipios .= $value[$extirpo].',';
		}
		
		$municipios = substr($municipios,0,-1);
		
		return $municipios;
	}

	protected function nueva_url_busqueda(){
		$filtrado = $this->busq;
		foreach ($this->busq as $key => $value) {
			//borro los 0
			if($value == '0'){unset($filtrado[$key]);}
			//borro min y max
			if($value == 'min'){unset($filtrado[$key]);}
			if($value == 'max'){unset($filtrado[$key]);}	
		}

		//elimino valores que estorban o no necesito 
		if(isset($filtrado['busqueda'])){unset($filtrado['busqueda']);}
		if(isset($filtrado['pagg'])){unset($filtrado['pagg']);}
		if(isset($filtrado['inmv'])){unset($filtrado['inmv']);}
		if(isset($filtrado['pagg_inmo'])){unset($filtrado['pagg_inmo']);}
		if(isset($filtrado['lang_form'])){unset($filtrado['lang_form']);}

		$nueva_url = 'index.php?busqueda=1';
		foreach ($filtrado as $key => $value) {
			$nueva_url .= '&'.$key.'='.$value;
		}

		return $nueva_url;
	}

}
?>