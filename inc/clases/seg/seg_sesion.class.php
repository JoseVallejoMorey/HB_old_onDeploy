<?php

require_once 'inc/clases/seg/seg_actions.class.php';

class Seg_sesion extends seg_actions{
	


	public function __construct(){

		parent::__construct();

	}

	


	public function login_check() {
	   //Revisa si todas las variables de sesión están configuradas.
		//añadir: si no encuentra session en db se le kikkea
	//var_dump('hago login_check');
	   $error = '';
	   $login_check = '';
	   $login_string = '0';
	   //declaro variables si estan todas, sino error
		if((!empty($_SESSION['user_id'])) && (!empty($_SESSION['username'])) && (!empty($_SESSION['login_string']))) {
			 $user_id 	   = $_SESSION['user_id'];
			 $login_string = $_SESSION['login_string'];
			 $username 	   = $_SESSION['username'];
			 $user_browser = $_SERVER['HTTP_USER_AGENT']; //Obtén la cadena de caractéres del agente de usuario
		}else{

			$error = 1;
		}
		//verifico que usuario existe
		$this->where('id',$user_id);
		if($salida = $this->getOne('usuarios', 'pass')){
			$password    = $salida['pass'];
			$login_check = hash('sha512', $password.$user_browser);
		
		}else{
			$error = 2;
		}
		
		if($login_check == $login_string) {
			//loguin check ok, queda comprobar que este en tabla
			$user_ip 		= $_SERVER['REMOTE_ADDR'];
			$salt = hash('sha512', $user_browser.$user_ip.$user_id);
			$this->where('salt',$salt);
			//var_dump($salt);
			if(!$salida = $this->getOne('stats_users_online')){
				$error = 4;
			}

		}else{
			$error = 3;
		}

		if($error != ''){
			 // var_dump('chafarrancho');
			// var_dump($login_check);
			// var_dump($login_string);
			 // var_dump($error);
			 // die;
			cerrar_sesion();
			//echo $error.'-login_check()';
			//var_dump('session catapuf');
			//die;
			return $error;
		}else{
			//var_dump($_SESSION);
			//actualizo estado
			$this->revisar_sesiones($salt);
			$this->update_sesion($salt);
			return true;
		}
	
	}//fin login_ceck

	//sera llamada en login_check
	private function update_sesion($salt){

		//actualizara estado de session
		$cols = array('ultimo_movimiento' => $this->now,
					  'ultima_uri' => $_SERVER['REQUEST_URI']);
		$this->where('salt',$salt);
		if($this->update('stats_users_online',$cols)){
			return true;
		}

	}

	//sers llamada al loguearse
	protected function new_sesion($user, $tipo, $salt){

		//registrara los datos de la nueva sesion que se ha creado en tabla
		$sesion_cookie = hash('sha512', $_SERVER['HTTP_COOKIE']);
		$cols = array('user' 	=> $user,
					  'ip' 		=> $_SERVER['REMOTE_ADDR'],
					  'salt' 	=> $salt,
					  'navegador' 	=> $_SERVER['HTTP_USER_AGENT'],
					  'iniciada' 	=> $this->now,
					  'tipo_usuario' 	  => $tipo,						  
					  'ultimo_movimiento' => $this->now);
		if($this->insert('stats_users_online',$cols)){
			//añadir una mas a stats_users_conexiones
			// $this->una_conexion_mas($user);
			$this->iniciar_entrada($user);	//actualiza datos
			
			return true;
			//die(var_dump('se ha creado una nueva session en db'));
		}else{
			return false;
			//die(var_dump('no se esta registrando session en db'));
		}
	}
	
	//actualizamos en stats_user_conections
	//=================================
	protected function iniciar_entrada($user){
		
		$this->where('user',$user);
		$salida = $this->getOne('stats_user_conections','total_conexiones');
		$total_conexiones = $salida['total_conexiones']+1;

		$data = array ('total_conexiones' => $total_conexiones,'ultima_conexion' => $this->now);
		$this->where('user',$user);
		$this->update('stats_user_conections', $data); 
	}		

	//mete en db modo de desconexion de la session; kikked, afk, cerrada
	private function report_sesion($id_conexion, $cause){
		$this->where('id_conexion', $id_conexion);
		//aqui actualizamos, el modo de cierre de sesion
		if($salida = $this->getOne('stats_total_conections','id')){
			$campos = array('cierre' => $cause);
			$this->where('id',$salida['id']);
			$this->update('stats_total_conections',$campos);
		}
	}

	//calcula la cantidad de segundos entre una fecha y otra (datetime)
	private function contabilizar_sesion($inicio, $final){
		$segundos = strtotime($final) - strtotime($inicio);
		return $segundos;
	}

	//suma la cantidad de segundos a la total del usuarios
	private function sumar_tiempo_online($user,$duracion){
		$this->where('user',$user);
		$cols = array('id', 'tiempo_online');
		if($salida = $this->getOne('stats_user_conections', $cols)){

			$duracion = $salida['tiempo_online'] + $duracion;
			$campos=array('tiempo_online' => $duracion);
			$this->where('id',$salida['id']);
			$this->update('stats_user_conections', $campos);
		}			
	}


	//creara un salt unico para cada usuario
	public function make_salt(){
		$user_browser 	= $_SERVER['HTTP_USER_AGENT']; //Obtén el agente de usuario del usuario
		$user_ip 		= $_SERVER['REMOTE_ADDR']; //direccion ip del usuario
		$salt 			= hash('sha512', $user_browser.$user_ip.$this->user);

		return $salt;
	}

	//mediante el salt generado encontrara la sesion que debe cerrar
	public function search_and_destroy($salt = NULL, $motivo){
		if(!isset($salt)){
			$salt = $this->make_salt();
		}
		
		
		//buscar si hay ese salt y eliminar bicho
		$this->where('salt',$salt);
		if($salida = $this->get('stats_users_online', NULL, 'id')){
			foreach ($salida as $value) {
				//var_dump('iban a matarte muchacho, nos necesitas');
				// var_dump($value['id']);
				// die;
				$this->delete_sesion($value['id'], $motivo);

				//el problema es que al borrar la que esta en db tambien me esta borrando 
				//la sesion actual...
				// var_dump($_POST);
				// die(var_dump('te estoy tirando porque esto es una locura.'));
			}
		}
	}

	//eliminamos de users_online y guardamos en historial
	public function delete_sesion($deleteid, $cause){
		//var_dump($this);
		//primero sacamos info de usars_online y preparamos datos
		$this->where('id',$deleteid);
		if($salida = $this->getOne('stats_users_online')){
			$user 		= $salida['user'];
			$sesion_id 	= $salida['id']; 
			$sesion_iniciada   = $salida['iniciada'];
			$sesion_ultima_uri = $salida['ultima_uri'];
			$duracion = $this->contabilizar_sesion($sesion_iniciada,$this->now);
			//preparamos e ingresamos informacion en historial de sessiones
			$cols = array('user' 			=> $user,
						  'id_conexion'		=> $sesion_id,
						  'duracion' 		=> $duracion,
						  'time_close' 		=> $this->now,
						  'ultima_uri' 		=> $sesion_ultima_uri);
			if($this->insert('stats_total_conections',$cols)){
				//copiada la informacion con exito borramos de users_online
				$this->where('id',$sesion_id);
				$this->delete('stats_users_online');
			}
			$this->reset();
			$this->report_sesion($sesion_id, $cause);
			//suma la cantidad de segundos a la total del usuario
			$this->sumar_tiempo_online($user,$duracion);		
		}

	}


	//si llevan 20 minutos afk se desconecta al usuario
	//sera llamada al loguear un usuario
	protected function revisar_sesiones($salt){

		//var_dump($this->now);
		//var_dump(date('Y-m-d H:i:s', strtotime('-20 minutes')));
		$fecha_caducidad = date('Y-m-d H:i:s', strtotime('-40 minutes'));


		//el ultimo movimiento fue hace mas de 20 minutos
		$this->where('ultimo_movimiento', array('<=' => $fecha_caducidad));
		if($salida = $this->get('stats_users_online', NULL, 'id')){
			//si los hay hay que cerrarlos
			// var_dump($salida);
			// die;
			foreach ($salida as $value) {
				$this->delete_sesion($value['id'], 'afk-kikked');
			}
		}

	}



}







?>