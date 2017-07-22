<?php


//exclusiva para mda_users
class show_master_users extends builders{

	public $cols;
	public $titulos;


	public function __constructor(){
		parent::__construct();
	}

//1 usuarios
//2 usuarios online
//3 actividad de usuarios
//4 historial de sesiones de usuarios

//se definen titulos y cols de tabla
public function mda_panel_usuarios($num){


	$return = '<table class="table table-hover table-bordered interruptor">';
	if($num == 1){
		$this->titulos = array('id','nombre','tipo usuario','anuncios','email','fecha alta','opcion');
		$this->cols = array('id', 'user_nombre', 'user_email', 'tipo_usuario', 'fecha_alta', 'salt');
	}else if($num == 2){
		$this->titulos = array('user','tipo usuario','navegador','session iniciada',
						 'ultimo movimiento','ultima uri','ip','Opciones');
		$this->cols = array('user', 'tipo_usuario', 'ip', 'browser_name', 'iniciada',
			  		  'ultimo_movimiento', 'ultima_uri');
	}else if($num == 3){
		$this->titulos = array('id','fecha alta','Ultima conexion','Total conexiones',
						 'Tiempo Online','Ultima actividad','fecha ultima actividad');
		$this->cols = array('user','fecha_alta','ultima_conexion','total_conexiones', 
					  'tiempo_online','ultima_actividad','fecha_ultima_actividad');
	}else if($num == 4){
		$this->titulos = array('user','duracion','Navegador','hora de cierre',
						 'modo de cierre','Ultima uri');
		$this->cols = array('user','duracion','browser_name','time_close','cierre','ultima_uri');

	}	

	$return .= $this->mda_trtable();
	$return .= $this->mda_tabla_usuarios($num);
	$return .= '</table>';

	return $return;
}


private function mda_tabla_usuarios($num){

	$return = '';
	
	if($num == 1){
		//1 muestra informacion de los usuarios registrados
		$link = 'index.php?perfil_mda=4&artmda=';
		$cuantos_anuncios = 0;
		
		$this->Perfil->where('tipo_usuario', array('NOT IN' => array('mda')));
		if($salida = $this->Perfil->get('usuarios', NULL, $this->cols)){

			foreach($salida as $value){
				$this->where('ussr',$value['id']);
				if($salida = $this->get('anuncios')){ $cuantos_anuncios = count($salida);
				}else{								  $cuantos_anuncios = 0;				
				}
				$enlace = 'link="'.$link.$value['salt'].'"';
				$return .='<tr>
						<td>'.$value['id'].'</td><td>'.$value['user_nombre'].'</td>
						<td>'.$value['tipo_usuario'].'</td><td>'.$cuantos_anuncios.'</td>
						<td>'.$value['user_email'].'</td><td>'.$value['fecha_alta'].'</td>
						<td><a class="btn btn-danger" '.$enlace.' >Eliminar</a></td>
					</tr>';
		}	}	
	
	}else if($num == 2){	
	//2 usuarios online
	$return .= $this->mda_extractor('stats_users_online');		
	}else if($num == 3){	
	//3 actividad de usuarios
	$return .= $this->mda_extractor('stats_user_conections');			
	}else if($num ==4){	
	//4 historial de sesiones de usuarios
	$return .= $this->mda_extractor('stats_total_conections');	
	}	

	return $return;
}


	private function mda_extractor($tabla){
		$return = '';
		if($salida = $this->Perfil->get($tabla,NULL,$this->cols)){
			foreach ($salida as $value) {
				$return .= '<tr>';
				foreach ($value as $value2) {
					$return .= '<td>'.$value2.'</td>';
				}
				$return .= '</tr>';
		}	}	
		return $return;
	}


	private function mda_trtable(){
		$return = '<tr>';
		foreach ($this->titulos as $value){	$return .= '<th>'.$value.'</th>';	}
		$return .= '</tr>';
		return $return;
	}





}






?>