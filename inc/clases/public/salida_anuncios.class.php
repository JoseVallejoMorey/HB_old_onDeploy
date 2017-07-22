<?php

//salidas de anuncios para vista del usuario

class salida_anuncios extends Modulos{

	public $pagina = 1;
	public $mod    = 'mod1';
	public $url;

	public function __construct(){
		
		parent::__construct();
		//pagina (para paginacion si la necesita)
		if(!empty($_GET['pagg'])){  		 $this->pagina = $_GET['pagg'];				}
		if(!empty($_GET['pagg_inmo'])){  $this->pagina = $_GET['pagg_inmo'];	}
		if(isset($this->busq['mod'])){	 $this->mod = $this->busq['mod'];			}
		if(!is_null($this->busq)){$this->url = $this->nueva_url_busqueda();		}
	
	}

	//añade pestaña "promocionados" a los que son
	protected function propiety_promoted($array){
		$return = '';
		if($array['anuncio_promocionado'] == 1){
		$return = '<div class="property-promoted"><h5>Promocionado</h5></div>';
		}
		return $return;
	}


	//en ocasiones viene de un bucle while, usamos este
	public function formar_anuncios_paginados($class,$contador,$value){
		
			$return = '';
			$promo  = '';
			$this->anuncio_class = $class;
			$this->define_multiplos();
			if(in_array($contador, $this->anuncio_multiplos)){
				//mostrara banner
				$return .='<div class="bann6 vissible-lg">'.
				$this->show_banner('central').'</div>';
			}else{
				$return .= $this->crear_box_anuncio($value);
			}
		return $return;
	}

	//da forma a distintos formatos de anuncio mediante una misma funcion
	//===================================================================
	public function formar_anuncio($class,$valores,$multiplos = null){
		
		$contador 	= 0;
		$return 	= '';
		$promo 		= '';
		$this->anuncio_class = $class;
		$this->define_multiplos($multiplos);
		foreach ($valores as $key => $value) {
			$this->anuncio_contador = $contador++;
			if(in_array($contador, $this->anuncio_multiplos)){
				//si es multipo sacara un banner
				$return .='<div class="bann6 vissible-lg">'.
				$this->show_banner('central').'</div>';
			}else{
				$return .= $this->crear_box_anuncio($value);
			}
		}//foreach
	return $return;
	}
	            

	//creara una cajita con el anuncio, 
	protected function crear_box_anuncio($value,$tipo = null){

		$this->anuncio_class = 'mod1';

		$this->define_lang_info($value['id']);				//texto y desc en idioma indicado
		$this->define_imagen($value['id'],$value['ussr']);	//obtenemos imagen principal del anuncio
		$this->propiety_promoted($value);						//promocion o no del anuncio
		//var_dump($this);
		$subtip = $this->show_subtipo_inmueble($value['subtipo_inmueble']);
		$municipio = $this->show_municipio($value['municipio']);
		$titulo = '<h3><a href="#">'.$subtip.' en '.$municipio.'</a> <small>Miami</small></h3>';
		$texto  = '<p>'.substr($this->anuncio_idioma_data['descripcion'],0,100).'</p>';

		if($this->anuncio_class == 'mod1'){
			$cuadro    = 'mod1 col-md-4 col-sm-6 col-xs-12';	//3 por fila 
			$interior  = '<div class="property-image">';
			$interior .= $this->anuncio_imagen;
			
		    $interior .= '<div class="property-status"><h5>'.$this->propiety_status($value).'</h5></div>';
		    $interior .= $this->propiety_promoted($value);
		    $interior .= '</div>';		            
		    $interior .= $this->propiety_features($value);          
		    $interior .='<div class="property-content">';		           
			$interior .= $titulo;
			//$interior .= '<div class="row">';
			// $interior .= '<div class="property-text">';
			// $interior .= $texto;			
			// $interior .= '</div>';

			$interior .= '<div class="mod-logo">'.$this->propiety_logo($value['ussr']).'</div>';	
		    $interior .= $this->propiety_price($value['precio'],$value['tipo_venta']);
		    $interior .='</div>';	
		    //$interior .= '</div>';	          
		}else{
			$cuadro    = 'mod2 col-md-6 col-sm-12 col-xs-12';	//2 por fila 
			$interior  = '<div class="property-content-list">
		                 <div class="property-image-list">';
		    $interior .= $this->anuncio_imagen;
		    $interior .= '<div class="property-status"><h5>'.$this->propiety_status($value).'</h5></div>';
		    $interior .= $this->propiety_promoted($value);
		    $interior .= $this->propiety_price($value['precio']);
		    $interior .= '</div>';
		    $interior .= '<div class="property-text">';
			$interior .= $titulo;         
			//$interior .= $texto; 
			$interior .= '<div class="mod-logo">'.$this->propiety_logo($value['ussr']).'</div>';
		    $interior .= '</div>';
		    $interior .= '</div>';
		    $interior .= $this->propiety_features($value);             
		}

		$salida = '<div class="'.$cuadro.'">';
		$salida .= '<div class="property-container">';
		$salida .= $interior;
		$salida .= '</div>';
		$salida .= '</div>';

		return $salida;
	}


	//montara una consulta para sacar una paginacion deseada
	public function crear_consulta_busqueda(){

		//si hay datos crearemos una consulta funcional
		if(is_array($this->busq)){

			$params = '';
			$valores = array();
			$orderby = '';

			//operacion, provincia-municipio, tipo-subtipo
			$params .= $this->tipo_operacion();
			$params .= $this->crivar01();
			$params .= $this->crivar02();
			//se definen variables de precio, superficie y habitaciones
			$params .= $this->universal_min_max('precio');
			$params .= $this->universal_min_max('superficie');
			$params .= $this->min_rooms();

			//empresa, extras y orden
			$params .= $this->busco_empresa();
			$orderby = $this->ordenar_consulta();

			if(isset($this->busq['filtro2'])){
				$params .= $this->varios_filtros($this->busq['filtro2'],'extras');
			}	
			
			$con ='SELECT * FROM anuncios WHERE '.$params .
			' activo = 1 AND apto = 1 '.$orderby;		
			
		}
		//var_dump($con);	
		return $con;
	}



	//mostrara la paginacion de anuncios (hermana de paginacion empresas)
	//conexion, pagina, objeto Perfil, objeto Ress
	//===============================================================================
	public function paginacion_anuncios(){

		$return = '<div class="row container-property">';
		//si no le pasamos conexion se monta una
		//link paginacion dependiendo de seccion (inmobiliaria o busqueda)
		// if(is_null($Empresa)){
			$link_pag = $this->url.'&pagg=*VAR*';
			$conexion = $this->crear_consulta_busqueda();
		// }else{
		// 	$this->empresa = $Empresa->user;
		// 	$link_pag = $this->url.'&inmv='.$Empresa->salida['nik_empresa'].'&pagg_inmo=*VAR*';
		// 	$conexion = $this->crear_consulta_busqueda($Empresa);
		// }

		$this->publicar_mysqli();
		$options = array(  
			'url'        	=> $link_pag,  
			'db_handle'  	=> $this->public_mysqli,
			'results_per_page' => ANUNCIOS_POR_PAGINA,
			'db_conn_type'  => 'mysqli');
		$Pag = new pagination($this->pagina, $conexion, $options);

		if($Pag->success == true){  

			 $i=0;
			 while($salida = $Pag->resultset->fetch_assoc()){
			 	$i++;
			 	$return .= $this->formar_anuncios_paginados($this->mod,$i,$salida);
			 }		
			 $return .= '</div>';
			if($Pag->total_pages <1){	$return .= $this->no_results($conexion); }
			if($Pag->total_pages >1){	$return .= $Pag->links_html; 	}
		}else{
			$return .= '</div>'; //cerramos igualmente
			//var_dump('es false');
		}

		return $return;
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

//mostraremos anuncios con mis criterios elegidos, para index
//===========================================================
	public function show_index_results(){

		$return = '<div class="row container-property">';

		//requisitos anuncio apto y activo
		$this->where('activo','1');
		$this->where('apto','1');
		$this->orderBy('anuncio_promocionado','DESC');

		if($salida = $this->get('anuncios',6)){
			foreach ($salida as $value) {
				$return .= $this->crear_box_anuncio($value);
			}
		}else{
			$return .= 'no hay resultados';		
		}
		$return .= '</div>';
		return $return;
	}









	//extrae informacion interesante de una consulta fallida
	//=============================================
	private function destripar_consulta($consulta){
		//var_dump($consulta);
		$params = array();
		$return = array();
		$cachos = explode(' ',$consulta);
		foreach ($cachos as $key => $value) {
			if(strpos($value,' = ')){ array_push($params, $value);}
		}
		//var_dump($params);
		foreach ($params as $key => $value) {
			$cachos = explode('=',$value);
			$return[$cachos[0]] = str_replace('"', '', $cachos[1]); 

		}
		// var_dump($return);
		return $return;
	}

	//si no hay refultados sacampos cualquier cosa
	//===============================================
	public function no_results($consulta = NULL){
		$return = '<h1>No se encontraron sefultados, estas son otras ofertas</h1>';

		$this->where('apto',1);
		$this->where('activo',1);
		
		if(!is_null($consulta)){
			$parametros = $this->destripar_consulta($consulta);
			//var_dump($parametros);

			foreach ($parametros as $key => $value) {
				$this->where($key,$value);
				$this->where($key,array('in' => array($value) ) );

				//$db->where('id', array( 'in' => array(1, 5, 27, -1, 'd') ) );
			}
			
			//var_dump($this);
			//var_dump('pasa por canarias');
		}
		$this->orderBy('apto','DESC');
		$salida = $this->get('anuncios',20);
		$return .= $this->formar_anuncio('mod1',$salida);
		
		return $return;


	}
	



}

?>