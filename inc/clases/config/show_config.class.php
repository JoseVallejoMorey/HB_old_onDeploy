<?php

// //necesarios para anuncios
require_once 'inc/clases/builders.class.php';
require_once 'inc/clases/procesos/reserva_builder.class.php';//(para modulos)
include_once 'inc/clases/form_builders.class.php';
require_once 'inc/clases/config/builder_config.class.php';
require_once 'inc/clases/public/modulos.class.php';

class show_config extends builder_config{

	public $anuncio = array();
	public $empresa = array();
	

	//objetos
	public $Modulos;
	public $Empresa;
	public $Usuario;

	public function __construct(){

		parent::__construct();
		//var_dump($this->seccion['id']);
		if($this->seccion['id'] == 'pag'){		$this->iniciar_pagina_anuncio();	}		
		if($this->seccion['id'] == 'inmv'){		$this->iniciar_pagina_empresas();	}

		if($this->seccion['id'] == 'perfil'){	$this->iniciar_perfil();}
		if($this->seccion['id'] == 'accion'){	$this->iniciar_accion();}
		//if($this->seccion['id'] == 'busqueda'){		$this->iniciar_mn_nav();}	
		if($this->seccion['id'] == 'mn_nav'){		$this->iniciar_mn_nav();}	
		if($this->seccion['id'] == 'generico'){		$this->iniciar_mn_nav();}	
		if($this->seccion['id'] == 'perfil_mda'){	$this->iniciar_perfil_mda();	}
		if($this->seccion['id'] == 'inmv_lista'){	$this->iniciar_indice_empresas();	}
	}



	private function iniciar_accion(){
		//require_once 'inc/clases/alertas/alertas.class.php';
		require_once 'inc/clases/procesos/sql_operations.class.php';		
		require_once 'inc/clases/procesos/master.class.php';
	}

	private function iniciar_perfil(){
		//si no hay session redirige a index
		// if(!isset($_SESSION['id_user'])){
		// 	header('location : index.php');
		// }

		require_once 'inc/clases/procesos/fechas.class.php';
		require_once 'inc/clases/perfil/usuarios.class.php';			//clase usuarios
		$this->Modulos = new Modulos();	
		$this->Usuario = new Usuarios();			
	}
	private function iniciar_perfil_mda(){
		//si no hay session redirige a index
		require_once 'inc/clases/procesos/fechas.class.php';
		require_once 'inc/clases/procesos/sql_anuncios.class.php';
		require_once 'inc/clases/mda/show_master.class.php';
		require_once 'inc/clases/mda/pedidos.class.php';
		require_once 'inc/clases/config/perfil_config.class.php';

		$this->Modulos = new Modulos();
	}

	private function iniciar_mn_nav(){
		require_once 'inc/clases/public/main_con.class.php';
		$this->Modulos 	= new main_con();
		//si hay get seccion debe ser una de las correctas, sino 404
		$capaces = $this->Modulos->permitidos;
		if( (!empty($_GET['mn_nav'])) && (!in_array($_GET['mn_nav'],$capaces)) ){
			$this->forzar_404();
		}
	}

	//inicia el objeto para pagina de anuncio
	//========================================
	private function iniciar_pagina_anuncio(){
		//==========necesarios para anuncios===============
		require_once 'inc/clases/form_busqueda_builder.class.php';	//para formularios ($Form)
		require_once 'inc/clases/public/empresa.class.php';			//pagina de empresa (y empresas)
		require_once 'inc/clases/public/pagina_anuncio.class.php';		//pagina anuncio(Modulo)


		$Anuncio = new Pagina_anuncio();
		//var_dump($Anuncio);
		if($Anuncio->correcto == false){
			$this->forzar_404();
		}else{
			//var_dump('esta ok');
			$this->anuncio = $Anuncio->salida;
			$empresa = $this->anuncio['ussr'];
			$this->Empresa = new Empresa();
			$this->Empresa->extraigo_para_anuncios($empresa);

		}
	}


	//inicia el objeto para pagina de emepresas
	//========================================
	private function iniciar_pagina_empresas(){
		//==========necesarios para empresa================
		require_once 'inc/clases/public/empresa.class.php';			//pagina de empresa (y empresas)
		require_once 'inc/clases/config/builder_config.class.php';	//clase builder(reservas)
		require_once 'inc/clases/form_busqueda_builder.class.php';	//para formularios ($Form)
		require_once 'inc/clases/public/salida_anuncios.class.php';

		$this->Empresa = new Empresa();
		$this->Modulos = new Salida_anuncios();
		if(is_null($this->Empresa->user)){
			//es null, devolvera inmobiliarias
		}else if($this->Empresa->user == false){
			$this->forzar_404();
		}else{
			//es bien
			$this->empresa = $this->Empresa->salida;
		}
	}


	//inicia el objeto para indice de emepresas
	//========================================
	private function iniciar_indice_empresas(){
		//==========necesarios para empresa================
		require_once 'inc/clases/public/empresa.class.php';			//pagina de empresa (y empresas)
		require_once 'inc/clases/config/builder_config.class.php';	//clase builder(reservas)
		require_once 'inc/clases/public/salida_anuncios.class.php';

		$this->Empresa = new Empresa();
		$this->Modulos = new Salida_anuncios();
		// if(is_null($this->Empresa->user)){
		// 	//es null, devolvera inmobiliarias
		// }else if($this->Empresa->user == false){
		// 	$this->forzar_404();
		// }else{
		// 	//es bien
		// 	$this->empresa = $this->Empresa->salida;
		// }
	}


	//direccion generica
	public function direccion_generica(){
			include 'modulos/promo.php';
	}

	//trae un footer segun seccion
	//==============================
	public function section_footer(){
		include 'secciones/'.$this->seccion['footer'].'.php'; 
	}
	//trae un aside segun seccion
	//==============================
	public function section_aside(){
		if($this->seccion['aside'] != 'false'){
			include 'secciones/'.$this->seccion['aside'].'.php';
		}
	}
	//trae un adapted segun seccion
	//==============================
	public function section_adapted(){
		return '<div id="main" class="'.$this->seccion['adapted'].'">';
	}



	//obtiene la seccion que viene por _GET
	public function obtener_seccion(){
		//si por get viene una seccion correcta, se buscara su include 
		if(isset($_GET[$this->seccion['id']])){
			if(!empty($this->seccion['include'])){
					//var_dump($this->seccion['include']);
					include $this->seccion['include'].'.php';
			}

		}else if(empty($_GET)){
			//no existe seccion que se envia por get, se continua con seccion predefinida
			//'secciones/promo.php' o 'modulos/main_con.php'
			
			$this->direccion_generica();
		}else{
			//es algo en GET desconocido por el sistema
			//header("HTTP/1.0 404 Not Found");
			//header("Status: 404 Not Found");
			if($this->seccion['id'] == 'no_disponible'){
				include $this->seccion['include'].'.php';
			}else{
				include 'modulos/error/404.php';	
			}			
		}
	}




	//mostra links de footer
	//===========================
	public function footer_links(){
		$return = '';
        $this->where('idioma',$_SESSION['lg']);
        $this->where('seccion','footer');
        if($quecosa = $this->get('links_main')){
            foreach ($quecosa as $key => $value) {
                $link = 'index.php?'.$value['primer_parametro'].'='.$value['primer_valor'];
                if($value['segundo_parametro'] != ''){
                    $link .= '&'.$value['segundo_parametro'].'='.$value['segundo_valor'];
                }
                $return .= '<span><a href="'.$link.'">'.$value['titulo'].' |</a></span> ';
            }
        }
        return $return;
	}






//menus
//========================================

	//mostrara logo usuario y nombre empresa en menu admin
	public function menu_usuario_foto(){
		$empresa = '';
		if($this->tipo_usuario == 'empresa'){
			$empresa = '<h3>'.$this->Usuario->empresa.'</h3>';
		}
		$return  ='<div class="sidebar-header">';
		if($this->tipo_usuario != 'particular'){
			$logo = $this->Modulos->Perfil->sacar_logo();	
			if(!empty($logo)){	$return .= $logo;
			}else{				$return .='<img src="http://placehold.it/80x80" />';	}	
		}		
		$return .=	'<h2>'.$_SESSION['username'].'</h2>';		
		'';
		$return .= $empresa;
		$return .='</div>';

		return $return;
	}

	public function menu_perfil(){
		//lista de menus// menu del dia
		$this->menu_del_dia();

		$return  = '<ul class="nav nav-sidebar">';
		foreach($this->menu_del_dia as $key => $value){
		//menus que se muestran
			if(!empty($value['show'])){
				if ($value['show'] == 'true') {
					$return .= '<li>';
					$return .= '<a href="'.$value['link'].'"><i class="icon-magic-wand"></i><span class="text">'.$value['text'].'</span></a>';	
					$return .= '</li>';
				}
			//menus desplegables	
			}else if (is_array($value)){
				$interlist = '';
				//var_dump($value);
				foreach ($value as $key2 => $value2) {
					$title = $value2['title'];	
					//var_dump($title);					
					$titulo = '<li><a href="#"><i class="icon-magic-wand"></i><span class="text">'.$title.'</span><span class="indicator"></span></a>';;
					$interlist .= '<li><a href="'.$value2['link'].'"><i class="icon-magic-wand"></i><span class="text">'.$value2['text'].'</span></a></li>';	
				
				}
				$return .= $titulo;
				$return .= '<ul>';
				$return .= $interlist;
				$return .= '</ul>';
				$return .= '</li>';

			}	
		}	
		$return .= '</ul>';


		// $return .= '</ul>';
		return $return;	 
	}


	//page-header (para pagina anunciom y empresa)
	//======================================================
	public function page_header(){
		$e = $this->Empresa->salida;
		$this->Empresa->where('id',$this->Empresa->user);
		$fondo = $this->Empresa->getOne('empresa_fondo','img_fondo');
		
		$link = 'index.php?inmv='.$e['nik_empresa'];		
		$return  = '<div class="page-header" style="background-image: url(imagenes/fondos/'.$fondo['img_fondo'].');">';
		$return .= '<div class="header-info">';
		$return .= '<div class="row">';
		$return .= '<div class="logo-header"><a href="'.$link.'"><img src="imagenes/logo/'.$e['img'].'" /></a></div>';
		$return .= '<div class="header-desc col-md-6 visible-md visible-lg">';
		$return .= '<h1>'.$e['descripcion'].'</h1>';
		$return .= '</div>';
		$return .= '</div>';
		$return .= '</div>';
		$return .= '</div>';
		return $return;
	}



}


?>