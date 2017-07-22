<?php

//clase que contiene metodos empleados unicamente en pagina propia de empresa


class Empresa extends seg_builder{

	//public $nik_empresa;
	public $mod;
	public $pagina = 1;
	public $salida = array();

	public function __construct(){

		parent::__construct();
		//if(!empty($_POST['empresa'])){$param = $_POST['empresa'];}
		$this->obtengo_mod();		//cuadros de anuncios
		$this->obtengo_pagina();	//paginacion
		$this->desmenuzo(); 	//obtiene empresa si hay
	}


	//si hay sera post de ajax, sino lo coje el de GET
	//================================================
	private function desmenuzo(){
		


		if(!empty($_POST['empresa'])){
			$this->existe_empresa($_POST['empresa']);
		}else if(!empty($_GET['inmv'])){
			$this->existe_empresa($_GET['inmv']);
		}else{
			$this->user = null;	//no solicita empresa (empty)
		}


	}


	public function extraigo_para_anuncios($param){

		$this->where('id',$param);

		$cols = array('id','empresa','img','descripcion','img_fondo','empresa_email',
					  'empresa_telefono');

		if($salida = $this->getOne('perfiles_emp',$cols)){

			$this->user 				 = $salida['id'];
			$this->salida['img'] 	 	 = $salida['img'];
			$this->salida['empresa'] 	 = $salida['empresa'];
			$this->salida['nik_empresa'] = strtolower($salida['empresa']);
			$this->salida['descripcion'] = $salida['descripcion'];
			$this->salida['img_fondo']   = $salida['img_fondo'];
			$this->salida['e_email'] 	 = $salida['empresa_email'];
			$this->salida['e_telefono']  = $salida['empresa_telefono'];

			//return $this->user;
		}else{
			//return FALSE;
			$this->user = false;	//empresa solicitada no existe
		}

	}


	//comprueba si existe empresa con ese nombre, 
	//sin este metodo nada tendra sentido
	public function existe_empresa($param){

		if(!is_int($param)){
			$name = strtolower($param);
			$this->where('nik_empresa',$name);			
		}else{
			$this->where('id',$param);
		}

			$cols = array('id','empresa','img','direccion','descripcion',
						  'empresa_telefono','empresa_email','img_fondo');


			if($salida = $this->getOne('perfiles_emp',$cols)){

				$this->user 				 = $salida['id'];
				$this->salida['img'] 	 	 = $salida['img'];
				$this->salida['img_fondo']   = $salida['img_fondo'];
				$this->salida['empresa'] 	 = $salida['empresa'];
				$this->salida['e_email'] 	 = $salida['empresa_email'];				
				$this->salida['nik_empresa'] = strtolower($salida['empresa']);
				$this->salida['direccion'] 	 = $salida['direccion'];
				$this->salida['descripcion'] = $salida['descripcion'];
				$this->salida['e_telefono']  = $salida['empresa_telefono'];

				//return $this->user;
			}else{
				//return FALSE;
				$this->user = false;	//empresa solicitada no existe
			}

	}

	//obtiene el mod (tipo de cuadros en los que muestra los anuncios)
	//=============================================================
	private function obtengo_mod(){
		if(!empty($_SESSION['busqueda']['mod'])){
			$this->mod = $_SESSION['busqueda']['mod']; 
		}else{						 
			$this->mod = 'mod1';
		}	
	}


	//obtiene pagina(para paginacion segun la info que encuentre)
	//=============================================================
	private function obtengo_pagina(){
		if(!empty($_GET['pagg_inmo']) ){  
			$this->pagina = $_GET['pagg_inmo'];
		}
		if(!empty($_GET['pagg_empresas']) ){  
			$this->pagina = $_GET['pagg_empresas'];
		}
	}























	//mostrara la paginacion de empresas //conexion y pagina
	//===============================================================================
	public function paginacion_empresas(){
		$return = '';
		$consulta = 'SELECT * FROM perfiles_emp WHERE apto ="1" AND visible="1"';
		$this->publicar_mysqli();
		$options = array(  
			'url'        	=> 'index.php?inmv=&pagg_empresas=*VAR*',  
			'db_handle'  	=> $this->public_mysqli,
			'results_per_page' => ANUNCIOS_POR_PAGINA_EMPRESA,
			'db_conn_type'  => 'mysqli');
		$Pag = new pagination($this->pagina, $consulta, $options);

		if($Pag->success == true){  
			//var_dump($pagination);
			while($salida = $Pag->resultset->fetch_assoc()){
				$empresa_link = 'index.php?inmv='.$salida['nik_empresa'];
				$return .= $this->empresa_box($salida,$empresa_link);
			}		
			if($Pag->total_pages <1){	$return .= 'No hay resultados'; }
			if($Pag->total_pages >1){	$return .= $Pag->links_html; 	}
		}else{ 
		$return .= 'no hay resultados';
		}

		return $return;
	}

	//cada cajon con info de empresa
	//============================================
	private function empresa_box($salida,$empresa_link){
		return '<div class="list-empresa">
							<div class="row">
								<div class="col-sm-3 list-e-left">
									<div>
										<img src="imagenes/logo/'.$salida['img'].'" />
									</div>	
									<span class="btn btn-success">
										<a href="'.$empresa_link.'">Visitar anunciante</a>
									</span>
									<span class="btn btn-warning">
										<a href="'.$empresa_link.'&inmv_section=oficinas">Ver Oficinas</a>
									</span>
									<span class="btn btn-primary">
										<a href="'.$empresa_link.'&inmv_section=agentes">Ver Agentes</a>
									</span>
								</div>
								<div class="col-sm-7 list-e-center">
									<h2>'.$salida['empresa'].'</h2>
									<p>'.$salida['descripcion'].'</p>
									<h5>Idiomas</h5>
									<span>chino</span><span>ruso</span><span>checoslovaco</span>
									<a href="'.$empresa_link.'">Ver propiedades</a>
								</div>
								<div class="col-sm-2 list-e-right"></div>
							</div>	
						</div>';

	}














	//muestra datos de la empresa
	//=====================================
	public function show_datos_empresa(){

		$return  ='<div class="col-md-6 col-sm-6">';
		$return .='<div class="team-container">';
		$return .='<div class="team-image">';
		$return .='<img src="imagenes/logo/'.$this->salida['img'].'" alt="'.$this->salida['empresa'].'">';
		$return .='</div>';
		$return .='<div class="team-description">';
		$return .='<h4>'.$this->salida['empresa'].'</h4>';
		$return .='<p><i class="fa fa-phone"></i> Oficina : '.$this->salida['e_telefono'].'<br>';
		$return .='<i class="fa fa-mobile"></i> Movil : +62-3456-78910<br>';
		$return .='<i class="fa fa-print"></i> Fax : 021-234-5679<br>';
		$return .='<i class="fa fa-envelope-o"></i> Email : '.$this->salida['e_email'].'</p>';
		$return .='<p>'.$this->salida['descripcion'].'</p>';
		$return .='</div>';
		$return .='</div>';
		$return .='</div>';

		return $return;
		                            
	}



	public function show_contacto_empresa($Form,$origen){
		$saltador = $this->select_salt($this->user);

		$return = '<div class="col-md-6 col-sm-6">';
		$return .= '<form id="user_contact" method="post" class="agent-contact-form family_alert">';
		$return .= '<input type="hidden" name="ip_enc" 		value="'. md5($_SERVER['REMOTE_ADDR']).'"/>';
		$return .= '<input type="hidden" name="aleatorio" 	value="'. $_SESSION['invisible']['token_key'].'"/>';
		$return .= '<input type="hidden" name="alerta"  	value="user_contact" />';
		$return .= '<input type="hidden" name="saltador"  	value="'.$saltador.'" />';
		$return .= '<input type="hidden" name="origen"  	value="'.$origen.'" />';			
		$return .= '<div class="form-group">';
		$return .= '<label for="name">Nombre *</label>';
		$return .= '<input id="name" name="nombre" class="form-control input-lg" placeholder="Enter name : " type="text">';
		$return .= '</div>';
		$return .= '<div class="form-group">';
		$return .= '<label for="email">Email *</label>';
		$return .= '<input id="email" name="email" class="form-control input-lg" placeholder="Enter email : " type="email">';
		$return .= '</div>';
		$return .= '<div class="form-group">';
		$return .= '<label for="phone">Telefono</label>';
		$return .= '<input id="phone" name="telefono" class="form-control input-lg" placeholder="Enter phone number : " type="text">';
		$return .= '</div>';
		$return .= '<div class="form-group">';
		$return .= '<label for="message">Message *</label>';
		$return .= '<textarea id="message" name="comentario" req="required" class="form-control input-lg" rows="4" placeholder="Type a message : "></textarea>';
		$return .= '</div>';
		$return .= $Form->privatepol();			
		$return .= '<div class="form-group">';
		$return .= '<input value="Send Message" class="btn btn-block btn-success" type="submit">';
		$return .= '</div>';
	
		$return .= '</form>';
		$return .= '</div>';


		return $return;
	}



	public function show_datos_ycontacto($Form){

		if(!empty($_GET['pag'])){$origen = $_GET['pag'];
		}else{					 $origen = 'Perfil de empresa';	}
		$return = '<div class="row">';
		$return .= '<div class="col-md-12"><div class="heading-title">';
		$return .= '<h3 class="text-left">Datos de empresa</h3>';
		$return .= '</div></div>';
		$return .= $this->show_datos_empresa();
		$return .= $this->show_contacto_empresa($Form, $origen);
		$return .= '</div>';

		return $return;

	}



	public function empresa_botonera(){

		$link    = 'index.php?inmv='.$this->salida['nik_empresa'];
		$return  = '<a href="'.$link.'" class="btn btn-default" role="button">Propiedades</a>';
		$return .= '<a href="'.$link.'&inmv_section=oficinas" class="btn btn-default" role="button">Oficinas</a>';
		$return .= '<a href="'.$link.'&inmv_section=agentes" class="btn btn-default" role="button">Agentes</a>';

		return $return;
	}






public function show_oficinas_empresa(){

	$return  = '';
	
	$this->where('empresa',$this->user);
	$this->where('activo',1);
	$this->orderBy('sede_central','DESC');
	if($salida = $this->get('empresa_oficinas')){

		foreach ($salida as $key => $value) {	
			$sede   = '';
			$movil2 = '';	

			if($value['sede_central'] == 1){$sede = '<h3>Sede Central</h3>';}
			if(!empty($value['movil2'])){
				$movil2 = '<i class="fa fa-mobile"></i> Movil2 : '.$value['movil2'].'<br>';
			}

			$return .= '<div class="col-sm-12 oficina-vista"><div class="row">';
			$return .= '<div class="col-sm-12 col-md-3 oficina_img">';
			$return .= '<img src="imagenes/oficinas/'.$value['img'].'" alt="" title="'.$value['nombre'].'"/>';
			$return .= '</div>';
			$return .= '<div class="col-sm-12 col-md-9">';
			$return .= '<div class="oficina-desc">';	
			$return .= $sede;		
			$return .= '<h4>'.$value['nombre'].'</h4>';
			$return .= '<h5>'.$value['poblacion'].'</h5>';		
			$return .= '<p><i class="fa fa-phone"></i> Oficina : '.$value['tel'].'<br>';
			$return .= '<i class="fa fa-mobile"></i> Movil : '.$value['movil'].'<br>';	
			$return .= $movil2;
			$return .= '<i class="fa fa-print"></i> Fax : '.$value['fax'].'<br>';	
			$return .= '<i class="fa fa-envelope-o"></i> Email : '.$value['email'].'</p>';
			$return .= '</div>';

			$return .= '</div>';	
			$return .= '</div></div>';	

		}
	}else{
		$return = 'No hay Oficinas Inscritas. Porfavor inscriba almenos una.';
	}
	return $return;
}



public function show_agentes_empresa(){
	$return = '';

	$this->where('empresa',$this->user);
	$this->where('activo',1);
	if($salida = $this->get('empresa_agentes')){
		$return .= '<div class="row">';
		foreach ($salida as $key => $value) {
	
			$return .= '<div class="col-sm-4 agente-vista">';
			$return .= '<div class="thumbnail">';
			$return .= '<img src="imagenes/agentes/'.$value['img'].'" alt="'.$value['nombre'].'">';
			$return .= '<div class="caption">';
			$return .= '<h3>'.$value['nombre'].'</h3>';
			$return .= '<h4>'.$value['cargo'].'</h4>';
			$return .= '<h5>'.$value['idiomas'].'</h5>';

			$return .= '<p><i class="fa fa-mobile"></i> Movil : '.$value['movil'].'<br>';			
			$return .= '<i class="fa fa-envelope-o"></i> Email : '.$value['email'].'</p>';
			$return .= '</div>';				

			$return .= '</div>';		
			$return .= '</div>';
		}
		$return .= '</div>';
	}else{
		$return = 'No hay asesores inmobiliarios inscritos.';
	}
	return $return;
}



public function show_promo_logos_slider(){
$return ='<div class="col-sm-12">';
$return .= '<div id="promo-slider" class="owl-carousel owl-theme">';
//$return .= '<div class="promo-slider-cont">';

$cols = array('nik_empresa','img');
$salida = $this->get('perfiles_emp',null,$cols);
	foreach ($salida as $key => $value) {
		$return .= '<div class="item">';
		$return .= '<a href="index.php?inmv='.$value['nik_empresa'].'">';
		$return .= '<img src="imagenes/logo/'.$value['img'].'" title="" alt="" />';
		$return .= '</a>';
		$return .= '</div>';	
		$return .= '<div class="item">';
		$return .= '<a href="index.php?inmv='.$value['nik_empresa'].'">';
		$return .= '<img src="imagenes/logo/'.$value['img'].'" title="" alt="" />';
		$return .= '</a>';
		$return .= '</div>';	
		$return .= '<div class="item">';
	//	$return .= '<a href="index.php?inmv='.$value['nik_empresa'].'">';
		$return .= '<img src="imagenes/logo/'.$value['img'].'" title="" alt="" />';
	//	$return .= '</a>';
		$return .= '</div>';	

		$return .= '<div class="item">';
	//	$return .= '<a href="index.php?inmv='.$value['nik_empresa'].'">';
		$return .= '<img src="imagenes/logo/'.$value['img'].'" title="" alt="" />';
	//	$return .= '</a>';
		$return .= '</div>';	

		$return .= '<div class="item">';
	//	$return .= '<a href="index.php?inmv='.$value['nik_empresa'].'">';
		$return .= '<img src="imagenes/logo/'.$value['img'].'" title="" alt="" />';
	//	$return .= '</a>';
		$return .= '</div>';									
	
	}

//$return .= '</div>';
$return .= '</div>';
$return .= '</div>';

return $return;
}












                    


              
                
                
            
 



}//end class



?>