<?php


/**
* user se quiere dar de baja porque es un mohon
*/
//include_once 'Seg_actions.class.php';

class seg_baja extends seg_actions{
	
	private $Master;

	function __construct(){
		parent::__construct();

		$this->Master = new Master();
		$this->Session = new Seg_sesion();
	}




	public function confirmacion_baja(){
		if(!empty($_GET['validation'])){
			if($this->validacion_codigo_baja($_GET['validation']) == true){
			//codigo valido continuamos	
				$this->where('code',$_GET['validation']);
				if($salida = $this->getOne('users_baja_tramite','user')){
					$user = $salida['user'];

					//1-cerrar session del user
					$this->Session->delete_sesion(null, 'nos deja');
					cerrar_sesion();

					//2-
					$this->Master->exterminar_usuario($user);
					//3-borrar de tramite_baja donde user
					$this->limpiar_tramites_baja($user);
					
					$msj = 'Baja efectuada con exito. Hasta pronto.';

				}else{
					$msj = 'No se pudo continuar.';
				}
			}else{
				$msj = 'No se pudo continuar.';
			}
		}else{
			$msj = 'No se pudo continuar.';
		}	
		//mostramos resultado al usuario
		return $this->show_mensaje($msj);
	}







//validacion de un codigo de baja
public function validacion_codigo_baja($codigo){

	$code_generado = '';
	
	$this->where('code',$codigo);
	if($salida = $this->getOne('users_baja_tramite')){
		$code_reservado = $salida['code'];

		$this->where('id',$salida['user']);
		if($salida2 = $this->getOne('usuarios')){
			$code_generado = $this->generar_codigo_baja($salida2);
		}
		if($code_reservado == $code_generado){
			//user que reservo y user actual son el mismo
			return true;
		}
	}
}

//limpiara de tramites caducados o terminados
public function limpiar_tramites_baja($user = NULL){
	if(!is_null($user)){
		$this->where('user',$user);
	}else{
		$fecha_caducidad = date('Y-m-d H:i:s', strtotime('-40 minutes'));
		$this->where('date', array('<=' => $fecha_caducidad));
	}
	//user o todo el que lleva mas de 40 minutos
	$this->delete('users_baja_tramite');
}

//codigo sera(email.fecha_alta.navegador.salt)
public function generar_codigo_baja($sa){
	$code = hash('sha512', $sa['user_email'].$sa['fecha_alta'].$_SERVER['HTTP_USER_AGENT'].$sa['salt']);
	return $code;
}

//guardara en una tabla cualquier error producido en el proceso
private function baja_error_report($cause){
	$campos = array('user' 	  => $this->user,
					'fecha'   => $this->now,
					'mensaje' => $cause);
	$this->insert('users_baja_attempts', $campos);
}

private function baja_encuesta($tipo){
	$cols = array('user' => $this->user,
				  'tipo_user' => $tipo,
				  // 'causa' => $_POST['causa'],
				  // 'valoracion' => $_POST['valoracion'],
				  // 'comentario' => $_POST['comentario'],
				  'fecha_baja' => $this->date);
	$this->insert('users_baja',$cols);
}

private function baja_particular(){
	$cols = array('user' => $this->user,
				  'tipo_user' => 'particular',
				  'date' => $this->date);
	$this->insert('users_baja_tramite',$cols);

}

private function baja_empresa($sa){
	$codigo = $this->generar_codigo_baja($sa);
	$cols = array('user' => $this->user,
				  'tipo_user' => 'empresa',
				  'code' => $codigo,
				  'date' => $this->date);
	$this->insert('users_baja_tramite',$cols);
	return $codigo;
}


	public function tramitar_baja(){

		$this->validar_passwords();		//pasword existen y coinciden
		//si no hay ningun error continuamos
		if(is_null($this->error)){
			if(!$user = $this->id_from_salt($_POST['saltador'])){
				$this->baja_error_report('no se encuentra saltador');
				return $this->errores[10];
			}
			$this->where('id',$user);
			if($salida = $this->getOne('usuarios')){
				//comprobar que la contraseña es la suya
				$pass_generado = hash('sha512', $_POST['password'].$salida['salt']);
				if($salida['pass'] == $pass_generado){

					if($salida['tipo_usuario'] == 'particular'){
						$this->baja_particular();
						$this->baja_encuesta('particular');
					}else{
						$codigo = $this->baja_empresa($salida);
						$this->baja_encuesta('empresa');

						//return $codigo;
						//enviara esto por email
					}
					
					//en edte punto supongo es donde le envio un email con dicho codigo

				}else{
					$this->baja_error_report('generados no coinciden');
					return $this->errores[10];
				}
			}
		}else{
			$this->baja_error_report($this->error);
			return $this->errores[10];
		}
	}










}

?>