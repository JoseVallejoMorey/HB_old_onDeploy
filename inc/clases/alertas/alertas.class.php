<?php

//aqui vienen funciones para creacion de alertas

class Alertas extends MysqliDb {

	public $user;
	public $now;
	public $date;
	public $base_name;

	private $last_id;
	public $alerta;
	private $saltador;


	
	public function __construct(){

		$this->now = date('Y-m-d H:i:s');
		$this->date = date('Y-m-d');
		$this->base_name = 'alertas';
		
		if(!empty($_POST['ip_enc'])){		unset($_POST['ip_enc']);			}
		if(!empty($_POST['aleatorio'])){	unset($_POST['aleatorio']);			}
		if(!empty($_POST['lang_form'])){	unset($_POST['lang_form']);			}
		if(!empty($_POST['privatepol'])){	unset($_POST['privatepol']);			}

		if(!empty($_POST['alerta'])){		$this->alerta = $_POST['alerta'];	
											unset($_POST['alerta']);			}
		if(!empty($_POST['saltador'])){		$this->saltador = $_POST['saltador'];	}
											//unset($_POST['saltador']);				

		parent::__construct($this->base_name);
		//parent::__construct();
		
	}	
	

	//registrar alertas en tabla
	//=====================================
	public function agregar_alert_db($tabla,$Usuario){
		$campos = array();
		foreach($_POST as $key => $value){
			if($key == 'form_to') {unset($_POST[$key]);	}
			if( ($value == 0) || ($value == '') ) {unset($_POST[$key]);	}
		}	
		foreach($_POST as $nombre_post => $valor_post) {
			if(is_array($valor_post)) {
				$campos[$nombre_post] = implode(',',$valor_post);
			}else{
				$campos[$nombre_post] = $valor_post;
			}
		}
		if($this->last_id = $this->insert($tabla,$campos)){ 
			if(!is_null($this->user)){
				$actividad = 'nuevo anuncio '.$tabla;
				$Usuario->movimientoStats($actividad);
			}
			return $this->last_id;
		}
	}



	//funcion usade cuando un usuario quiere establecer contacto con una empresa
	//=======================================================
	public function user_contact($Perfil){

		$this->user = $Perfil->id_from_salt($this->saltador);
		
		$cols = array('nombre' => $_POST['nombre'],
					  'email' => $_POST['email'],
					  'telefono' => $_POST['telefono'],
					  'comentario' => $_POST['comentario'],
					  'user' => $this->user,
					  'fecha' => $this->now );

		$this->insert('user_contact',$cols);








		//registrar en db que a "usuario" se le ha enviado un mail "contacto" desde la pagina, fecha
		//esto estara en una tabla que ira aumentando, requerira una funcion de limpieza. dicha funcion de limpieza
		//digamos se ejecuta diariamente y elimina los que tienen mas de 10 dias
		// 1-contara cuantos hay para cada usuario
		// 2-actualizara en cada usuario el numero de emails de ese tipo recibidos(sumara a los que tiene)
		// 3-entonces borrara

		//el nombre email telefono fecha van a otra tabla de recopilacion de emails

		//hay que enviar email al vendedor "mensaje de usuario bla bla bla"
		//hay que enviar email al usuario interesado "su mensaje ha sido enviado con exito al anunciante"


	}


	//funcion cuando un visitante crea una alerta para recibir nuevos anuncios con sus preferencias
	//=======================================================
	public function new_alert($Usuario){

		$_POST['fecha'] = $this->now;
		//los 'all' no los mete
		$this->agregar_alert_db('alertas',$Usuario);
		
		//insertamos nueva alerta en stats (solo crear)
		$alert = $this->last_id;

		$cols = array('alerta' => $alert);
		$this->insert('alertas_stats',$cols);

		//amail al usuario "su alerta ha sido creada pronoto recibira ofertas e informacion de alguna empresa"

	}

	//subscripciones de nuevos anuncios 
	//=======================================================
	public function user_subscribe($Fechas){

		//al llegar aqui se divide lo que llega por post en dos arrays, 
		//que irean a dos tablas distintas
		$cols1 = array('nombre' => $_POST['nombre'],
					  'email' => $_POST['email'],
					  'telefono' => $_POST['telefono'],
					  'idioma' => '',
					  'periodicidad' => $_POST['periodicidad'],
					  'fecha_alta' => $this->date,
					  'proximo_informe' => $Fechas->periodo($this->date, $_POST['periodicidad']),
					  'maximo_informes' => $_POST['maximo_informes']);
		if($this->last_id = $this->insert('alertas_subscripciones',$cols1)){

			//premera tabla implicada creada con exito, continuamos
			$cols2 = array('id_subscripcion' => $this->last_id,
						  'provincia' => $_POST['provincia'],
						  'municipio' => $_POST['municipio'],
						  'tipo_inmueble' => $_POST['tipo_inmueble'],
						  'subtipo_inmueble' => $_POST['subtipo_inmueble'],
						  'tipo_venta' => $_POST['tipo_venta'],
						  'min_precio' => $_POST['min_precio'],
						  'max_precio' => $_POST['max_precio']);
			//quito vacios para quedarme con los que interesan
			foreach ($cols2 as $key => $value) {
				if( ($value == 0) || ($value == '') ){	unset($cols2[$key]);	}
			}
			//inserto en segunda tabla implicada
			$this->insert('alertas_subscripciones_data',$cols2);			
		}

		//success
	}


	//funcion usade cuando 
	//=======================================================
	public function user_especializacion($Usuario){
		//digamos que un usuario define oficinas en inca, y que llega un visitante
		//y publica una alerta con esas coincidencias, entonces el usuario recibira un email avisandole
		//de que hay un interesado en un campo que el domina

		$_POST['fecha'] = $this->now;
		foreach($_POST as $key => $value){
			if(($value == '') || ($value == 0)){  unset($_POST[$key]);  }
		}

		$_POST['user'] = $Usuario->user;
		$this->agregar_alert_db('alertas_preferencias_users',$Usuario);

	}	









}//fin de clase alertas

?>