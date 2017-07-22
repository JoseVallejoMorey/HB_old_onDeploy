<?php


class login extends seg_sesion{


	public function __construct(){
		parent::__construct();
	}

	//comprueba intentos anteriores de login
	public function checkbrute($user) {
	   //Obtén timestamp en tiempo actual
	   $now = time();
	   //Todos los intentos de inicio de sesión son contados desde las 2 horas anteriores.
	   $valid_attempts = $now - (2 * 60 * 60);
	   $this->where('user_id', $user);
	   $this->where('time',array('>' => $valid_attempts));
	   if($this->get('login_attempts', NULL ,'time')== FALSE){
		   //echo 'ES FALSE, NO HAY INTENTOS REGISTRADOS';
		   return TRUE;
		}else{
			return $this->count;
			}
	}
	
	//registra errores de login
	public function error_report($user, $error){
		//var_dump($this);
		$data = array ('user_id' => $user,'time' => $this->now, 'cause' => $error);
		//$this->where('id', 1);
		$this->insert('login_attempts', $data); 
	}


	//login de usuario
	//===========================================================================
	public function login(){
	
		$error ='';
		$remote_ip = $_SERVER['REMOTE_ADDR'];
		
		$required_fields = array('email', 'password');
		if($this->campos_requeridos($required_fields,true) == true){
			$this->validar_email();		//email valido

		}else{
			return $this->error;		//deben completarse los campos requeridos
		}

		//var_dump($this->error);
		if(is_null($this->error)){
			
			$params = array($_POST['email']);
			if($users = $this->rawQuery("SELECT id, user_nombre, pass, salt, tipo_usuario, verificado FROM usuarios WHERE user_email = ? LIMIT 1", $params)){

				foreach($users as $value){
					$verificado	  = $value['verificado'];
					$user_id 	  = $value['id']; 
					$username     = $value['user_nombre'];
					$db_password  = $value['pass'];
					$salt 		  = $value['salt'];
					$tipo_usuario = $value['tipo_usuario'];
				}
				
				//verificamos que el usuario haya validado su email
				if( $verificado != '1'){
					$error = $this->errores[8];
					$this->error_report($user_id, $error);
						return $error;
				}

				$password = hash('sha512', $_POST['password'].$salt); //Hash de la contraseña con salt única.
				// var_dump($password);
				// die;
				$brute = $this->checkbrute($user_id);
				if( $brute != TRUE){
					if($brute > 5 ){
						//echo'sacar error';
						$error = $this->errores[1];
						$this->error_report($user_ip, $error);
						return $error;
					}
				}else{
					if($db_password == $password) { //contraseña en db coincide con la que el usuario envió.
						//¡La contraseña es correcta!
						//==============================================
						//antes de crear la nueva sesion miraremos que ese user no este ya conectado
						$this->where('user',$user_id);
						if($existe = $this->getOne('stats_users_online')){
							$salt_antiguo = $existe['salt'];
							$this->user = $user_id;
							//eliminamos la conexion antigua para conectar al nuevo usuario
							$this->search_and_destroy($salt_antiguo, 'conexion-duplicada');
						}
						//no se esta eliminando la antigua

						
						$user_browser 	= $_SERVER['HTTP_USER_AGENT']; //Obtén el agente de usuario del usuario
						$user_ip 		= $_SERVER['REMOTE_ADDR']; //direccion ip del usuario
						$login_string 	= hash('sha512', $password.$user_browser);
						$sesion_string 	= hash('sha512', $user_browser.$user_ip.$user_id);
						$tipo_usuario 	= preg_replace("/[^a-zA-Z0-9_\-]+/", "", $tipo_usuario); //protección XSS 
						$user_id 		= preg_replace("/[^0-9]+/", "", $user_id); //protección XSS 
						$username 		= preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); //protección XSS 
						
						//Inicio de sesión exitosa
						$_SESSION['tipo'] = $tipo_usuario;
						$_SESSION['user_id'] = $user_id;
						$_SESSION['username'] = $username;	
						$_SESSION['login_string'] = $login_string;
						
						//registro de usuario online
						if($this->new_sesion($user_id, $tipo_usuario, $sesion_string) !== true){
							//var_dump('hay error en esto');
						}else{
							//var_dump('entoncedsx que coño pasa?');
						}
						
						$this->movimientoStats('Login',$user_id);
						//revision de usuarios afk
						$this->revisar_sesiones($sesion_string);
						//desviamos al user a perfil
						//wowowowowo puede ser aqui, en lugar de header haremos otra cosilla luego
						

						header('location:index.php?perfil=1'); 
							
					}else{
						//Grabamos este intento en la base de datos
						$error = $this->errores[5];	
						$this->error_report($user_id, $error);
						return $error;
					}
				}//checkbrute
			}else{
				//echo'no se encuentra en tabla';
				$error = $this->errores[6];	
				$this->error_report($remote_ip, $error);
				return $error;
			}
		}else{
			//hay error
			$this->error_report($remote_ip, $error);
			return $error;
		}
	}//fin de login





}






?>