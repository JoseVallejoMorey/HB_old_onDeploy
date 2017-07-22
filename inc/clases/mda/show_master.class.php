<?php


//include_once'builders.class.php';

class show_master extends builders{

	
	//objetos
	public $Reserva;
	public $Fechas;
	//public $Perfil; 		//heredado de builders

	public function __construct(){
		parent::__construct();	

		$this->Reserva = new reserva_builder();
		$this->Fechas = new fechas();

	}

//mostrando tabla con paquetes caducados
	public function paquetes_caducados(){
		$return = '';
		$link = 'index.php?perfil_mda=8&mantenimiento=paquetes&paquete=';
		$cols = array('id','paquete','anuncios','fecha_inicio','fecha_final','duracion',
					  'duracion_total','full','estado');

		if($salida = $this->Reserva->get('paquetes',NULL,$cols)){
			$titulos = array('paquete','anuncios','fecha inicio','fecha final','duracion',
							 'duracion total','completo','estado','accion');

			$return .= '<table class="table table-striped table-hover table-bordered">';
			foreach ($titulos as $value) {
				$return .= '<th>'.$value.'</th>';
			}
			foreach ($salida as $key => $value) {
				$id_paquete = $value['id'];
				unset($value['id']);
				$return .= '<tr>';
				$return .= '<td>'.$value['paquete'].'</td>
							<td>'.$value['anuncios'].'</td>
							<td>'.$value['fecha_inicio'].'</td>
							<td>'.$value['fecha_final'].'</td>
							<td>'.$value['duracion'].'</td>
							<td>'.$value['duracion_total'].'</td>
							<td>'.$value['full'].'</td>
							<td>'.$value['estado'].'</td>';
				$return .= '<td><a class="btn btn-info" href="'.$link.$id_paquete.'" >
						  		 Activar/Desactivar</a></td>';
				$return .= '</tr>';
			}
			$return .= '</table>';

		}
		return $return;
	}

//mantenimiendo de reservas (special, star, banners y promos)
//cantidad de dias desde ultima revision
public function ultima_reserva(){
	$return = array();
	//1a parte
	$this->Reserva->orderBy('date','Desc');
	//si hay registros tendra exito sino no
	if($u = $this->Reserva->getOne('historial_reservas','date')){
		$u_ = $this->Fechas->hacerLegible($u['date']);
		$return['ultima_reserva'] =  $u_[1].'/'.$u_[2].'/'.$u_[3];

		//2a parte
		$hoy  = new DateTime($this->Reserva->date);
		$last = new DateTime($u['date']);
		$interval = $last->diff($hoy);
		$dias = $interval->format('%r%a');
		//salida guapa
		if($dias == 0){	$return['cuando'] = 'Hoy';
		}else{			$return['cuando'] = 'Hace '.$dias.' dias';	}

	}else{
		$return['cuando'] 		  = 'No hay datos';
		$return['ultima_reserva'] = 'No hay registros';
	}
	
	return $return;
}




	//mostrando anuncios de los usuarios, en mda
	public function mostrando_anuncios(){

		$return = '';
		$cols = array('id', 'ussr', 'provincia', 'municipio', 'tipo_venta',
					  'anuncio_promocionado','anuncio_e','apto','activo');
		$salida = $this->get('anuncios', NULL, $cols);

		if ($this->count==0){
			$return .= '<h5 id="sec1">No hay anuncios </h5>';
		}else{		
			$return .= '<table class="table table-bordered table-hover interruptor">';
			$return .= '<tr><th>Imagen</th><th>id - usuario</th><th>Provincia</th>';
			$return .= '<th>Operacion</th><th>Apto</th><th>Activo</th><th>Contratado</th>';
			$return .= '<th>Opciones</th></tr>';

			foreach($salida as $value){
				$id_arr = md5($value['id']);
				$imagen = $this->sacar_foto($id_arr, $value['ussr']);
				$return .= '<tr>';
				$return .= '<td>'.$imagen.'</td>';
				$return .= '<td>'.$value['id'].' - '.$value['ussr'].'</td>';
				$return .= '<td>'.$value['provincia'].'<br />'.$value['municipio'].'</td>';
				$return .= '<td>'.$value['tipo_venta'].'</td>';
				$return .= '<td>'.$value['apto'].'</td>';
				$return .= '<td>'.$value['activo'].'</td>';
				$return .= '<td>';
					if(!empty($value['anuncio_promocionado']) && 
						($value['anuncio_promocionado'] == 1)){
						$return .= 'Anuncio promocionado<br />';
					}
					if($value['anuncio_e'] == 1){
						$return .= 'Anuncio estrella';
					}	
				$return .= '</td>';
				$enlace_js = 'link="index.php?perfil_mda=7&deletear='.$id_arr.'"';
				$return .= '<td><a class="btn btn-danger" '.$enlace_js.'>eliminar</a></td>';
				$return .= '</tr>';
			}
			$return .= '</table>';
		}
		return $return;	
	}















	//muestra tablas con info de empresas (3 secciones)
	public function mda_empresas($num){
		// indice de $num
		// 1-mostrar todas
		// 2-mostrar no validadas(pero aptas)
		// 3-mostrar no aptas


		$return = '<table class="table table-striped table-hover table-bordered">';
		$return .= '<tr><th>Logo</th><th>Empresa</th>
			        <th>Tipo de empresa</th><th>Apto</th>';
		if($num == 2){	$return .= '<th>Opcion</th>';	}
		$return .= '</tr>';

		if($num == 2){
			$this->Perfil->where('apto',1);
			$this->Perfil->where('visible',0);
			
		}else if($num == 3){
			$this->Perfil->where('apto',0);
		}else{
			//puede ser 1 puede ser lo que sea
			$this->Perfil->where('apto',1);
			$this->Perfil->where('visible',1);	
		}

		$salida = $this->Perfil->get('perfiles_emp');
		foreach ($salida as $key => $value) {
			$return .='<tr>
					    <td><img src="imagenes/logo/'.$value['img'].'" /></td>
					    <td>'.$value['empresa'].'</td>
					    <td>'.$value['tipo_empresa'].'</td>
					    <td>'.$value['apto'].'</td>';
			if($num == 2){
				$salt = $this->Perfil->select_salt($value['id']);
				$return .= '<td><a href="index.php?perfil_mda=9&mpresa='.$salt.'">Validar</a></td>';
			}		    
			$return .='</tr>';
		}
		$return .= '</table>';		
		return $return;
	}


	public function info_alertas($Alerta){

	  $return = '';
	  $cols = array('id', 'nombre', 'email', 'telefono', 'provincia', 'municipio', 'subtipo_inmueble', 
	          'tipo_venta', 'idiomas', 'fecha');

	  if($salida = $Alerta->get('alertas', NULL, $cols)){

	    foreach($salida as $res){
	      $return .='<tr>
	                  <td>'.$res['nombre'].'</td>
	                  <td>'.$res['provincia'].', '.$res['municipio'].'</td>
	                  <td>'.$res['email'].'</td>
	                  <td>'.$res['telefono'].'</td>
	                  <td>'.$res['subtipo_inmueble'].'</td>
	                  <td>'.$res['tipo_venta'].'</td>
	                  <td>'.$res['idiomas'].'</td>
	                  <td>'.$res['fecha'].'</td>
	                  <td><a href="">ver</a></td>
	                  <td><a href="">pendiente</a></td>
	                  <td><a href="">eliminar</a></td>
	                </tr>';
	    }
	  }
	  return $return;

	}







//creamos mogollon de anuncios para comprobar el funcionamiento del portal a full
public function crear_mogollon_anuncios($cantidad){

	//conectara a usuarios y sacara los usuarios
	$usuarios = array();

	//creando anuncio
	for ($i=0; $i <$cantidad; $i++) { 

	//elije un usuario, obtiene paquete disponible, si no tiene paquetes disponibles 
	//escoje otro y descarta este del array usuarios

	//datos de anuncio para ello obtendra valores correspondientes de db y eligira
	//al azar
	//provincia poblacion
	//tipo subtipo
	// random precio 
	// -de 7 rooms
	// 6 extras al azar
	// //mete usuario

	// 2- creara enigma del anuncio,
	// 	actualizara paquetes

	// 3- creara en img, metera 10 imagenes (que tiene ya en su carpeta)
	// para ese anuncio

	// 4-metera 3 titulo-descripcion en su tabla correspondiente


//(importante antes de esto ahora el anuncio guardara nombre de empresa o particular)
	}


}















}




?>