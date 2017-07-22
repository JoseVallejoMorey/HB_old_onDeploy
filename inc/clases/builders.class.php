<?php


//include_once'mysqlidb.main.php';

class builders extends MysqliDb{
	
	public $link;
	public $user;
	public $empresa;
	public $last_id;
	public $now;
	public $date;
	public $base_name;
	
	//objetos
	public $Perfil;


	//constructor
	public function __construct(){
		//var_dump('I am builder');
		$this->now = date('Y-m-d H:i:s');
		$this->date = date('Y-m-d');
		$this->base_name = 'anuncios_db';	
		
		if(!empty($_SESSION['user_id'])){	$this->user = $_SESSION['user_id'];			}
		if(!empty($_SESSION['tipo'])){		$this->tipo_usuario = $_SESSION['tipo'];	}

		parent::__construct($this->base_name);
		//objetos
		$this->Perfil = new seg_builder();
	}
	
	

	//saca las imagenes de ese anuncio, false si no hay
	public function get_img($id_de_anuncio){
		$this->where('id_e',$id_de_anuncio);
		$this->orderBy('principal','Desc');
		if(!$imagenes = $this->get('anuncios_img')){
			return false;
		}else{
			//devolvemos array de imagenes
			return $imagenes;
		}
	}


	



	//convierte numero en provincia
	//=====================================================
	public function getPro($pro = NULL){
		$return = '';
		$cols = array('id' , 'zona');
		$salida = $this->get('zonas', NULL, $cols);
		foreach($salida as $value){
			if($value['id'] == $pro){	$check = 'selected="selected"';
		}else{							$check = '';					}
			$return .= '<option value="'.$value['id'].'" '.$check.'>'.ucfirst($value['zona']).'</option>';
		}
		return $return;
	}

	//convierte numero en provincia
	//=====================================================
	public function get_tipo($tipo = NULL){
		if(!empty($_SESSION['lg'])){	$lang = $_SESSION['lg'];
		}else{							$lang = 'esp';				}
		$return = '';
		$cols = array('id' , $lang);
		$salida = $this->get('tipo_inmueble', NULL, $cols);
		foreach($salida as $value){
			if($value['id'] == $tipo){	$check = 'selected="selected"';
		}else{							$check = '';					}
			$return .= '<option value="'.$value['id'].'" '.$check.'>'.ucfirst($value[$lang]).'</option>';
		}
		return $return;
	}



	//devuelve provincia mediante su id
	public function show_provincia($id){
		$this->where('id',$id);
		$this->salida = $this->getOne('zonas','zona');
		
		return $this->salida['zona'];
	}	
	
	//devuelve municipio mediante su id
	public function show_municipio($id){
		$this->where('id',$id);
		$mu = $this->getOne('municipios','municipio');
		
		return $mu['municipio'];
	}

	//devuelve tipo_inmueble mediante su id
	public function show_tipo_inmueble($id){
		if(!empty($_SESSION['lg'])){	$lang = $_SESSION['lg'];
		}else{							$lang = 'esp';				}
		$this->where('id',$id);
		$tipo = $this->getOne('tipo_inmueble',$lang);
		
		return $tipo[$lang];
	}

	//devuelve subtipo_inmueble mediante su id
	public function show_subtipo_inmueble($id){
		if(!empty($_SESSION['lg'])){	$lang = $_SESSION['lg'];
		}else{							$lang = 'esp';				}
		$this->where('id',$id);
		$sub = $this->getOne('subtipo_inmueble',$lang);
		
		return $sub[$lang];
	}




	//extraigamos extras
	public function extraigamos_extras($extras){

		$return = '';
		//$campos = array('id',$lang);

		if(!empty($_SESSION['lg'])){	$lang = $_SESSION['lg'];
		}else{							$lang = 'esp';				}

		if(is_array($extras)){
			$this->where('id',array('in' => $extras));
		}else{
			$this->where('id',$extras);
		}


		if($s = $this->get('extras',NULL,$lang)){
			foreach ($s as $key => $v) {
				$return .= '<div class="btn btn-info btn-extras">'.$v[$lang].'</div>';
			}

		}

		return $return;
	}








	//boton para subscribirse a alertas
	//===================================================
	public function btn_crear_alerta ($tipo){
		$tamanno = '';
		if($tipo == 'big'){
			$tamanno = 'alerta_big';
			//clase big e imagen
		}
		if($tipo == 'normal'){
			$tamanno = 'alerta_medium';
			//clase normal e imagen	
		}
		include 'scripts/modals/modal_form_alerta.php';
		return '<button class="btn btn-primary btn-lg '.$tamanno.'" data-toggle="modal" data-target="#modal-alert">

					<span>crear alerta</span>
				</button>';
				//<img src="assets/img/bell.jpg" />			
	} 			

				
	//boton para contacto con vendedor
	//===================================================
	public function btn_contacto (){
		$Form = new form_builder();
		//include 'scripts/modals/modal_form_contacta.php';	
		echo modal_contacto($Form);
		return '<button class="btn btn-warning btn-lg" data-toggle="modal" data-target="#modal-contacta">
	            	contacta con el anunciante
	         	</button>';
			
	}
	
	//boton para subscribirse a alertas
	//===================================================
	public function btn_subscripcion (){
		var_dump('antorcha');
		$Form = new form_builder();

		//$saltador = $Perfil->select_salt($this->user);
		//include 'scripts/modals/modal_form_subscripcion.php';	
		echo modal_subscripcion($Form);
		return '<button class="btn btn-warning btn-lg" data-toggle="modal" data-target="#modal-subscribe">
	            	Subscribirse a boletin
	         	</button>';
			
	}

	//campo check de aceptacion de politica de privacidad
	public function privatepol(){
		return '<div class="check-friend">
					<input type="checkbox" name="privatepol" value="true" />
		        		<p>He leido y acepto la 
		        		<a href="index.php?archivo=legal" target="_blank">politica de privacidad</a>
		        		</p>
	            </div>';
	}

	//check de politica de privacidad
	public function termandcon(){
		return '<div class="check-friend">
					<input type="checkbox" name="termandcon" value="true" />
		        		<p>He leido y acepto la 
		        		<a href="index.php?archivo=condiciones" target="_blank">Terminos y Condiciones</a>
		        		</p>
	            </div>';
	}

	//sacar imagen preferida de un anuncio
	public function sacar_foto($id_e, $ussr){

		$src_imagen ='';
		//sacara imagen desderoot o desde ajax
		if(!file_exists("imagenes/anuncios_img/".$ussr.'/small')){
			if(!file_exists("../../imagenes/anuncios_img/".$ussr.'/small')){
				$ussr = 'par';	
			}
		}

		$this->where('id_e', $id_e);
		$this->orderBy('principal','desc');
		$this->salida = $this->get('anuncios_img',1);
		
		foreach($this->salida as $value){
			$src_imagen .= '<a href="index.php?pag='.$id_e.'">';
			$src_imagen .='<img src="imagenes/anuncios_img/'.$ussr.'/small/small_'.$value['img'].'"
								title="'.$value['titulo'].'" alt="'.$value['descripcion'].'"/></a>';
		}

		return $src_imagen;
	}

	public function sacar_solo_foto($id_e, $ussr){
		$src_imagen ='';
		//sacara imagen desderoot o desde ajax
		if(!file_exists("imagenes/anuncios_img/".$ussr.'/small')){
			if(!file_exists("../../imagenes/anuncios_img/".$ussr.'/small')){
				$ussr = 'par';	
			}
		}

		$this->where('id_e', $id_e);
		$this->orderBy('principal','desc');
		$this->salida = $this->get('anuncios_img',1);
		
		foreach($this->salida as $value){
			$src_imagen .='imagenes/anuncios_img/'.$ussr.'/small/small_'.$value['img'];
		}
		return $src_imagen;		
	}



	//saca varias imagenes relacionadas a un anuncio
	//==============================================
	public function sacar_varias_fotos($id_e, $ussr, $numero){
		$src_imagen ='';
		
		$this->where('id_e', $id_e);
		$this->orderBy('principal','desc');
		$this->salida = $this->get('anuncios_img',$numero);
		
		//significara que es para star_area
		if($numero == 3){$src_imagen .='<ul>';}
		foreach($this->salida as $value){
			if($numero == 3){$src_imagen .='<li><a href="'.$this->link.'">';}
			$src_imagen .= '<img src="imagenes/anuncios_img/'.$ussr.'/small/small_'.$value['img'].'"
					title="'.$value['titulo'].'" alt="'.$value['descripcion'].'"/>';
			if($numero == 3){$src_imagen .='</a></li>';}		
		}
		if($numero == 3){$src_imagen .='</ul>';}		
					
		return $src_imagen;


	}




	//por cada anuncio que crea agrega un campo en enigma con las ids
	//====================================================					
	public function crear_enigma($id){
				
		$md5id = md5($id);
		$data = array('id_anuncio' => $id,'id_enc' => $md5id);
		$this->insert('enigma', $data);
	
	}

	//desvelar el id verdadero
	//====================================================	
	public function resolver_enigma($id_e){
	
		$this->where('id_enc',$id_e);
		if($this->salida = $this->getOne('enigma','id_anuncio')){
			return $this->salida['id_anuncio'];
		}else{
			return false;
		}
	
	}
	


	// //quita guiones y separaciones y convierte la fecha en un array
	// //================================================================
	// public function fecha_db($date){
	// 	$date = str_replace('-','/',$date);
	// 	$date = str_replace(':','/',$date);
	// 	$date = str_replace(' ','/',$date);
	// 	$date = explode('/',$date);
	// 		return $date;
	// }
	


	public function ordenar_resultados(){
		return '<div id="ordenador-cont" class="hidden-xs">

					<div id="mod_selector" class="btn-group" data-toggle="buttons">
					  <label class="btn btn-warning active">
					    <input type="radio" name="options" id="mod1" autocomplete="off" checked> mod1
					  </label>
					  <label class="btn btn-warning">
					    <input type="radio" name="options" id="mod2" autocomplete="off"> mod2
					  </label>
					  <label class="btn btn-warning">
					    <input type="radio" name="options" id="mod3" autocomplete="off"> mod3
					  </label>
					</div>

					<div class="orderby">		
					<span>'.ORDENAR.'</span>
						<select name="ordenar">
							<option value="1">'.PMAYORMENOR.'</option>
							<option value="2">'.PMENORMAYOR.'</option>
							<option value="3">'.SMAYORMENOR.'</option>
							<option value="4">'.SMENORMAYOR.'</option>
							<option value="5">'.MASNUEVO.'</option>
							<option value="6">'.MASANTIGUO.'</option>
						</select>
					</div>
				</div>';
	}


}//fin de builders



?>