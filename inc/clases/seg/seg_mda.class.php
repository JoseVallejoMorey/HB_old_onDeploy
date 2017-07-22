<?php

//include_once 'Seg_actions.class.php';
class Seg_mda extends Seg_sesion{
	
	
	//constructor 
	public function __construct(){	
			parent::__construct();
	}
	
	//comprueba intentos anteriores mda
	public function checkbruteMda($user) {
	   //Obtén timestamp en tiempo actual
	   $now = time();
	   //Todos los intentos de inicio de sesión son contados desde las 2 horas anteriores.
	   $valid_attempts = $now - (2 * 60 * 60);
	   
	   $this->where('user_id', $user);
	   $this->where('time',array('>' => $valid_attempts));
	   if($this->get('mda_login_attempts', NULL ,'time')== FALSE){
		   
		   //echo 'ES FALSE, NO HAY INTENTOS REGISTRADOS';
		   return TRUE;
		}else{
			//email
			//echo 'ES TRUE, HAY INTENTOS REGISTRADOS';
			return $this->count;
			}
	}
	
	//registra errores de login mda
	public function error_reportMda($user, $error){
		//var_dump($this);
		$data = array ('user_id' => $user,'time' => $this->now, 'cause' => $error);
		//$this->where('id', 1);
		$this->insert('mda_login_attempts', $data); 
		
	}


	//login2
	public function login2(){
	
		$error ='';
		$remote_ip = $_SERVER['REMOTE_ADDR'];

		$required_fields = array('email', 'password');
		if($this->campos_requeridos($required_fields,true) == true){
			$this->validar_email();		//email valido

		}else{
			return $this->error;		//deben completarse los campos requeridos
		}
		//var_dump($_POST['password']);
		
		if(is_null($this->error)){
			$params = array($_POST['email']);
			if($users = $this->rawQuery("SELECT id, nombre, pass, salt FROM mda_user WHERE email = ? LIMIT 1", $params)){
				foreach($users as $value){
					$mda_id 	  = $value['id']; 
					$username     = $value['nombre'];
					$db_password  = $value['pass'];
					$salt 		  = $value['salt'];
				}
				
				$password = hash('sha512', $_POST['password'].$salt); //Hash de la contraseña con salt única.

				$brute = $this->checkbruteMda($remote_ip);
				if( $brute != TRUE){
					if($brute > 5 ){
						//echo'si hay mas de 5 intentos banear ip';
						$error = $this->errores[1];
						$this->error_reportMda($remote_ip, $error);
						return $error;
					}
				}else{
					//contraseña en db coincide con la que el usuario envió.puede continuar
					if($db_password == $password) { 
						
						$user_id = 105;
						$tipo_usuario = 'mda';
						$mda_browser = $_SERVER['HTTP_USER_AGENT']; //Obtén el navegador del usuario
						$user_ip 	 = $_SERVER['REMOTE_ADDR']; //direccion ip del usuario

						//conexion a users_db
						$this->where('id',$user_id);
						$w = $this->getOne('usuarios','pass');
						$login_string 	= hash('sha512', $w['pass'].$mda_browser);
						$sesion_string 	= hash('sha512', $mda_browser.$user_ip.$user_id);
						$mda_id = preg_replace("/[^0-9]+/", "", $mda_id); //protección XSS 
						$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); //protección XSS 

						//valores de user normal
						$_SESSION['tipo'] 	  = $tipo_usuario;
						$_SESSION['user_id']  = $user_id; 						
						$_SESSION['username'] = $username;						
						$_SESSION['login_string'] = $login_string;

						//valores de user mda
						$_SESSION['mda_id']  = $mda_id;
						$_SESSION['mdaname'] = $username;
						$_SESSION['mda_login_string'] = hash('sha512', $password.$mda_browser);
						
						//Registro en db de usuarios online
						//no la registro puesto que ya existe una
						//$this->new_sesion($user_id, $tipo_usuario, $sesion_string);

						//Inicio de sesión exitosa
						//header('location:index.php?perfil_mda=4'); 
							
					}else{
						//Grabamos este intento en la base de datos
						$error = $this->errores[5];	
						$this->error_reportMda($remote_ip, $error);
						return $error;
					}
				}//checkbrute
			
			
			}else{
				//echo'no se encuentra en tabla';
				$error = $this->errores[6];	
				$this->error_reportMda($remote_ip, $error);
				return $error;
			}
		}else{
			//hay error
			$this->error_reportMda($remote_ip, $error);
			return $error;
		}
	}//fin de login2


}






?>