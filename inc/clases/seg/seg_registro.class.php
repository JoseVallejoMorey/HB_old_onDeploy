<?php

class registro extends seg_actions{

	private $Reserva;		//objeto reserva

	public function __construct(){
		parent::__construct();
		$this->Reserva = new reserva_builder();	//(para agregar paquetes al nuevo user)
	}
	
	//metodos de registro de datos
	//=================================================
	//funcion para validar nif
	//=============================================================
	private function validar_nif($cadena){
		$letras = 'T R W A G M Y F P D X B N J Z S Q V H L C K E';
		$letra = explode(' ',$letras);
		if (strlen($cadena) ==9){
			$cadena = substr_replace($cadena, '-', 8, -1);
			//antes hay que separar numeros y letras con -
			if($monos = explode('-',$cadena)){
				$letra_descubierta = $monos[0] % 23; 
				if($letra[$letra_descubierta] == strtoupper($monos[1])){
					return true;	
	}	}	}	}

	//incluye todas las verificaciones de empresa
	//=============================================================
	private function validar_empresa(){
		if($_POST['tipo_usuario'] == 'empresa') {
			//validacion de empresa_telefono
			$this->validar_telefono($_POST['empresa_telefono']);
			//validacion de nif
			if($this->validar_nif($_POST['nif']) != true){
				$this->error = $this->errores[12];
			}
			//-verifica que no exista ese telefono de empresa en perfiles_emp
			$this->existe_telefono('perfiles_emp','empresa_telefono',$_POST['empresa_telefono']);
			//4.1-verifica que no exista ese NIF, en perfiles_emp
			$this->existe_nif();	
		}
	}


	//verifica que no exista ese NIF, en perfiles_emp
	//=============================================================
	private function existe_nif(){
		$this->where('nif',$_POST['nif']);
		if($salida = $this->getOne('perfiles_emp')){
				$this->error = $this->errores[15];
		}	
	}



	//registro del usuario en banners catalogo
	//=============================================================
	private function reg_banners_catalogo(){
		$cols = array('user' 	 => $this->last_id,
					  'superior' => 0,
					  'lateral'  => 0,
					  'central'  => 0);
		$this->Reserva->insert('banners_catalogo', $cols);	
	}

	//registro del usuario en empresa_fondo
	//=======================================================
	private function reg_empresa_fondo(){
		$cols = array('id' 	 => $this->last_id,
					  'img_fondo' => 'generica.jpg');
		$this->insert('empresa_fondo', $cols);	
	}

	//registro del primer paquete del usuario
	//=============================================================
	private function reg_paquete($id,$paquete){
		$nuevafecha = strtotime ( '+6 month' , strtotime ($this->date) ) ;
		$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
					
		$cols = array('user' 	=> $id,		  'paquete_inicial' => 1,
					  'paquete' => $paquete,  'fecha_inicio' 	=> $this->date,
					  'full' 	=> 0, 		  'fecha_final'  	=> $nuevafecha,
					  'estado'  => 1,		  'duracion'		=> 6);

		$this->Reserva->insert('paquetes',$cols);
	}

	//registro del usuario en registro de actividades
	//=============================================================
	private function reg_registro(){
		$cols = array('user' => $this->last_id,
					  'fecha_alta' => $this->now,
					  'ultima_conexion' => $this->now,
					  'ultima_actividad' => 'Registro');
		$this->insert('stats_user_conections', $cols);
	}


	//registra un nuevo usuario y crea todo lo necesario
	//===========================================================================
	public function regUser(){
		//defino campos requeridos
		$required_fields = array('nombre','email','tipo_usuario','telefono','password','password2');
		//si todos los campos requeridos estan continuamos
		if($this->campos_requeridos($required_fields,true) == true){
			$this->validar_email(true);						//email valido y no existe(true)
			$this->validar_passwords();					//pasword existen y coinciden
			$this->validar_telefono($_POST['telefono'],true);	//telefono valido y no existe
			$this->validar_empresa();						//es empresa, verificaciones extra
		}else{
			return $this->error;					//deben completarse los campos requeridos
		}

		if(is_null($this->error)){

			$email    = $_POST['email'];
			$tipo 	  = $_POST['tipo_usuario'];
			$password = $_POST['password'];
			$tel	  = $_POST['telefono'];

			//5-encriptado de contraseña (Cuidado de no pasarte)
			$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
			$password 	 = hash('sha512', $password.$random_salt);
			//datos que van a tabla usuarios
			$campos = array('salt'	   => $random_salt, 	'ip' 		   => $_SERVER['REMOTE_ADDR'],
						  	'pass' 	   => $password,		'tipo_usuario' => $tipo,
						  	'user_telefono' => $tel,		'user_email'  	   => $_POST['email'],
						  	'user_nombre'   => $_POST['nombre'], 'fecha_alta'   => $this->now);

			//6-si inserto el nuevo user en usuarios
			$this->last_id = $this->insert('usuarios',$campos);

			if(!is_null($this->last_id)){
				//aqui ha registrado al usuario en tabla usuarios
				
				if($tipo == 'particular'){ 	 					
					//si se registra como particular
					$paquete = 1;
					$tabla_destino = 'perfiles_par';
					$cols2 = array('id' =>  $this->last_id);
				}else if($tipo == 'empresa'){
					//si se registra como empresa
					$paquete = 10;
					$tabla_destino = 'perfiles_emp';
					//datos que van a tabla empresas
					$cols2 = array('id' 			  => $this->last_id,
								   'empresa'		  => $_POST['nombre_empresa'],
								   'empresa_telefono' => $_POST['empresa_telefono'],
								   'tipo_empresa' 	  => $_POST['tipo_empresa'],
								   'nif' 			  => $_POST['nif'],
								   'user_telefono'	  => $tel);
					
					$this->reg_banners_catalogo();//7-empresa, fila en banners_catalogo
					$this->reg_empresa_fondo();		//fila en empresa_fondo
				}
				
				$this->insert($tabla_destino, $cols2);			//8-reparte a particulares o  empresa 
				$this->reg_paquete($this->last_id,$paquete);	//9-reg primer paquete de anuncios del user
				$this->reg_registro();							//10-estadisticas de conexion
				
				$_SESSION['new_user'] = $this->last_id;			//11 final
				
				//y le enviamos email de verificacion siiiii
				header('location:index.php?accion=val&validation='.$random_salt);
				
			}else{
				return $this->errores[10];
			}
		}else{
			return $this->error;
		}

	}//fin regUser




}




?>