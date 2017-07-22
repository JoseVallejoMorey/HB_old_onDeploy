<?php 

//funciones dedicadas al perfil/alertas y perfil/especializacion 
//=============================================================

include_once'alertas.class.php';
//
class alertas_user extends MysqliDb{

	public $user;
	public $now;
	public $date;
	public $base_name;


	public function __construct(){

		$Usuario = new builders();																	
		$this->user = $Usuario->user;
		$this->now  = $Usuario->now;
		$this->date = $Usuario->date;
		$this->base_name = 'alertas';

		parent::__construct($this->base_name);
		//parent::__construct();
		
	}	

	//nueva seleccion de parametros paraespecializacion (si puede)
	//===========================================================
	public function nueva_especializacion(){
		$this->where('user',$this->user);
		$salida = $this->get('alertas_preferencias_users');
		//si hay mas de tres especializaciones no puede tener mas
		if($this->count >= 3){
			return 'Solo puede tener tres avisos activos a la vez';	
		}else{
			include'scripts/forms/form_alerta_usuario.php';
		}
	
	
	}

	//muestra especialidades actualmente elegidas por el usuario
	//==========================================================
	public function especializacion_actual($Builder){

		$return = '<table class="table table-hover table-bordered">'; 	
		$return .= '<tr><th>Municipio</th><th>inmueble</th><th>operacion</th><th>idiomas</th><th>fecha </th>
            		<th>Ver </th><th>Quitar </th></tr>'; 
        
		$cols = array('id','provincia','municipio','subtipo_inmueble','tipo_venta','idiomas','fecha');
		$this->where('user',$this->user);
		$salida = $this->get('alertas_preferencias_users', NULL, $cols);
		foreach($salida as $value){

			$return .= '<tr>
						<td>'.$Builder->show_provincia($value['provincia']).', '.$value['municipio'].'</td>
						<td>'.$value['subtipo_inmueble'].'</td>
						<td>'.$value['tipo_venta'].'</td>
						<td>'.$value['idiomas'].'</td>
						<td>'.$value['fecha'].'</td>
						<td><a href="index.php?perfil=20&user_alert='.$value['id'].'">Ver alerta</a></td>
						<td><a href="index.php?perfil=20&delete='.$value['id'].'">Eliminar</a></td>
					  </tr>';
		}  
	  
		$return .= '</table>'; 
		return $return;
	}

	//elimina la especializacion elegida
	//===============================================
	public function eliminar_especializacion($delete){
		$this->where('id',$delete);
		if($salida = $this->getOne('alertas_preferencias_users')){
			if($this->user == $salida['user']){
				$this->where('id',$delete);
				$this->delete('alertas_preferencias_users');																
	}	}	}


	//ve las nuevas alertas creadas por visitantes (info superficial)
	//==================================================================
	public function ver_nuevas_alertas($Builder){

		$return = '';

		$descartados = array();
		$cuantos_anuncios = 0;

		$this->where('user',$this->user);
		$this->where('estado', 'descartado');

		if($salida = $this->get('alertas_actividad')){
			foreach($salida as $value){
				$descartados[] = $value['id_alerta'];
			}
			
			$this->where('id', array( 'not in' => $descartados ) );
		}

		$cols = array('id', 'nombre', 'provincia', 'municipio', 'subtipo_inmueble', 'tipo_venta',
		'idiomas', 'fecha');
		if($salida = $this->get('alertas', NULL, $cols)){
			$return .= '<table class="table table-hover table-bordered">';
			$return .= '<tr><th>nombre</th><th>Municipio</th><th>inmueble</th>
            			<th>operacion</th><th>idiomas</th><th>fecha </th><th>Ver </th>
            			<th>Quitar </th></tr>';
			foreach($salida as $value){

				//$where = 'ussr="'.$salida['id'].'"';
				$return .= '<tr>
						<td>'.$value['nombre'].'</td>
						<td>'.$Builder->show_provincia($value['provincia']).', '.$value['municipio'].'</td>
						<td>'.$value['subtipo_inmueble'].'</td>
						<td>'.$value['tipo_venta'].'</td>
						<td>'.$value['idiomas'].'</td>
						<td>'.$value['fecha'].'</td>
						<td><a href="index.php?perfil=21&alert='.$value['id'].'">Ver alerta</a></td>
						<td><a href="index.php?perfil=21&descartar='.$value['id'].'">Descartar</a></td>
					</tr>';
			}

        
    		$return .= '</table>';
    		return $return;

		}else{
			$return = '<p class="msj_p">No hay alertas</p>';
			return $return;
		}
		

	}


	//abre alerta creadas por visitantes (informacion completa)
	//==================================================================
	public function mostrar_info_alerta($cual, $tabla){

		$perfil = $_GET['perfil'];
		$return = '<a href="index.php?perfil='.$perfil.'">Atras</a>';
		$this->where('id',$cual);
		$salida = $this->getOne($tabla);
		foreach ($salida as $key => $value) {
			$return .= $key.' : '.$value.'<br/>';
		}
		if($tabla == 'alertas'){
			$this->regAlertstats($cual,'visita');
		}
		var_dump($return);
		return $return;
		
	}

	//muestra alsuario alertas(visitantes) que el mismo ha borrado
	//==================================================================
	public function ver_alertas_descartadas($Builder){

		$descartados = array();
		$fecha_descarte = '';
		$cuantos_anuncios = 0;

		$return = '';

		$this->where('user',$this->user);
		$this->where('estado', 'descartado');

		if($salida = $this->get('alertas_actividad')){
			foreach($salida as $value){
				$descartados[] = $value['id_alerta'];
				$fecha_descarte[] = $value['fecha_descarte'];
			}
			$this->where('id', array( 'in' => $descartados ) );
		}

		$cols = array('id', 'provincia', 'municipio', 'subtipo_inmueble', 'tipo_venta');
		if($salida = $this->get('alertas', NULL, $cols)){
			$i = 0;
			$return .= '<table class="table table-hover table-bordered">';
			$return .= '<tr><th>Municipio</th><th>inmueble</th><th>operacion</th>
           				<th>fecha descarte</th><th>Ver </th></tr>';
			foreach($salida as $value){
				
				$return .= '<tr><td>'.$Builder->show_provincia($value['provincia']).', '.$value['municipio'].'</td>
								<td>'.$value['subtipo_inmueble'].'</td>
								<td>'.$value['tipo_venta'].'</td>';
				if(is_array($fecha_descarte)){	

					$return .=	'<td>'.$fecha_descarte[$i].'</td>';
				}else{
					$return .=	'<td></td>';			
				}
				$return .=		'<td><a href="index.php?perfil=21&alert='.$value['id'].'">Ver alerta</a></td></tr>';
			}

			$return .= '</table>';
			return $return;
		}else{
			$return = '<p class="msj_p">No hay alertas descartadas</p>';
			return $return;
		}
		


	}


	//registrar accion en alerta, alertas_stats y alertas_acciones
	//============================================================
	private function regAlertstats($alerta, $destino){
		//destinos posibles: visita , respuesta , descarte
		
		if($destino == 'visita'){
			$this->regAlertVisit($alerta);
			$estado = 'visto';
		}
		if($destino == 'respuesta'){}
		if($destino == 'descarte'){		$estado = 'descartado';  }
		
		$destino = 'fecha_'.$destino;	
		
		//insertamos en alertas_activity la fecha de visita
		$this->where('id_alerta',$alerta);
		$this->where('user',$this->user);
		//si existe actualiza, sino crea. method here
		if($this->getOne('alertas_actividad')){
			$cols = array($destino => $this->now, 'estado' => $estado);
			$this->where('id_alerta',$alerta);
			$this->update('alertas_actividad',$cols);
	
		}else{
			$cols = array('id_alerta' => $alerta, 'user' => $this->user, 'estado' => $estado, $destino => $this->now);
			$this->insert('alertas_actividad',$cols);	
		
		}

	}

	//registrar visit en alerta
	//=============================
	private function regAlertVisit($alerta){
		
		$this->where('alerta' ,$alerta);
		$salida  = $this->getOne('alertas_stats','visitas');
		$visitas = $salida['visitas'];
		$visitas = $visitas+1;
		
		$cols    = array('visitas' => $visitas, 'fecha_ultima_visita' => $this->now, 'ultimo_visitante' => $this->user);
		
		//actualizamos que user ha visto alerta
		$this->where('alerta' ,$alerta);
		$this->update('alertas_stats',$cols);
	
	}

	public function descartar_alerta($que, $porque){
		if($this->regAlertstats($que, $porque)){
			
			// var_dump('true');
			// die;
		}
	}









}

?>