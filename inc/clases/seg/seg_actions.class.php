<?php

include_once'seg_builder.class.php';

class Seg_actions extends seg_builder{

	public $error;
	public $errores;
	
	//constructor 
	public function __construct(){
		
		$this->errores = array(
				1  => 'No se puede iniciar cuenta.',
				2  => 'El email ya esta asignado a un usuario.',
				3  => 'Debe completar los campos obligatorios.',
				4  => 'Email no valido.',
				5  => 'Contraseña incorrecta.',
				6  => 'No existe usuario.',
				7  => 'Le hemos enviado un email con su contraseña.',
				8  => 'El usuario no esta verificado. Revise su correo electronico.',
				9  => 'Las contraseñas no coinciden.',
				10 => 'No se pudo continuar',
				11 => 'Telefono no valido',
				12 => 'Nif no valido',
				13 => 'El telefono ya esta asignado a un usuario',
				14 => 'Ya existe ese telefono de empresa',
				15 => 'Ya existe ese Nif',
				16 => 'Las nuevas contraseñas no coinciden'
			);
			
		parent::__construct();
	}



	
	//muestra mensaje al user en (accion)
	//===================================================
	public function show_mensaje($mensaje){
		$return  = '<div class="col-sm-12 full">';
		$return .= '<div class="row">';
		$return .= '<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 login-box-locked">';
		$return .= $mensaje;
		$return .= '</div>';
		$return .= '</div>';
		$return .= '</div>';
		return $return;
	}


//intervencion

//=============================================================


//metodos de validacion comunes
//=============================================================

	//hay campos vacios
	//=============================================================
	public function campos_requeridos($required_fields, $empresa = NULL){
		//si es true(es un registro) y si es empresa, hay mas campos obligados
		if($empresa == true){
			if( (!empty($_POST['tipo_usuario'])) && ($_POST['tipo_usuario'] == 'empresa') ){
				array_push($required_fields, 'empresa_telefono', 'tipo_empresa', 'nif');
		}	}
		foreach ($required_fields as $required_field) {  
			if (empty($_POST[$required_field]) || $_POST[$required_field] == '') {
				 $this->error = $this->errores[3];
				return $this->error;
		}	}
		return true;
	}

	//email es correcto y no existe en usuarios
	//=============================================================
	public function validar_email($existente = NULL){
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false){
				$this->error = $this->errores[4];
		}else{
			//si es bueno y se indica que se verifique existencia
			if(!is_null($existente)){
				$this->existe_email($_POST['email']);
	}	}	}

	//busca si existe email en tabla usuarios
	//=============================================================
	private function existe_email($email){
		$this->where('user_email',$email);
		if($salida = $this->getOne('usuarios')){
			$this->error = $this->errores[2];	
	}	}

	//telefono valido y no existe en usuarios
	//=============================================================	
	public function validar_telefono($telefono,$control = null){
		if( (!is_numeric($telefono)) || (strlen($telefono) != 9) ){
			$this->error = $this->errores[11];
		}else{
			if($control == true){
				$this->existe_telefono('usuarios','user_telefono',$telefono);				
	}	}	}

	//-verifica que no exista ese telefono de empresa en tabla
	//=============================================================
	protected function existe_telefono($tabla,$campo,$tel){
		$this->where($campo,$tel);
		if($salida = $this->getOne($tabla)){
			$this->error = $this->errores[14];
		}	
	}

	//hay 2 passwords y coinciden
	//=============================================================
	public function validar_passwords(){
		if($_POST['password'] != $_POST['password2']){
			$this->error = $this->errores[9];
		}
	}










}



?>