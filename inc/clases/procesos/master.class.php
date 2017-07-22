<?php


class Master extends Sql_operations{
	
	
	private $Alerta;
	//una funcion se encarga de borrar al usuario en tabla anuncios
	//otra en tabla alertas
	//otra en tabla reservas
	// y la ultima en tabla usuarios

	public function __construct(){
		parent::__construct();	
		$this->Alerta = new alertas();

	}

	public function exterminar_usuario($user){
		$resultado = array();


		$this->exterminar_de_anuncios($user);
		$this->exterminar_de_alertas($user);
		$this->exterminar_de_usuarios($user);
	}


	//==========================================
	private function exterminar_de_anuncios($user){

		$this->where('ussr',$user);
		if($salida = $this->get('anuncios',NULL,'id')){
			foreach($salida as $value){
				$this->eliminar_anuncio_yamigos($value['id']);
			}
		}

		$this->where('ussr',$user);
		$this->delete('anuncios_borradores');
		$this->where('ussr',$user);
		$this->delete('anuncios_borrados');

	}

	//==========================================
	private function exterminar_de_alertas($user){

		$this->Alerta->where('user',$user);
		$this->Alerta->delete('alertas_actividad');

		$this->Alerta->where('user',$user);
		$this->Alerta->delete('alertas_preferencias_users');

		$this->Alerta->where('user',$user);
		$this->Alerta->delete('user_contact');

	}

	//==========================================
	private function exterminar_de_reservas(){
		//no me complicare la vida en este por ahora
	}

	//==========================================
	private function exterminar_de_usuarios($user){

		$this->Perfil->where('id',$user);
		$this->Perfil->delete('stats_user_conections');

		$this->Perfil->where('id',$user);
		$this->Perfil->delete('perfiles_emp');

		$this->Perfil->where('id',$user);
		$this->Perfil->delete('perfiles_par');

		$this->Perfil->where('id',$user);
		$this->Perfil->delete('usuarios');
	}

	
	//elimina anuncio y tablas relacionadas con el
	//====================================================
	public function eliminar_anuncio_yamigos($limpio, $ussr = NULL){
		
		$error = 0;
		$img   = '';
		$small = '';
		
		$mensaje   = array();
		$mensaje[1]= 'no existe auncio';
		$mensaje[2]= 'el anuncio no puede eliminarse porque ha sido elegido como anuncio estrella';
		$mensaje[3]= 'el anuncio no puede eliminarse porque esta promocionado';
		$mensaje[4]= 'no coincide usuario';
		
		//1- tengo la id del anuncio que quiero borrar

		$cols = array('ussr', 'anuncio_e', 'anuncio_promocionado');
		$this->where('id',$limpio);
		if($salida = $this->getOne('anuncios')){
			if(is_null($ussr)){		$ussr = $salida['ussr'];	}		
		}
		
		//si no hay error con anuncio ni usuario avanzamos
		if($error == 0){
		
			//elimina anuncio ===============================================
			$this->where('id',$limpio);
			if(!$this->delete('anuncios')){ $error = 5;}
			
			//elimina las descripciones en otros idiomas=====================
			$this->where('anuncio',$limpio);
			if(!$this->delete('anuncios_idiomas')){ $error = 6;}
			
			//borrar superficies exteriores del anuncio======================
			$this->where('anuncio',$limpio);
			if(!$this->delete('anuncios_exteriores')){ $error = 7;}
			
			//fotos relacionadas con el anuncio, elimino de tabla y de disco=
			$cols = array('id', 'img');
			$this->where('id_e',$elegido);
			
			if($salida = $this->get('anuncios_img')){
				foreach($salida as $value){
					
					$img   = "imagenes/anuncios_img/$ussr/$value[img]";
					$small = "imagenes/anuncios_img/$ussr/small/small_$value[img]";
					
					if(file_exists($img)){		unlink($img);	}
					if(file_exists($small)){	unlink($small);	}
				}
				
			$this->where('id_e',$elegido);
			$this->delete('anuncios_img');
			}
			
			//elimina de tabla enigma =======================================
			$this->where('anuncio',$limpio);
			$this->delete('enigma');	
			
		}else{
			//error report
			return $mensaje[$error].'--<br />';
		}	
	}
		
		





//insolacion
//================================================
public function mantenimiento_promocionados(){
	$this->where('anuncio_promocionado',1);
	$this->where('promo_fecha_final',array('<=' => $this->date));
	if($salida = $this->get('anuncios')){
		foreach ($salida as $key => $value) {
			$cols = array('anuncio_promocionado' => '0',
						  'promo_fecha_inicio' => '',
						  'promo_fecha_final' => '');
			$this->where('id',$value['id']);
			$this->update('anuncios',$cols);
		}
	}
}

//activar paquete desactivado
//=================================================
public function activar_paquete($paquete){

	$this->Reserva->where('id',$paquete);
	if($salida = $this->Reserva->getOne('paquetes')){

		//1- actuar en paquete
		$cols = array('estado' => 1);
		$this->Reserva->where('id',$paquete);

		if($this->Reserva->update('paquetes',$cols)){
			//2- actuar sobre anuncios
			$this->where('paquete',$paquete);
			$cols = array('id','apto');
			if($salida = $this->get('anuncios',NULL,$cols)){
				//en lugar de esto por cada anuncio valorara si es apto()	
				foreach ($salida as $key => $value) {
					$this->check_anuncio_apto($value['id']);
	}	}	}	}

}

//desactivar paquete y sus anuncios 
//motivos caducado o inactivo
//======================================================
public function desactivar_paquete($paquete,$motivo){
	$this->Reserva->where('id',$paquete);
	if($salida = $this->Reserva->getOne('paquetes')){
		//actuar sobre anuncios
		$this->where('paquete',$paquete);
		$cols = array('id','apto');
		if($salida = $this->get('anuncios',NULL,$cols)){
			//en lugar de esto por cada anuncio valorara si es apto()
			$col = array('apto' => 0);
			foreach ($salida as $key => $value) {
				$this->where('id',$value['id']);
				$this->update('anuncios',$col);
			}
		}	

		//actuar en paquete
		$cols = array('estado' => $motivo);
		$this->Reserva->where('id',$paquete);
		$this->Reserva->update('paquetes',$cols);
	}
}

//buscara paquetes caducados si los hay y los desactivara
public function mantenimiento_paqueteria(){
	$this->Reserva->where('fecha_final',array('<' => $this->date));
	if($salida = $this->Reserva->get('paquetes')){
		foreach ($salida as $key => $value) {
			$this->desactivar_paquete($this->Reserva,$value['id'],3);
		}
	}
}

//una sola funcion para llamar a todas 
public function mantenimiento_reservas(){

	//anuncios promocionados
	$this->mantenimiento_promocionados();

	//banners
	$tablas = array('central' 	=> 'reserva_banners_central',
					'lateral'	=> 'reserva_banners_lateral',
					'superior'  => 'reserva_banners_superior');
	$campos = array('tipo','b1','b2','b3','b4','b5','b6','b7','b8');
	$this->mantenimiento_generico($tablas, $campos, 'historial_banners');

	//special
	$tablas = array('alquiler' => 'reserva_special_alquiler',
					'comercial'=> 'reserva_special_comercial',
					'venta'    => 'reserva_special_venta');
	$campos = array('seccion','s1','s2','s3','s4','s5','s6','s7','s8');
	$this->mantenimiento_generico($tablas, $campos, 'historial_special');

	//star_area
	$tablas = array('1'	 => 'reserva_star_1',	'2'  => 'reserva_star_2',
					'3'  => 'reserva_star_3',	'4'  => 'reserva_star_4',
					'5'  => 'reserva_star_5',	'6'  => 'reserva_star_6',
					'7'  => 'reserva_star_7',	'8'  => 'reserva_star_8',
					'9'  => 'reserva_star_9',	'10' => 'reserva_star_10');
	$campos = array('zona','e1','e2','e3');
	$this->mantenimiento_generico($tablas, $campos, 'historial_star');

}


//Limpia (banners, special, star) de reservas caducadas y las
// lleva a su historial correspondiente
private function mantenimiento_generico($tablas, $campos, $destino){

	foreach ($tablas as $key => $value) {
		//ahora debe borrar filas anteriores a hoy (hoy no)
		$this->Reserva->where('date',array('<' => $this->date));
		if($salida = $this->Reserva->get($value)){
			$salientes = $campos;
			$new_campos = array();
			foreach ($salida as $key2 => $value2) {
				//1-$new_campos tiene toda la info
				$new_campos[$campos[0]] = $key;
				unset($salientes[0]);
				foreach ($salientes as $key3 => $value3) {
					 // var_dump($value3);
					$new_campos['date']  = $value2['date'];
					$new_campos[$value3] = $value2[$value3];
				}
				//2-metemos info
				if($this->Reserva->insert($destino,$new_campos)){
					//3-borramos antigua
					$this->Reserva->where('id',$value2['id']);
					$this->Reserva->delete($value);
					//4-guardo en historial_reservas
					$this->guardar_tabla_historial($value);
				}

	}	}	}

}

	//si existe la fecha actualiza su correspondiente campo
	//sino crea la fila con la fecha
	private function guardar_tabla_historial($tabla){

		//la fecha que crea como indice deberia ser 1anterior a hoy
		$campo = str_replace('reserva_', '', $tabla);
		$campos = array($campo => '1');
		$this->Reserva->where('date',$this->date);

		if(!$this->Reserva->update('historial_reservas',$campos)){
			$campos = array($campo => '1',
							'date' => $this->date);
			$this->Reserva->insert('historial_reservas',$campos);
		}	
	}


	
}


?>