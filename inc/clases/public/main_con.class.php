<?php

require_once'inc/clases/public/modulos.class.php';

class main_con extends Modulos{

	private $pregunta = array();
	public $permitidos;


	public function __construct(){
		parent::__construct();
		$this->permitidos = array('venta', 'alquiler', 'comercial', 'inmobiliarias', 'footer');
	}

//mostrara un menu con links permitidos segun seccion
//===================================================
public function mostrar_mn_mav($Config){
	$return = '';
	if(!empty($_GET['mn_nav'])){	

		//mostrando menus de links disponibles
		$return .= '<ul id="segun_nav" class="nav nav-tabs no_border">';
		//buscara en db cuales hay con su nombre
		$Config->where('seccion',$_GET['mn_nav']);
		if($quecosa = $Config->get('links_main')){
			foreach ($quecosa as $key => $value) {
				$link = 'index.php?mn_nav='.$_GET['mn_nav'];
				$link .= '&'.$value['primer_parametro'].'='.$value['primer_valor'];
				if($value['segundo_parametro'] != ''){
					$link .= '&'.$value['segundo_parametro'].'='.$value['segundo_valor'];
				}
				$return .= '<li><a href="'.$link.'">'.$value['titulo'].'</a></li>';
			}
		}
		$return .= '</ul>';

	return $return;	
	}
}

//si entra desde un link disponible aqui se agregan al array $this->pregunta
//======================================================
// private function captando_preguntas(){

// 	if(!empty($_GET['operacion'])){}
// 	if(!empty($_GET['estado'])){}

// 	//resuelve poblacion o zona
// 	if(!empty($_GET['poblacion'])){
// 		$p = str_replace('-', ' ', $_GET['poblacion']);
// 		$this->where('municipio',$p);
// 		if($s = $this->getOne('municipios','id')){
// 			$this->pregunta['municipio'] = $s['id']; 
// 		}else{
// 			$this->where('zona',$p);
// 			if($z = $this->getOne('zonas','id')){
// 			$this->pregunta['provincia'] = $z['id']; 
// 	}	}	}

// 	//resuelve subtipos (incluyendo idiomas)
// 	if(!empty($_GET['subtipo'])){
// 		$sb = str_replace('-', ' ', $_GET['subtipo']);
// 		$this->where($_SESSION['lg'],$sb);
// 		if($s = $this->getOne('subtipo_inmueble','id')){
// 			$this->pregunta['subtipo_inmueble'] = $s['id']; 
// 	}	}

// 	//resuelve extras (incluyendo idiomas)
// 	if(!empty($_GET['extra'])){
// 		$x = str_replace('-', ' ', $_GET['extra']);
// 		$this->where($_SESSION['lg'],$x);
// 		if($s = $this->getOne('extras','id')){
// 			$this->pregunta['extras'] = $s['id']; 
// 	}	}


// }


	// //mostrara la consulta realizada para salida general
	// public function mostrando_resultados($mod){

	// 	//$return = '<div id="salida_anuncios" class="row">';
	// 	$return = '';
	// 	//preguntas provenientes de links creados
	// 	$this->captando_preguntas();
	// 	foreach ($this->pregunta as $key => $value) {
	// 		$this->where($key, array('LIKE' => "%$value%"));
	// 	}

	// 	//seccion si hay
	// 	if(!is_null($this->seccion)){
	// 		if($this->seccion == 'comercial'){	$this->where('tipo_inmueble',2);
	// 		}else{								$this->where('tipo_venta',$this->seccion);	}
	// 	}

	// 	//requisitos anuncio apto y activo
	// 	$this->where('activo','1');
	// 	$this->where('apto','1');
	// 	$this->orderBy('apto','DESC');

	// 	if($salida = $this->get('anuncios',20)){
	// 		//var_dump($this->_lastQuery);
	// 		$return .= $this->formar_anuncio($mod,$salida);
	// 	}else{
	// 		$return .= 'no hay resultados';		
	// 	}
		
	// 	//$return .= '</div>';//row
	// 	return $return;
	// }



}


?>