<?php

//include_once 'Seg_actions.class.php';

class Seg_lost_pw extends Seg_actions{

	//constructor 
	public function __construct(){
		parent::__construct();
	}


	//2 old_password coinciden (para rewrite) +++++++++
	//=============================================================
	public function validar_old_passwords(){
		if($_POST['old_password'] != $_POST['old_password2']){
			$this->error = $this->errores[16];
		}
	}

	//rewrite_attempts
	protected function rewrite_attempt($user, $error){
		$campos = array('user_id' => $user,
						'time' => $this->now,
						'cause' => $error);
		$this->insert('rewrite_attempts', $campos);
	}	
	

	//generara un codigo para cambiar el pass (nueva)
	//========================================
	public function lostPw(){
		
		$required_fields = array('email','telefono');
		//si todos los campos requeridos estan continuamos
		if($this->campos_requeridos($required_fields,true) == true){
			$this->validar_email();					 	 //validar solo si es correcto
			$this->validar_telefono($_POST['telefono']); //en clase registro, llevar a seg_actions
		}else{
			return $this->error;						 //deben completarse los campos requeridos
		}

		if(is_null($this->error)){
			
			//los datos enviados son validos
			$email = $_POST['email'];
			$telefono = $_POST['telefono'];
			$user_browser = $_SERVER['HTTP_USER_AGENT']; //navegador
			$ip = $_SERVER['REMOTE_ADDR']; //ip
			$macro_sha = hash('sha512', $email.$user_browser.$telefono.$ip);
			
			$cols = array('email' 	  => $email,		'telefono'  => $telefono,
						  'ip' 		  => $ip,			'navegador' => $user_browser,
				  		  'hora' 	  => $this->now,	'macro_sha' => $macro_sha );
			
			//se guarda todo en db y se le envia un email
			$this->insert('solicitudes_clave',$cols);
			
			//comprobamos que existe el email
			$cols = array('id', 'user_email', 'salt', 'user_telefono');
			$this->where('user_email',$email);
			if($salida = $this->getOne('usuarios ',$cols)){
			
				$user_id 	 = $salida['id'];
				$email_db 	 = $salida['user_email'];
				$saltador 	 = $salida['salt'];
				$telefono_db = $salida['user_telefono'];
			
				if($telefono == $telefono_db){
					//guardamos salt del user en solicitudes_clave
					$campos = array('salt' => $saltador);
					$this->where('email',$email_db);
					$this->update('solicitudes_clave',$campos);
						
					//Actualizando movimientos en stats_user_conections
					$this->movimientoStats('Solicitado nuevo password',$user_id);
					
					//generar link y se enviara email
					return '<a href="index.php?accion=los&validation='.$macro_sha.'">Sigua este enlace para continuar 
							  con el reestablecimiento e su contraseña</a>';					
				}else{
					//los datos no son correctos
					return $this->errores[10];
				}
			}else{
				//no existe este email
				return $this->errores[10];
			}
		}else{
			//los datos (telefono y/o email) no son correctos
			return $this->errores[10];
		}
	}





































	
	//validation_sha. valida el codigo que nos envia
	//==========================================
	public function validationSha($code){
		//variables
		$error = '';
		$user_browser = $_SERVER['HTTP_USER_AGENT']; //navegador
		$ip = $_SERVER['REMOTE_ADDR']; //ip
	
		$this->where('macro_sha', $code);
		if($salida = $this->getOne('solicitudes_clave')){
			
			if($salida['navegador'] != $user_browser){	$error = 'navegador no concide';	}
			if($salida['ip'] != $ip){					$error = 'remote ip no concide';	}
			
			if($error != ''){
				$this->rewrite_attempt($ip, $error);
				return false;
			}else{
				$nuevo_sha = hash('sha512', $salida['email'].$user_browser.$salida['telefono'].$ip);
				if($nuevo_sha != $salida['macro_sha']){
					$error = 'Shas no coinciden';
					$this->rewrite_attempt($ip, $error);
					return false;
				}else{
					//succes!!
					$_SESSION['email_val'] = $salida['email'];
					$_SESSION['corrector'] = $salida['salt'];
					
					return true;
				}
			}
		
		}else{
			$error = 'no hubo conexion a db';
			$this->rewrite_attempt($ip, $error);
			return false;
		}
	}//end validation_sha







//escribiendo el nuevo password
//===============================
public function rewrite_pw(){

	$info 	  = array();
	$continue = true;
	$ip 	  = $_SERVER['REMOTE_ADDR']; //ip

	//campos que deben estar
	if(!empty($_SESSION['email_val'])){	$info['email'] = $_SESSION['email_val'];	}
	if(!empty($_SESSION['corrector'])){	$info['salt']  = $_SESSION['corrector'];	}
	if(!empty($_POST['macro_sha'])){	$info['code']  = $_POST['macro_sha'];		}
	//comprobamos si estan  //var_dump($info);
	foreach ($info as $key => $value) {
		if( (empty($value)) || ($value == '') ){
			$continue = false;
	}	}

	//no hay requeridos vacios y el codigo es bueno
	if($continue != false){
		if ($this->validationSha($info['code']) ==true){

			$this->validar_passwords();		//pasword existen y coinciden

			//si no hay ningun error continuamos
			if(is_null($this->error)){

			 	$new_pass = hash('sha512', $_POST['password'].$info['salt']);
				//exito brutal, hay que comunicarlo
				$this->where('email',$info['email']);
				$cols = array('pass' => $new_pass);
				$this->update('usuarios', $cols);
				
				//Actualizando movimientos en stats_user_conections
				$user_id = $this->id_from_salt($info['salt']);
				$this->movimientoStats('Cambiado password',$user_id);
				//header a nuevo destino

			}else{
				//hay que devolver error
				return $this->error;
			}

		}else{
			//falló el codigo de verificacion
			return $this->errores[10];
		}
	}

}





}//fin de clase


?>