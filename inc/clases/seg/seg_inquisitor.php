<?php

//seg_inquisitor
//siendo esta una segunda capa de seguridad para post es la primera para get y session
//filtrara formularios y todos los datos a tratar

class Inquisitor{

	private $contador 		= 0;	//numero de veces que realiza el bucle
	private $descartes 		= 0;	//numero de descartes
	private $descartados 	= '';	//strings detectados y descartados

	//listas blancas y negras
	private $white_get;			//aceptadas por get
	private $white_sesion;		//aceptadas en session
	private $numeric_get;		//get que deben ser numericas
	private $numeric_post;		//post que deben ser numericos
	private $sql_restrict;		//lista negra sql
	private $fun_restrict;		//lista negra de funciones
	private $code_restrict;		//lista negra de codigo

	//errores y aciertos
	public $report;				//anuncio del fallo
	public $report_key;			//datos del fallo

	//posiciones iniciales
	private $continue 			= NULL;	
	private $filtro_get 		= NULL;
	private $filtro_post 		= NULL;
	private $filtro_session 	= false;

	public function __construct(){



		$this->white_sesion = array('invisible','lg','lg_cambio','provincia','municipio','tipo_inmueble',
		'subtipo_inmueble','precio_min','precio_max','superf_min','superf_max','rooms', 'ordenar',
		'filtro1','filtro2','empresa','tipo','username','user_id','login_string','mda_id','mdaname',
		'mda_login_string','new_user','email_val','corrector','con_provincia','con_tipo_inmueble',
		'lang_form','ip_enc','aleatorio','mod','busqueda','action_error','itempay', 'datapay');

		//listas negras
		$this->sql_restrict = array('SELECT','LIKE','update','insert','DELETE','copy','dump','drop',
		' or ');

		$this->fun_restrict = array('eval', 'gzinflate', 'base64_decode', '<script');

		$this->code_restrict = array('<?','<%','()','""',"''", "..\\", "../", "--", "<?php");


		//get numericos
		$this->numeric_get = array('pagg','pagg_inmo','pagg_empresas','paquete','delete',
		'descartar', 'alert', 'user_alert');
		//post[numericos]
		$this->numeric_post = array('direccion_permisos','paquete','ussr','telefono',
		'aleatorio','provincia','municipio','tipo_inmueble','subtipo_inmueble',
		'superficie','banos','precio','habitaciones',);

	}



	//funcion para comparar el host
	//===============================================
	private function seguridad_form($url) {
		$url = str_replace('http://', '', $url);
		$url = explode('/', $url);
		if($url[0] == $_SERVER['HTTP_HOST']) {
			return true; 
		}else{		
			return false;
	}	}

	//funcion de ip_encriptada (para formularios)
	//===============================================
	private function seguridad_form_post($ip_encriptada) {
		if($ip_encriptada == md5($_SERVER['REMOTE_ADDR'])) {
			return true;
		}else{			
			return false;		
	}	}	

	//verifica que token recibido por sesion es bueno (para formularios)
	//===============================================
	private function seguridad_form_session($num) {
		if($num == $_SESSION['invisible']['token_key']) {
			$hash = hash('sha512', $num);
			if($hash == $_SESSION['invisible']['token_value']){
				return true;
			}
		}
		return false;
	}

//verificando session
//=====================================================================

	//si hay token verifica sino crea uno
	//===============================================
	private function check_token(){
		//verificamos un token existente
		if(!empty($_SESSION['invisible']['token_key'])){
			if($this->seguridad_form_session($_SESSION['invisible']['token_key']) != true){
				$this->report = 'token incorrecto';
				$this->report_key = $key;
				return false;
			}else{
				return true;
			}
		}else{
			//si no tiene token sera nueva entrada, se crea
			$aleatorio = rand(1, 999999999);
			$_SESSION['invisible'] = array();
			$_SESSION['invisible']['token_key'] = $aleatorio;
			$_SESSION['invisible']['token_value'] = hash('sha512', $aleatorio);
			return true;
		}	
	}

	//filtrara las sesiones con una lista blanca
	//==================================================
	private function check_session(){
		foreach ($_SESSION as $key => $value) {
			if(!in_array($key, $this->white_sesion)){
				$this->continue = false;
				$this->report = 'session desconiocida';
				$this->report_key = $key;
				var_dump('fallo aqui check_session');die;
				return false;
		}	}
		return true;
	}	

	//session pasara la prueba o no?
	//=========================================
	private function last_check_session(){
		$f1 = $this->check_token();		//comprobara token sea correcto
		$f2 = $this->check_session();		//pasara cada sesion por lista blanca

		if( ($f1 == true) && ($f2 == true) ){
			$this->filtro_session = true;
		}
	}

//verificando post
//=====================================================================

	//en form, verifica que esten y que sean correctos
	//======================================================
	private function check_form(){
		if( (empty($_POST['ip_enc'])) || (empty($_POST['aleatorio'])) ){
			//echo '<script>alert("FALTA ALGUN POST")</script>';
			$this->continue = false;
			$this->report = 'error en check_form 1';
			$this->report_key = 'falta un post imprescindible';
			return false;
		}else{
			$v1 = $this->seguridad_form($_SERVER['HTTP_REFERER']);		//referer
			$v2 = $this->seguridad_form_post($_POST['ip_enc']); 		//ip
			$v3 = $this->seguridad_form_session($_POST['aleatorio']);	//token
			if(($v1 == true) && ($v2 == true) && ($v3 == true)){
				//borro ambos valores para no crear error en db
				unset($_POST['ip_enc']);
				unset($_POST['aleatorio']);
				//unico camino valido
				return true;
			}else{
				$this->report = 'error en check_form 2';
				$this->report_key = $v1.' - '.$v2.' - '.$v3;
				return false;
	}	}	}

	//chequea con listas negras
	//===============================================	
	private function check_post($filtro) {
		//abro cada post y paso cada valor por filtro
		foreach($_POST as $key => $value){
			foreach($filtro as $value2){

				//si aun es un array hay que abrirle
				if(is_array($value)){
					foreach ($value as $key3 => $value3) {
						if(stripos($value3, $value2) !== false){
							$_POST[$key][$key3] = str_ireplace($value2, "", $_POST[$key][$key3]);
							$this->descartes++;
							$this->descartados .= ' - '.$value2;
							return false;
					}	}
				}else{
					if(stripos($value, $value2) !== false){
						$_POST[$key] = addslashes(str_ireplace($value2, "", $_POST[$key]));
						$this->descartes++;
						$this->descartados .= ' - '.$value2;
						return false;
				}	}
		}	}
		//var_dump($this->descartes);
		return true;
	}	

	//comprueba si debe ser numerico que lo sea
	//==============================================
	private function numeric_post(){
		//var_dump($_POST);
		foreach ($_POST as $key => $value) {
			if(in_array($key, $this->numeric_post)){
				if( (!is_numeric($value)) && (!empty($value)) ){
					$this->report = 'dato debe ser numerico';
					$this->report_key = $key;
					return false;
		}	}	}
		return true;
	}

	// //filtramos POST con las tres listas negras
	// //===============================================	
	private function checkeator(){
		//var_dump($this->contador);
		if($this->contador >= 2){
			return false;
		}
		$checker = array();
		$this->contador++;	//sumamos al contador cada vez que pasa por aqui	
		$checker['1'] = $this->check_post($this->sql_restrict);
		$checker['2'] = $this->check_post($this->fun_restrict);
		$checker['3'] = $this->check_post($this->code_restrict);
		foreach ($checker as $key => $value) {
			if($value != true){
				$this->report = 'mota detectada en post';
				$this->report_key = $this->contador.' -'.$this->descartados.'_'.$key;
				//return false;
				return $this->checkeator();
			}
		}
		//encontro todos true
		return true;
	}
	
	//prueba superada para post
	private function last_check_post(){
		// var_dump('last_check_post');
		$this->filtro_post = false;		//si pasa los filtros lo haran true
		$f1 = $this->numeric_post();	//comprueba sea numerico
		$f2 = $this->check_form();		//si recibimos un formulario debe estar autorizado
		$f3 = $this->checkeator();		//filtrara post, si encuentra error filtrara denuevo hasta 3 veces

		if(($f1 == true) && ($f2 == true) && ($f3 == true)){
			$this->filtro_post = true;
		}else{
			// var_dump($f1);
			// var_dump($f2);
			// var_dump($f3);
		}
	}

//verificando get
//=====================================================================

	//comprueba si debe ser numerico que lo sea
	//==============================================
	private function numeric_get($key,$value){
		
			if(in_array($key, $this->numeric_get)){
				if(!is_numeric($value)){
					$this->filtro_get = false;
					$this->report = 'dato debe ser numerico';
					$this->report_key = $key;
			}	}
	}

	//filtrara key con lista blanca y value con lista negra
	//===============================================
	//(white_get desactivado)
	private function test_get(){
		foreach ($_GET as $key => $value) {
			//if(in_array($key, $this->white_get)){
				$this->numeric_get($key,$value);
				$this->check_get($value, $this->sql_restrict);
				$this->check_get($value, $this->fun_restrict);
				$this->check_get($value, $this->code_restrict);
			//}else{
				//get no esta en lista blanca pero le dejo pasar
				//(no borrar nada)
				// $this->report = 'no get in whitelist';
				// $this->report_key = $key;
				// $this->filtro_get = false;
				// break;
			//}
		}
		//si los filtros no la convierten en false es que pasa las pruebas
		if(is_null($this->filtro_get)){	
			$this->filtro_get = true;
		}
	}
	//filtrara cada get recibido con su lista negra
	//===============================================
	private function check_get($value, $filtro){
		foreach($filtro as $value2){
			if(stripos($value, $value2) !== false){
				$this->report = 'get in blacklist';
				$this->report_key = $value;
				$this->filtro_get = false;
				break;
		}	}
	}





	//funcion definitiva devolvera true si todos los filtros pasan ok
	public function police_man(){
		//post
		if($_POST){
			// var_dump($_POST);
			$this->last_check_post();	
			// var_dump($this->descartes);
			// var_dump($this->contador);
			// var_dump($this->report);
			// var_dump($this->report_key);
			// var_dump($_POST);
			// die;
		}else{			$this->filtro_post = true;	}
		//get
		if($_GET){		$this->test_get();		
		}else{			$this->filtro_get = true;	}
		//session
		$this->last_check_session();

		if(is_null($this->continue)){
			if(($this->filtro_post == true) && 
				($this->filtro_get == true) && 
				($this->filtro_session == true)){

				return true;
			}else{
				return false;
			}	
		}else{
			return false;
		}

	}








}

?>