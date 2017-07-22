<?php
//validation user
class seg_validation extends seg_actions{

	public function __construct(){
		parent::__construct();
	}


	//si llega aqui valida el email del usuario
	public function validando_usuario(){
		$return = '';

		//objeto seg_builder y nuevo metodo ahi
		if(!empty($_GET['validation'])){
			if($user = $this->id_from_salt($_GET['validation'])){
				//usuario valido
				$this->where('id',$user);
				$campos = array('verificado' => '1');
				$this->update('usuarios',$campos);
				
				$mensaje = '<p>Correo electronico verificado con exito. <a href="index.php?accion=log">Click aqui para continuar</a></p>
							<a href="index.php?validation='.$_GET['validation'].'">Verificar aqui</a>';
				$return .= $this->show_mensaje($mensaje);
			}else{
				$return .= '<p>no se pudo continuar.</p>';
			}
		}else{
			$mensaje = '<p>Compruebe la bandeja de entrada de su correo electronico para continuar con el proceso.</p>';
			$return .= $this->show_mensaje($mensaje);
		}

		return $return;
	}	

}



?>