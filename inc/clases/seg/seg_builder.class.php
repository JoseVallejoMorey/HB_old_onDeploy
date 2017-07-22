<?php 


class seg_builder extends mysqlidb{

	public $now;
	public $date;
	public $user;
	public $tipo_usuario;
	public $last_id;
	public $base_name;


	//constructor
	public function __construct(){
		if(!empty($_SESSION['user_id'])){	$this->user = $_SESSION['user_id'];		}
		if(!empty($_SESSION['tipo'])){		$this->tipo_usuario = $_SESSION['tipo'];}
		$this->now = date('Y-m-d H:i:s');
		$this->date = date('Y-m-d');
		$this->base_name = 'usuarios';
		parent::__construct($this->base_name);

	}



	//actualizamos actividad y fecha en stats_user_conections
	//===================================================
	public function movimientoStats($actividad,$user = null){
		//si no recibe parametro es el user del objeto
		if(is_null($user)){$user = $this->user;}
		$this->where('user', $user);
		$cols = array ('ultima_actividad' 		=> $actividad,
					   'fecha_ultima_actividad' => $this->now);		   	   
		if(!($this->update('stats_user_conections', $cols))){
			return false;
		}
	}

	//saca el logotipo de una empresa
	//===================================================
	public function sacar_logo($user = null){
		$link = 1;
		if(is_null($user)){
			$user = $this->user;
			$link = 0;
		}
		$return = '';
		$cols = array('img','nik_empresa');
		$this->where('id',$user);
		if($this->salida = $this->getOne('perfiles_emp',$cols)){
			$a1 = '<a href="index.php?inmv='.$this->salida['nik_empresa'].'">';
			$img = '<img src="imagenes/logo/'.$this->salida['img'].'" />';
			$a2 = '</a>';

			if($link == 0){	$return = $img;
			}else{			$return = $a1.$img.$a2;	}					   
		}

		return $return;
	}



	//obtiene id de usuario desde salt
	//======================================================================
	public function id_from_salt($salt){
		$return = '';
		$this->where('salt',$salt);
		if($user = $this->getOne('usuarios','id')){
			$return = $user['id'];
		}
		return $return;
	}
	
	// selecciona salt del usuario para formularios de envio por ajax
	// ======================================================================
	public function select_salt($ussr = null){
		if(is_null($ussr)){$ussr = $this->user;}

		$return = '';
		$this->where('id',$ussr);
		if($salt = $this->getOne('usuarios','salt')){
			$return = $salt['salt'];
		}
		return $return;
	}


	//comprueba si hay un minimo de perfil rellenado
	//=================================================	
	public function empresa_apta(){

		$this->where('id',$this->user);
		//campos imprescindibles
		$campos = array('empresa','tipo_empresa','direccion','descripcion','empresa_telefono','img');
		if($salida = $this->getOne('perfiles_emp',$campos)){
			foreach ($salida as $key => $value) {
				if( (empty($value)) || ($value == '') ){			
					return false;
		}	}	}
		//si pasa del foreach es que no hay campos vacios
		$this->where('id',$this->user);
		$campos = array('apto' => 1);
		$this->update('perfiles_emp',$campos);
	}


}




?>