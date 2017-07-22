<?php 

require_once 'inc/clases/mysqlidb.main.php';

class builder_config extends mysqlidb{


	public $tipo_usuario;
	private $base_name;
	protected $estado;
	protected $secciones;
	public $seccion;
	public $user;
	public $mda_status;
	protected $menu_del_dia;
	public $url;
	public $idioma = array();



	//constructor
	public function __construct(){

		if (!empty($_SERVER['HTTP_REFERER'])) {	$this->url = $_SERVER['HTTP_REFERER'];	}
		if(!empty($_SESSION['tipo'])){		$this->tipo_usuario = $_SESSION['tipo'];	}
		if(!empty($_SESSION['user_id'])){	$this->user  		= $_SESSION['user_id'];	}
		if(!empty($_SESSION['mda_id'])){	$this->mda_status 	= $_SESSION['mda_id'];	}

		if($this->tipo_usuario == 'mda'){
			if( ($this->mda_status != '1') || (is_null($this->mda_status)) ){
				$this->tipo_usuario = 'empresa';
			}	
		}

		$this->base_name = 'configuracion';
		
		parent::__construct($this->base_name);
		$this->estado 	 = $this->get_estado();		//estado actual del portal
		$this->secciones = $this->get_secciones();	//secciones validas en estado
		$this->seccion   = $this->old_get_seccion();	//seccion actual del portal
		//$this->seccion   = $this->get_seccion();	//seccion actual del portal
		$this->idioma_de_pagina();
		//var_dump($this);
	}



	private function idioma_de_pagina(){
		if(empty($_GET['lg'])){

			if(empty($_SESSION['lg'])){
				$_SESSION['lg'] = 'esp';
				$_SESSION['lg_cambio'] = '';				
								
			}
			$this->idioma['lg'] = $_SESSION['lg'];
			
		}else{
			
			//verifico que idioma existe
			$idiomas_permitidos = array('esp','eng','ger');
			if(in_array($_GET['lg'],$idiomas_permitidos)){
				$_SESSION['lg'] = $_GET['lg'];
			}else{
				$_SESSION['lg'] = 'esp';
			}
			$this->idioma['lg'] = $_SESSION['lg'];
			//si pasa esto hay un cambio de idioma
			//lo metemos en una sesion, luego si esta vacia

			if($_SESSION['lg_cambio'] == ''){
				$_SESSION['lg_cambio'] = $this->url;
			}

			//$this->idioma['lg'] = $_SESSION['lg'];
			//var_dump($pasadaurl);
			//header('Location: '.$this->url);
		}


	}





	//obtiene el estado de la pagina
	private function get_estado(){
		$s = $this->getOne('estado');
		foreach ($s as $key => $value) {
			if($value == 1){ return $key; }
		}
	}
	//mostrara de forma publica
	public function show_estado(){
		return $this->estado;
	}
	public function show_section(){
		return $this->seccion['id'];
	}
	//obtiene las secciones disponibles
	private function get_secciones(){
		$tabla = 'secciones';
		$a = array();
		if($this->tipo_usuario != 'mda'){
			$this->where($this->estado,1);
		}
		
		if($s = $this->get($tabla,NULL,'id')){
			foreach ($s as $key => $value) {
				foreach ($value as $key2 => $value2) {
					array_push($a, $value2);
		}	}	}
		return $a;
	}



	//si no es promo, digo generico y si hay get; GET
	private function old_get_seccion(){


		//lista de gets que dejara pasar
		//'pag','pagg','inmv','archivo','mn_nav','perfil','perfil_mda','accion',
		$white_get = array('lg','pagg_inmo','pagg_empresas','art','borr',
		'destroy','delimg','prince','validation','linkdel','deletear',
		'mpresa','subtipo','poblacion','land_sub','land_cat','link_operator',
		'secciones','directivas','sujeto','mantenimiento','paquete','controlator',
		'payprocess','paycancel','token','PayerID','landing_sec','landing_esp');

		//habra 3 listas blancas distintas segun.... ¿tipo user?	
		
		//if($sel != 'promo'){$sel = 'generico';}
		//$sel = $this->estado;
		
		if(!empty($_GET)){
			foreach ($_GET as $key => $value) {
				if(in_array($key,$this->secciones)){
					$sel = $key;
				}else{
					if($key == 'accion'){$sel = 'no_disponible';
					} //else if(!in_array($key, $white_get)){ $sel = '404'; }
				}	
			}		
		}else{	
			//si GET esta vacio sera promo
			$sel = 'promo';
		}

		//unset($this->secciones);	//borro, ya no las necesito
		$tabla = 'secciones';
		$this->where('id',$sel);
		if($s = $this->getOne($tabla)){
			return $s;
		}
	}











	//directamente 404 para la seccion
	public function forzar_404(){
		$tabla = 'secciones';
		$this->where('id','404');
		if($s = $this->getOne($tabla)){
			$this->seccion = $s;
		}
	}

	//fuerza la seccion a no disponible
	public function forzar_no_disponible(){
		$tabla = 'secciones';
		$this->where('id','no_disponible');
		if($s = $this->getOne($tabla)){
			$this->seccion = $s;
		}
	}


	public function cargando_pagina(){

		//si es uno de estos 3 se ira a admin.php y de ahi a donde corresponda
		$admin = array('perfil','perfil_mda','accion');

		if(in_array($this->seccion['id'], $admin)){
			include 'modulos/admin.php';
		}else{
			include 'modulos/public.php';
		}
		

	}



	

	//para perfil solo se abren paginas permitidas
	public function directivas_permitidas(){
		$tabla = 'directivas_'.$this->tipo_usuario;
		if($this->tipo_usuario != 'mda'){
			$this->where('estado',1);
		}
		$return = $this->get($tabla);
		return $return;	
	}

	//menu para directiva
	private function menu_promo(){

		$menu_config  = array();
		$menu_final   = array();

		if($this->tipo_usuario != 'mda'){
			$tabla = 'directivas_promo_'.$this->tipo_usuario;
			$this->where('estado',1);
		}else{
			$tabla = 'directivas_mda';
		}
		
		$perfil_menu = $this->get($tabla);
		foreach ($perfil_menu as $key => $value) {
			if($value['orden'] == '18'){
				$menu_config[$value['id']]= $perfil_menu[$key];
				unset($perfil_menu[$key]);
			}
			if(isset($perfil_menu[$key])){
				$menu_final[$value['orden']] = $perfil_menu[$key];
			}
		}
		if($this->tipo_usuario != 'mda'){
			$menu_final[18] = $menu_config;
		}
		
		ksort($menu_final);	
		return $menu_final;
	}

	//menu para directiva
	private function menu_empresa(){
		$menu_alertas = array();
		$menu_config  = array();
		$menu_final   = array();
		$this->where('estado',1);
		$perfil_menu = $this->get('directivas_empresa');

		foreach ($perfil_menu as $key => $value) {
			if($value['orden'] == '2'){
				$menu_alertas[$value['id']]= $perfil_menu[$key];
				unset($perfil_menu[$key]);
			}
			if($value['orden'] == '18'){
				$menu_config[$value['id']]= $perfil_menu[$key];
				unset($perfil_menu[$key]);
			}
			if(isset($perfil_menu[$key])){
				$menu_final[$value['orden']] = $perfil_menu[$key];
			}
		}
		$menu_final[2] = $menu_alertas;	
		$menu_final[18] = $menu_config;
		ksort($menu_final);	
		return $menu_final;
	}

	//menu para directiva
	private function menu_particular(){
		$menu_config  = array();
		$menu_final   = array();
		$this->where('estado',1);
		$perfil_menu = $this->get('directivas_particular');

		foreach ($perfil_menu as $key => $value) {
			if($value['orden'] == '18'){
				$menu_config[$value['id']]= $perfil_menu[$key];
				unset($perfil_menu[$key]);
			}
			if(isset($perfil_menu[$key])){
				$menu_final[$value['orden']] = $perfil_menu[$key];
			}
		}
		$menu_final[18] = $menu_config;
		ksort($menu_final);	
		return $menu_final;	
	}

	//menu para directiva
	private function menu_mda(){
		$menu_final   = array();
		$perfil_menu = $this->get('directivas_mda');
		$menu_final = $perfil_menu;
		ksort($menu_final);	
		return $menu_final;
	}


	//obtiene el menu correspondiente
	protected function menu_del_dia(){
		if($this->estado == 'promo'){				$this->menu_del_dia = $this->menu_promo();
		}else{
			if($this->tipo_usuario == 'empresa'){	$this->menu_del_dia = $this->menu_empresa();	}
			if($this->tipo_usuario == 'particular'){$this->menu_del_dia = $this->menu_particular();	}
			if($this->tipo_usuario == 'mda'){		$this->menu_del_dia = $this->menu_mda();		}
		}
	}





//cambiara el estado del portal
//1-activo, 2-promo, 3-mantenimiento
public function cambio_de_estado($nu){
	if($nu == 1){		$nu_estado = 'activa';
	}else if($nu == 2){	$nu_estado = 'promo';
	}else if($nu == 3){	$nu_estado = 'mantenimiento';}

	if($salida = $this->getOne('estado')){
		foreach ($salida as $key => $value) {
			if($value == 1){$last_estado = $key;}
		}

		$campos = array('activa' => 0, 'promo' => 0, 'mantenimiento' => 0);
		$nuevo_estado = array($nu_estado => 1);			
		//desactivando estados
		$this->where('id',0);
		if($this->update('estado',$campos)){
			//activando estado nuevo
			$this->where('id',0);
			$this->update('estado',$nuevo_estado);			
		}
		//registro en cambios de estado
		$campos = array('estado_inicial' => $last_estado,
						'nuevo_estado' 	 => $nu_estado);
		$this->insert('cambios_estado',$campos);
	}
}

//activa o desactiva secciones o directivas (filtra innegociables)
public function activar_desactivar_secciones_directivas($tabla,$sujeto){
	//las siguientes no podran desactivarse

	$innegociables = array('perfil','perfil_mda','385');
	if(!in_array($sujeto, $innegociables)){
		$this->where('id',$sujeto);
		if($salida = $this->getOne($tabla,'estado')){
			if($salida['estado'] == 1){	$col = array('estado' => 0);
			}else{						$col = array('estado' => 1);	}

			$this->where('id',$sujeto);
			$this->update($tabla,$col);
			//header('Location: index.php?perfil_mda=10');
		}		
	}

}



//activa o desactiva secciones (especificas o generales) de landing
//=================================================================
	public function landing_change($sujeto, $array, $valor){

			$col = array('estado' => $valor);
			foreach ($array as $key => $value) {
				$this->where('id',$value);
				$this->update('landing_sections',$col);	
			}
	}

	public function landing_general_change($sujeto){
		//apago los dos
		$borrar = array('promo' => 0,'activa' => 0);
		$this->where('id',0);
		$this->update('landing_estado',$borrar);
		//enciendo el que quiero
		$on = array($sujeto => 1); 	
		$this->where('id',0);
		$this->update('landing_estado',$on);
	}


	public function landing_interruptor($sujeto){
		$tabla 	  = 0;
		$status   = array('promo','activa');
		$s_promo  = array('1a','1b','1c','1d');
		$s_activa = array('2a','2b','2e');
		$s_todas  = array_merge($s_promo, $s_activa);


		if(in_array($sujeto, $status)){
			//es seccion general
			if($sujeto == 'promo'){
				$this->landing_change('activa',$s_activa,0);
				$this->landing_change('promo',$s_promo,1);
			}else{
				$this->landing_change('promo',$s_promo,0);			
				$this->landing_change('activa',$s_activa,1);
			}
			$this->landing_general_change($sujeto);

		}else{
			//una sola seccion especifica
			if(in_array($sujeto, $s_todas)){
				$this->where('id',$sujeto);
				if($salida = $this->getOne('landing_sections','estado')){
					if($salida['estado'] == 1){	$col = array('estado' => 0);
					}else{						$col = array('estado' => 1);	}
					$this->where('id',$sujeto);
					$this->update('landing_sections',$col);	
				}	
			}
		}

	}




//ahota dos funciones creando links validos y eliminando links
//==========================================================
public function link_foot_storator($posibles){
	//hay que agregar en links_footer lo que recibimospor post
	$section = array();
	if(!empty($_POST['two_cual'])){		$two = $_POST['two_cual'];
	}else{								$two = '';					}

	foreach ($_POST as $key => $value) {
		if(strstr($key, 'que')){
			if($value != ''){
				array_push($section, $posibles[$value]);
			}else{
				$section[1] = '';
	}	}	}	
	//preparamos y guardamos
	$cols = array('titulo'  => $_POST['link_titulo'],
				  'seccion' => $_POST['section'],
				  'idioma'  => $_POST['idioma'],
				  'primer_parametro'  => $section[0],
				  'primer_valor' 	  => $_POST['one_cual'],
				  'segundo_parametro' => $section[1],
				  'segundo_valor' 	  => $two);
	$this->insert('links_main',$cols);

}

	//elimina el link indicado
	public function link_deleteator($delete){
		$this->where('id',$delete);
		$this->delete('links_main');
	}





	//mostrara titulo de la seccion (perfil y perfil_mda)
	//=================================================
	public function show_title($array){
		$return = '';

		if($array['title'] == ''){
			$texto = $array['text'];
		}else{
			$texto = $array['title'].' / '.$array['text'];
		}

		$return .= '<ol class="breadcrumb">';
		$return .=   '<li><a href="index.php?perfil=1">Panel Principal</a></li>';
		$return .=   '<li class="active"><a href="'.$array['link'].'">'.$texto.'</a></li>';						

		$return .= '</ol>';
		return $return;


			
	}








}

?>