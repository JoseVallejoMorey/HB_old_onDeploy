<?php

require_once 'inc/clases/config/builder_config.class.php';

class perfil_config extends builder_config{



//sacara todas las directivas de tabla que se le indique
//dara control sobre activar/desactivar
public function master_control($param,$que = null){

	if(is_null($que)){
		//seccion
		$titulo = 'Seccion';
		$link = 'secciones';
		$identificador = 'id';
		$tabla = 'secciones';
		$estado = $param;
	}else{
		//es directiva
		$titulo = 'Directiva';
		$link = 'directivas';
		$identificador = 'text';
		$tabla = $param;
		$estado = 'estado';
	}

	$salida = $this->get($tabla);
	$return = '<table class="table table-hover interruptor"><tr>';
	if($que == 'directiva'){
			$return.= '<th>Numero</th>';
	}			

	$return.= '<th>'.$titulo.'</th><th></th><th>Estado</th><th>Opcion</th></tr>';
	foreach ($salida as $key => $value) {
		if($value[$estado] == 1){	
			$status = 'Activado';	
			$opcion = 'Desactivar';
			$opcion_class = 'btn-danger';
		}else{						
			$status = 'Desactivado'; 
			$opcion = 'Activar'; 
			$opcion_class = 'btn-success';
		}
		$return.= '<tr>';
		if($que == 'directiva'){
			$return.= '<td>'.$value['id'].'</td>';
		}		
		$return.='<td>'.$value[$identificador].'</td>
				<td></td>
				<td>'.$status.'</td>
				<td><a class="btn '.$opcion_class.'" 
					   link="index.php?perfil_mda=10&'.$link.'='.$tabla.'&sujeto='.$value['id'].'">
					   '.$opcion.'</a></td>
			  </tr>';
	}
	$return .= '</table>';
	return $return;
}



	//tiene que generar un enlace de eliminar e indicar tabla correspondiente
	public function links_table(){
		$return   = '';
		$columnas = array('titulo','seccion','idioma','primer parametro','primer valor',
						  'segundo parametro','segundo valor','Opcion');
		$cols	  = array('id','titulo','seccion','idioma','primer_parametro','primer_valor',
					  	  'segundo_parametro','segundo_valor');

		//mostramos contenido
		if($filas = $this->get('links_main',NULL,$cols)){

			$link = 'index.php?perfil_mda=11&link_operator=links_main&linkdel=';
			$return .= '<table class="table table-stripped">';
			foreach ($columnas as $value) {
				$return .= '<th>'.$value.'</th>';
			}
			foreach ($filas as $key => $value) {
				$return .= '<tr>';
				foreach ($value as $key2 => $value2) {
					if($key2 == 'id'){	$id = $value2;
					}else{				$return .= '<td>'.$value2.'</td>';	}
				}
				$return .='<td><a href="'.$link.$id.'">Eliminar</a></td>';
				$return .= '</tr>';
			}
			$return .= '</table>';
		}
		return $return;
	}







	//Modos de landing page promo o activa
	//======================================
	public function perfil_landing(){

		$s_promo = array('1a','1b','1c','1d');
		$s_activa = array('2a','2b','2e');

		$return = '<h3>Configuracion General</h3>';
		$return.= $this->landing_genericas();

		$return.= '<h3>Configuracion Especifica</h3>';
		$return.= $this->landing_especificas();

		return $return;

	}


	public function landing_genericas(){
		$link = 'landing_sec';	
		$tabla = 'espereto';	//a definir
		$status = array('promo','activa');

		$return = '<table class="table table-bordered table-hover interruptor"><tr>';
		$return.= '<th>Definicion</th>';
		$return.= '<th>Estado</th><th>Opcion</th></tr>';

		$salida = $this->get('landing_estado');
		foreach ($salida as $key => $value) {

			foreach ($status as $key => $value2) {
				if($value[$value2] == 1){	
					$estado = 'Activado';	
					$opcion = 'Desactivar';
					$opcion_class = 'btn-danger';
				}else{						
					$estado = 'Desactivado'; 
					$opcion = 'Activar'; 
					$opcion_class = 'btn-success';
				}				

				$enlace = 'index.php?perfil_mda=10&'.$link.'='.$tabla.'&sujeto='.$value2;
				$a = '<a class="btn '.$opcion_class.'" link="'.$enlace.'">'.$opcion.'</a>';
				//cada fila
				$return .= '<tr>';
				$return .= '<td>'.$value2.'</td>
							<td>'.$estado.'</td>
							<td>'.$a.'</td></tr>';
			}
		}
		$return .='</table>';

		return $return;
	}



	public function landing_especificas(){
		$link = 'landing_esp';	
		$tabla = 'caraculo';	//a definir
		$return = '<table class="table table-hover interruptor"><tr>';
		$return.= '<th>ID</th><th>Orden</th><th>Definicion</th>';
		$return.= '<th></th><th>Estado</th><th>Opcion</th></tr>';

		$salida = $this->get('landing_sections');

		foreach ($salida as $key => $value) {

			if($value['estado'] == 1){	
				$estado = 'Activado';	
				$opcion = 'Desactivar';
				$opcion_class = 'btn-danger';
			}else{						
				$estado = 'Desactivado'; 
				$opcion = 'Activar'; 
				$opcion_class = 'btn-success';
			}				

			//cada fila
			$enlace = 'index.php?perfil_mda=10&'.$link.'='.$tabla.'&sujeto='.$value['id'];
			$a = '<a class="btn '.$opcion_class.'" link="'.$enlace.'">'.$opcion.'</a>';
			$return.= '<tr>';
			$return.= '<td>'.$value['id'].'</td>
						<td>'.$value['orden'].'</td>
						<td>'.$value['definicion'].'</td>
						<td></td>
						<td>'.$estado.'</td>
						<td>'.$a.'</td></tr>';
		}
		$return .='</table>';

		return $return;

	}




//the end	
}

?>