<?php

include_once 'buscador.class.php';
//require_once 'inc/clases/procesos/reserva_builder.class.php';
//para iniciar el objeto reservas

//include_once'reserva/second.class.php';
//include_once 'seg/seg_sesion.class.php';

class Modulos extends buscador{
	
	public $lang; 						//idioma elegido
	public $salida;
	public $seccion = NULL;
	public $provincia;
	public $banner;

	private $anuncio_class;				//clase con la que debe mostrarse el anuncio
	private $anuncio_md5;				//anuncio en md5
	private $anuncio_contador;			//numero de anuncio en esa serie
	protected $anuncio_idioma_data;		//contiene titulo desc en idioma deseado
	protected $anuncio_multiplos;
	protected $anuncio_imagen;

	//otro objeto
	public $Ress;
	


	public function __construct(){
		if(!empty($_SESSION['lg'])){	$this->lang = $_SESSION['lg'];
		}else{							$this->lang = 'esp';				}
		
		parent::__construct();
		$this->section_ubicator();
		//si hay provincia
		if(isset($this->busq['provincia'])){	
			$this->provincia = $this->busq['provincia'];	
		}
		$this->Ress = new reserva_builder();
	}

	//se ubicara a si mismo en una seccion (para banners y special)
	private function section_ubicator(){
		$validos = array('venta','alquiler','comercial');
		if(!empty($_GET['mn_nav'])){
			if(in_array($_GET['mn_nav'], $validos)){
				$this->seccion = $_GET['mn_nav'];
		}	}
	} 





	//devuelve un usuario elegido entre varios candidatos
	//==========================================================
	private function candidatoElecto($array){
		if(is_array($array)){
			foreach($array as $key => $value){
				if($value == 0){	unset($array[$key]); }
			}	
			$candidatos = array_unique($array);
			$finalista = array_rand($candidatos);
			if(!is_null($finalista)){
				$electo = $candidatos[$finalista];
				return $electo;
			}			
		}
		return false;
	}


	//formar anuncios
	protected function define_multiplos($array = null){
		if(is_null($array)){
			if($this->anuncio_class == 'mod2'){			$mm = array('3','6','9');
			}else if($this->anuncio_class == 'mod3'){	$mm = array('4','8','12');
			}else{										$mm = array('4','8','12');	}
			$this->anuncio_multiplos = $mm;
		}else{
			$this->anuncio_multiplos = $array;
		}

	}

	//formar anuncios
	protected function define_imagen($id,$user){
		$this->anuncio_md5 = md5($id);
		$this->anuncio_imagen = $this->sacar_foto($this->anuncio_md5, $user);
	}

	//formar anuncios
	protected function define_lang_info($id){
		$this->where('anuncio',$id);
		$this->where('idioma',$this->lang);
		$this->anuncio_idioma_data = $this->getOne('anuncios_idiomas');
	}



	//sacar banner
	//=========================================================
	public function show_banner($tipo_banner){

		$this->Ress->where('date',$this->date);
		$tabla = 'reserva_banners_'.$tipo_banner;
		$cols = array('b1','b2','b3','b4','b5','b6','b7','b8');
		if(!$salida = $this->Ress->getOne($tabla, $cols)){
			//no hay banner hoy
			$return ='no hay baner hoy';
		}else{

			$electo = $this->candidatoElecto($salida);

			$this->Ress->where('user',$electo);
			$salida = $this->Ress->getOne('banners_catalogo', $tipo_banner);
			$return = '<img src="imagenes/banners/'.$electo.'/'.$salida[$tipo_banner].'" />';
			
		}

		return $return;
	
	}
	


	//esta solo se ocupa de seleccionar
	//===============================================
	private function selectSpecial(){

		$todos = array();
		$cols  = array('s1','s2','s3','s4','s5','s6','s7','s8');

		if(!is_null($this->seccion)){
			$tabla = 'reserva_special_'.$this->seccion;
			$this->Ress->where('date',$this->date);
			$todos = $this->Ress->getOne($tabla,$cols);
		}else{
			//si no tiene tabla donde buscar, sacara de todas (lol)	
			$this->Ress->where('date',$this->date);
			if($salida1 = $this->Ress->getOne('reserva_special_venta', $cols)){
				foreach ($salida1 as $key => $value) {	array_push($todos, $value);	}
			}
			$this->Ress->where('date',$this->date);
			if($salida2 = $this->Ress->getOne('reserva_special_alquiler', $cols)){
				foreach ($salida2 as $key => $value) {	array_push($todos, $value);	}
			}
			$this->Ress->where('date',$this->date);
			if($salida3 = $this->Ress->getOne('reserva_special_comercial', $cols)){
				foreach ($salida3 as $key => $value) {	array_push($todos, $value);	}
			}
		}

		$electo = $this->candidatoElecto($todos);
		if($this->candidatoElecto($todos) != false){
			return $electo;
		}
		return false;

	}
	

	//contando con selectSpecial monta y muestra special area
	//======================================================
	public function showSpecial(){

		$electo = $this->selectSpecial();
		if($electo != false){
			//datos empresa
			$this->Perfil->where('id',$this->user);
			$salida = $this->Perfil->getOne('perfiles_emp','empresa');
			foreach($salida as $value){
				$this->empresa = $value;
			}
			
			$sp_area ='<div id="special-area" class="col-md-12">
	                    <div class="star-title">
	                      <h4 class="">
							<a href="index.php?inmv='.$this->empresa.'">
							  propiedades de '.$this->empresa.'
							</a>
						  </h4>
	                    </div>';	
	        $sp_area .= '<div class="row">';            	
			//datos anuncios
	        $cols = array('id','municipio','subtipo_inmueble','tipo_venta','precio','ussr',
	        			  'superficie','habitaciones','banos');
			$this->where('ussr',$electo);
			if($salida = $this->get('anuncios',4,$cols)){
				//var_dump($salida);
				foreach($salida as $value){
					$id_md   = md5($value['id']);
					$link    = 'index.php?pag='.$id_md;					
					$titulo  = $this->show_subtipo_inmueble($value['subtipo_inmueble']).' en ';
					$titulo .= $this->show_municipio($value['municipio']);
					$this->define_imagen($value['id'],$value['ussr']);	//imagen

					if($value['tipo_venta'] == 'venta'){$operacion = 'En venta';
					}else{								$operacion = 'En alquiler';	}


			$sp_area .= '<div class="post-container col-sm-3 col-xs-12">';
			$sp_area .= '<div class="row">'; 	
		
			$sp_area .= '<div class="property-image">';
			$sp_area .= $this->anuncio_imagen;

		    $sp_area .= '<div class="property-status"><h5>'.$this->propiety_status($value).'</h5></div>';
		    $sp_area .= '</div>';	
			$sp_area .= '<div class="post-content col-xs-12">';	
			$sp_area .= '<div class="heading-title">';	
			$sp_area .= '<h2><a'.$link.'>'.$this->show_municipio($value['municipio']).' - <span>'.$value['precio'].'</span></a></h2>';	
			$sp_area .= '</div>';	
			$sp_area .= '<div class="post-meta col-xs-12">';	
			$sp_area .= '<span><i class="fa fa-home"></i> '.$value['superficie'].' m<sup>2</sup> / </span>';	
			$sp_area .= '<span><i class="fa fa-hdd-o"></i> '.$value['habitaciones'].' Habitaciones / </span>';
			$sp_area .= '<br/>';
			$sp_area .= '<span><i class="fa fa-male"></i> '.$value['banos'].' Baños / </span>';	
			$sp_area .= '</div>';
			$sp_area .= '</div>';

			$sp_area .= '</div>';
			$sp_area .= '</div>';
				 
				}
			$sp_area .= '</div>';	

			}

			$sp_area .='
					<div class="special-footer hidden-sm">
						<a href="index.php?inmv='.$this->empresa.'">ver todas las propiedades de '.$this->empresa.'</a>
					</div>
				 </div>';
			
			return $sp_area;
		}else{
			//var_dump('no hay nadie');
			return false;
		}

	}//fin metodo mostrar special area



	private function selectStar(){

		$todos = array();
		$cols  = array('e1','e2','e3');
		$posibles = array('1','2','3','4','5','6','7','8','9','10');

		
		if(($this->provincia !='') && (in_array($this->provincia, $posibles)) ){	
			//provincia debe corresponder con una tabla existente
			$tabla = 'reserva_star_'.$this->provincia;
			$this->Ress->where('date',$this->date);
			$todos = $this->Ress->getOne($tabla,$cols);			
		}else{
			//sino buscare los star mas caros y tirare uno
			$this->where('anuncio_e',1);
			$this->orderBy('precio','desc');
			$salida = $this->get('anuncios', 20,'id');
			foreach ($salida as $key => $value) {
				foreach ($value as $key => $value2) {
					array_push($todos, $value2);
				}
			}
		}

		$electo = $this->candidatoElecto($todos);
		if($electo != false){
			return $electo;
		}

		return false;
	}


	//muestra un anuncio estrella (contiene selectStar)
	//===================================================
	public function showStar(){
		$star = '';
		$electo = $this->selectStar();
		if($electo != FALSE){
		
			$md5nuncio = md5($electo);
			$this->link = 'index.php?pag='.$md5nuncio;
			$campos = array('id', 'ussr', 'provincia', 'municipio', 'tipo_venta', 'subtipo_inmueble',
				'anuncio_promocionado','precio','habitaciones','banos','superficie');
			$this->where('id',$electo);
			$salida = $this->getOne('anuncios', $campos);

			$this->define_lang_info($salida['id']);				//texto y desc en idioma indicado
			$this->define_imagen($salida['id'],$salida['ussr']);	//imagen principal del anuncio	
			
			$municipio = $this->show_municipio($salida['municipio']);
			$subtip = $this->show_subtipo_inmueble($salida['subtipo_inmueble']);
			$titulo = $subtip.' en '.$municipio;
			$cabeza = '<h3><a href="'.$this->link.'">'.$subtip.' en '.$municipio.'</a></h3>';
			$texto  = '<p>'.substr($this->anuncio_idioma_data['descripcion'],0,140).'</p>';

			$star .= '<div id="star_area">';
			$star .= '<div class="property-container">';
			$star .= '<div class="star-title">';
			$star .= $titulo;  
			$star .= '</div>';
			$star .= '<div class="property-content-list">';
			$star .= '<div class="row">';
	    $star .= '<div class="property-image-list col-sm-12 col-xs-12">';
	    $star .= $this->anuncio_imagen;
	    $star .= '<div class="property-status"><h5>'.$this->propiety_status($salida).'</h5></div>';		    
	    $star .= $this->propiety_price($salida['precio']);
	    $star .= '</div>';
	    $star .= '<div class="property-text col-sm-6 col-xs-6">';
			$star .= $cabeza;       
			$star .= $texto; 
		    $star .= '</div>';
		    //$star .= '<div class="">';
			$star .= '<div class="mod-logo col-sm-6 col-xs-6">'.$this->propiety_logo($salida['ussr']).'</div>';	
		    //$star .= '</div>';

		    $star .= '</div>';

		    $star .= '</div>'; //row
		    $star .= $this->propiety_features($salida);   		    
			$star .= '</div>';
			$star .= '</div>';

		}
		return $star;
	
	}//fin de showStar



	
	
	//columna con anuncios del mismo propietario (o no)
	public function anuncios_col($user = NULL, $arr){

		//preparacion
		if(!(is_null($user))){
			$this->where('ussr',$user);	
		}
		if(is_array($arr)){	
			foreach($arr as $key =>$value){
				$this->where($key,$value);
			}	
		}
		$this->where('apto',1);
		$cols = array('id', 'municipio','subtipo_inmueble','tipo_venta', 'precio', 'ussr','superficie',
			'habitaciones','banos');
		$salida = $this->get('anuncios',7 ,$cols);
		
		//html

		$return  = '<div id="similar-list" class="">';
			
		foreach($salida as $value){

			$operacion = $this->propiety_status($value);
			$id_md   = md5($value['id']);
			$link 	 = ' href="index.php?pag='.$id_md.'"';
			$src_imagen = $this->sacar_solo_foto($id_md, $value['ussr']);
									
			$return .= '<div class="post-container">';	
			$return .= '<div class="post-img" style="background: url('.$src_imagen.');"><h3>'.$operacion.'</h3></div>';	
			$return .= '<div class="post-content">';	
			$return .= '<div class="heading-title">';	
			$return .= '<h2><a'.$link.'>'.$this->show_municipio($value['municipio']).' - <span>'.$value['precio'].'</span></a></h2>';	
			$return .= '</div>';	
			$return .= '<div class="post-meta">';	
			$return .= '<span><i class="fa fa-home"></i> '.$value['superficie'].' m<sup>2</sup> / </span>';	
			$return .= '<span><i class="fa fa-hdd-o"></i> '.$value['habitaciones'].' Habitaciones / </span>';
			$return .= '<br/>';
			$return .= '<span><i class="fa fa-male"></i> '.$value['banos'].' Baños / </span>';	
			//$return .= '<span><i class="fa fa-building-o"></i> 2 Floors </span>';	
			$return .= '</div>';
			$return .= '</div>';
			$return .= '</div>';
		}
		
		$return .= '</div>';

		return $return;      
              
	}



	//muestra el logo para anuncios
	protected function propiety_logo($user){
		//$return ='<div class="mod_logo">'..'</div>';
		$return = $this->Perfil->sacar_logo($user);
		return $return;
	}



	//propiedades del anuncio
	protected function propiety_features($array){
		$return = '<div class="property-features">
				    <span><i class="fa fa-home"></i> '.$array['superficie'].' m<sup>2</sup></span>
				    <span><i class="fa fa-hdd-o"></i> '.$array['habitaciones'].' Habitaciones</span>
				    <span><i class="fa fa-male"></i> '.$array['banos'].' Baños</span>
				    <span><i class="fa fa-building-o"></i> 2 Floors</span>
				    </div>';
		return $return;		    
	}
	//precio del anuncio
	public function propiety_price($p,$o = null){
		$small = '';
		if(!is_null($o)){
			if($o == 'alquiler'){$small = '<small>Al mes</small>';} 
			$precio = '<h2>'.$p.$small.' </h2>';
		}else{
			$precio = '<h5>'.$p.'</h5>';
		}		
		$return = '<div class="property-price">'.$precio.'</div>';
		return $return;

	}
	//tipo de venta o alquiler
	public function propiety_status($array){
		if($array['tipo_venta'] == 'venta'){
			$operacion = 'En venta';
		}else{
			$operacion = 'En alquiler';
		}
	    return $operacion;
	}





		



}//fin de la clase



?>