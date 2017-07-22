<?php
include_once'sql_anuncios.class.php';

class sql_anuncios_pro extends sql_anuncios {

//1
//funciones para creacion y actualizacion de anuncios
//======================================================
	public function sql_anuncio_preparador(){

		$art = ''; //para redirigir despues
		//eliminamos algunas variables
		//si era borrador hay que eliminarlo de alli
		if(!empty($_POST['ex_borr'])){
			$ex_borr = $_POST['ex_borr'];
			unset($_POST['ex_borr']);
		}
		//elimino idioma del form que ya no me sirve
		if(!empty($_POST['lang_form'])){	unset($_POST['lang_form']);	}

		//segun el estado del anuncio(borrador, actualizar o nuevo)
		if( (!empty($_POST['borrador'])) && ($_POST['borrador'] == true) ){
			$this->sql_anuncio_borrador();
		}else if(!empty($_POST['art'])){
			$this->sql_anuncio_actualizar();
		}else{
			$this->sql_anuncio_nuevo();
		}


	}

	//creando un borrador
	private function sql_anuncio_borrador(){

		unset($_POST['paquete']);	//como es borrador no necesita paquete.
		unset($_POST['art']);		//ni art, porque sera dirigido a main perfil o a ver anuncios.
		unset($_POST['borrador']);	//solo empleado para dirigir el post hasta aqui.
		unset($_POST['suelo']);		//al ser borrador no guardamos esta informacion ya que va -
		unset($_POST['metros']);	// a otra tabla dependiente del anuncio definitivo.

		$_POST['fecha_publicacion'] = $this->now;	//añadimos fecha de creacion
		$this->agregar_db('anuncios_borradores');	//agregamos el anuncio como borrador
		
		//nos llevara al menu principal
		header('Location: index.php?perfil=1');
		exit;
	}

	//actualizando un anuncio existente
	private function sql_anuncio_actualizar(){

		$id = $this->resolver_enigma($_POST['art']);		//resolvemos,
		$art = '&art='.$_POST['art']; 					//montamos y
		unset($_POST['art']);							//eliminamos
		
		$_POST['fecha_actualizacion'] = $this->now;		//añadimos fecha de actualizacion
		$superficies = $this->superficies_varias();		//prepara superficies
		$this->actualizar_db('anuncios', $id);			//actualizo anuncio
		$this->introducir_superficies($superficies,$this->last_id);	//introduce superficies en su tabla
		
		//nos llevara al siguiente formulario
		header('Location: '.ANUNCIO_PASO_2.$art);
		exit;
	}

	//creando un nuevo anuncio
	private function sql_anuncio_nuevo(){

		$superficies = $this->superficies_varias();	//prepara superficies
		unset($_POST['art']);						//eliminamos innecesario
		$_POST['fecha_publicacion'] = $this->now;	//añadimos fecha de creacion
		$this->agregar_db('anuncios');				//introduzco anuncio en tabla anuncios

		$this->introducir_superficies($superficies,$this->last_id);//introduce superficies en su tabla

		$this->sumar_paquete($_POST['paquete'],'sumar');		//sumamos 1 al paquete
		$this->crear_enigma($this->last_id);			//una vez creado del anuncio, creo el enigma
		$art = '&art='.md5($this->last_id);			//defino art para que redirija 

		//hay que eliminar el borrador
		if(isset($ex_borr)){
			$this->where('id',$ex_borr);
			$this->delete('anuncios_borradores');
		}
		//nos llevara al siguiente formulario
		header('Location: '.ANUNCIO_PASO_2.$art);
		exit;
	}



//2
//funciones para imagenes de anuncio (nuevas y actualizaciones)
//=============================================================

	public function sql_img_preparador(){

		if(!empty($_POST['imagen'])){
			$actividad = 'actualizar datos imagen';
			$this->sql_img_actualizar();
					
		}else{
			$actividad = 'nueva imagen';
			$this->sql_img_nueva();
		}		 

		$anuncio = $this->resolver_enigma($_POST['id_e']);
		$this->check_anuncio_apto($anuncio);	
		//actualizo movimiento
		$this->Perfil->movimientoStats($actividad);

	}

	//actualizando informacion de imagen existente
	private function sql_img_actualizar(){
		$imagen = $_POST['imagen'];
		unset($_POST['imagen']);
		$this->actualizar_db('anuncios_img',$imagen);
	}

	//se trata de una nueva imagen con datos
	private function sql_img_nueva(){
		unset($_POST['imagen']); //(por si esta aunque vacio)
		$this->agregar_db('anuncios_img');

	}

//3
//idiomas del anuncio, creacion y actualizacion
//==============================================
	public function sql_idiomas_preparador(){

		//separo el idioma
		if(!empty($_POST['idioma'])){$idioma = $_POST['idioma'];}
		//cambio art por id
		if(!empty($_POST['art'])){
			$anuncio = $this->resolver_enigma($_POST['art']);
			unset($_POST['art']);
			$_POST['anuncio'] = $anuncio;
		}

		//si existe ese anuncio, ese idioma; se actualiza. sino nueva
		$this->where('anuncio',$anuncio);
		if(isset($idioma)){
			$this->where('idioma',$idioma);
		}

		if($existe = $this->getOne('anuncios_idiomas')){
			$this->actualizar_db('anuncios_idiomas',$existe['id']);	//actualizar
		}else{
			$this->agregar_db('anuncios_idiomas');					//agregar nueva
		}

		//veremos si anuncio sigue siendo apto
		$this->check_anuncio_apto($anuncio);
		//actualizamos anuncio/idiomas con su nuevo idioma
		$this->check_anuncio_idiomas($anuncio, $idioma);

	}





//4
//actualizando perfil de usuario
//============================================================
	// public function sql_perfil_preparador(){

	// 	//en actualizar_db le paso objeto $this->Perfil, por el cambio de db

	// 	if($_POST['form_to'] == 'perfil_datos_particular'){
	// 		//perfil usuario particular
	// 		$this->actualizar_db('usuarios',$this->user, $this->Perfil);

	// 	}else{

	// 		//perfil usuatio-empresa, datos-empresa o logo
	// 		//aqui intercepta el codigo y coje empresa,
	// 		//substituye espacios por _ y lo mete en nik_empresa
	// 		if( (!empty($_POST['user_email'])) && (!empty($_POST['user_telefono'])) ){
	// 			$tabla = 'usuarios';
	// 		}else if(isset($_POST['empresa'])){
	// 			$tabla = 'perfiles_emp';
	// 			$_POST['empresa'] = strtolower($_POST['empresa']);
	// 			$_POST['nik_empresa'] = str_replace(" ", "_", $_POST['empresa']);
	// 		}
	// 	var_dump($_POST);
	// 	die;


	// 		$this->actualizar_db($tabla,$this->user, $this->Perfil);
	// 		$this->Perfil->empresa_apta();
			
	// 	}
	// }

//logotipo o fondo de perfil
public function sql_logo_fondo($tabla){
	$this->actualizar_db($tabla,$this->user, $this->Perfil);
	$this->Perfil->empresa_apta();
}

//oficina o agentes
public function sql_oficina_agentes($tabla){
	$_POST['empresa'] = $this->user;
	$this->agregar_db($tabla,$this->Perfil);
}

//actualizara el nuevo pasword que quiere el user
//==============================================
	public function new_user_pw($Perfil){

		$required_fields = array('password','password2','old_password','old_password2');

			//si todos los campos requeridos estan continuamos
		if($Perfil->campos_requeridos($required_fields,true) == true){
			$Perfil->validar_old_passwords();		//pasword actuales existen y coinciden	
			$Perfil->validar_passwords();			//pasword existen y coinciden
		}else{
			return $Perfil->error;					//deben completarse los campos requeridos
		}

		if(is_null($Perfil->error)){
			
			$Perfil->where('id',$this->user);
			$cols = array('salt','pass','verificado');
			if($salida = $Perfil->getOne('usuarios',$cols)){
				//var_dump('es bienn');
				$old_pass = hash('sha512', $_POST['old_password'].$salida['salt']);
				$new_pass = hash('sha512', $_POST['password'].$salida['salt']);
				if($old_pass == $salida['pass']){
					//var_dump('pass antiguo coincide');
					if($salida['verificado'] != 1){	return $Perfil->errores[8];	}
					//var_dump('llegado aqui se cambiara su contraseña');
					$campos = array('pass' => $new_pass);
					$Perfil->where('id',$this->user);
					$Perfil->update('usuarios',$campos);

					$Perfil->movimientoStats('Cambiado password');
					header('location:index.php?perfil=9');
					exit;

				}else{
					//var_dump('pass no coincide');
					return $Perfil->errores[5];
				}
			}
		}else{
			//var_dump('no es bien');
			return $Perfil->error;
		}
	}







}
?>