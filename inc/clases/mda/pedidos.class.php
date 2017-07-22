<?php


//se encargara de mostrar los pedidos pendientes de realizar
//y realizarlos
class Pedidos extends reserva_builder{
	
	public $pedido;
	public $idioma;
	public $anuncio;

	//objetos
	public $Builder;
	

	function __construct(){
		if(!empty($_POST['pedido'])){	$this->pedido  = $_POST['pedido'];	}
		if(!empty($_POST['idioma'])){	$this->idioma  = $_POST['idioma'];	}
		if(!empty($_POST['anuncio'])){	$this->anuncio = $_POST['anuncio'];	}

		parent::__construct();
		$this->Builder = new Builders();
	}




	//distinguir tipo de pedido (numero de pedido y pedido especifico)
	//====================================
	public function discernir_pedido($tiket, $num){

		$elemento = array();
		if($tiket == '00'){
			//traduccion, editando existente
			$elemento[1] = 'traduccion';
			$elemento[2] = $this->get_pedido_tradd($num,$tiket);
		}else{
			//traduccion nueva o banner nuevo
			$this->where('id',$tiket);
			if($salida = $this->getOne('reservas_efectivas','reserva')){
				$elemento[1] = $salida['reserva'];			
				if($salida['reserva'] == 'banner'){
					$elemento[2] = $this->get_pedido_bann($num);
				}else if($salida['reserva'] == 'traduccion'){
					$elemento[2] = $this->get_pedido_tradd($num);
				}else{	
					$elemento = false;	}	//no es parametro aceptado
			}else{	$elemento = false;	}	//no sale la consulta

		}

		return $elemento;
	}



	//info de traduccion mediante el numero de pedido
	//===========================================
	public function get_pedido_tradd($num,$tiket = NULL){

		if(!is_null($tiket)){
			//buscamos una traduccion para editar
			$tabla  = 'anuncios_idiomas';
			$Objeto = $this->Builder;
		}else{
			//es un pedido a realizar
			$tabla  = 'traducciones_pedidos';
			$Objeto = $this;
		}

		$Objeto->where('id',$num);
		if($salida = $Objeto->getOne($tabla)){
			return $salida;
		}
	}


	//info de banner mediante el numero de pedido
	//===========================================
	public function get_pedido_bann($num){
		$this->where('id',$num);
		if($salida = $this->getOne('banners_pedido')){
			return $salida;
		}else{
			return false;
		}
	}










	//mostrara las traducciones pendientes
	//===========================================
	public function show_pedidos_traduccion(){
		$return = '';
		$cols = array('id','user','anuncio','idioma','num_pedido','fecha_pedido');
		$this->where('estado','pendient');
		if($salida = $this->get('traducciones_pedidos',NULL,$cols)){
			$class_table = 'table table-striped table-hover table-bordered';
			$return .= '<table id="t_traducciones" class="'.$class_table.'">';
			//titulos
			$tit = array('Numero pedido','Usuario','Anuncio','Idioma','Fecha pedido','Opcion');
			foreach ($tit as $value) {
				$return .= '<th>'.$value.'</th>';
			}
			foreach ($salida as $key => $value) {
				$return .= '<tr rel="'.$value['id'].'" data="'.$value['num_pedido'].'">';
				$return .= '<td>'.$value['num_pedido'].'</td>
							<td>'.$value['user'].'</td>
							<td>'.$value['anuncio'].'</td>
							<td>'.$value['idioma'].'</td>
							<td>'.$value['fecha_pedido'].'</td>';
				$return .= '<td class="td_option"></td>';
				$return .= '</tr>';
			}
			$return .= '</table>';

		}else{
			$return = 'No hay pedidos';
		}
		return $return;
	}


	//show pedidos traducidos (opcion de editar)
	public function show_traducidos(){
		$return = '';

		$cols = array('id','anuncio','idioma','titulo','descripcion',
					  'fecha_realizacion','num_pedido');
		$this->Builder->where('num_pedido',array('>' => '0'));
		if($salida = $this->Builder->get('anuncios_idiomas',NULL,$cols)){
			$class_table = 'table table-striped table-hover table-bordered';
			$return .= '<table id="t_traducciones" class="'.$class_table.'">';
			//titulos
			$tit = array('Pedidos','Anuncio','Idioma','Titulo','Texto',
						 'Fecha realizacion','Opcion');
			foreach ($tit as $value) {
				$return .= '<th>'.$value.'</th>';
			}
			foreach ($salida as $key => $value) {
				$return .= '<tr rel="'.$value['id'].'" data="00">';
				$return .= '<td>'.$value['num_pedido'].'</td>
							<td>'.$value['anuncio'].'</td>
							<td>'.$value['idioma'].'</td>
							<td>'.$value['titulo'].'</td>
							<td>'.$value['descripcion'].'</td>
							<td>'.$value['fecha_realizacion'].'</td>';

				$return .= '<td class="td_option"></td>';
				$return .= '</tr>';
			}
			$return .= '</table>';

		}
		return $return;
	}















//respuesta ajax al pedido traduccion
//===============================================	
public function pedidio_es_traduccion($datos_pedido){
	//texto original del anuncio
	$return  = '';
	$tit_val = '';
	$tex_val = '';	
	$this->pedido  = $datos_pedido[2]['num_pedido'];
	$this->anuncio = $datos_pedido[2]['anuncio'];
	$this->idioma  = $datos_pedido[2]['idioma'];
	
	$cols = array('titulo','idioma','descripcion','anuncio');
	$this->Builder->where('anuncio',$datos_pedido[2]['anuncio']);
	//$this->Builder->where('idioma','esp');
	if($salida = $this->Builder->get('anuncios_idiomas',NULL,$cols)){
		//hidden de control del formuilario
		$return .= '<input type="hidden" name="mda_control" value="new_tradd"/> ';
		$return .= '<input type="hidden" name="pedido" value="'.$this->pedido.'"/> ';
		$return .= '<input type="hidden" name="anuncio" value="'.$this->anuncio.'"/> ';
		$return .= '<input type="hidden" name="idioma" value="'.$this->idioma.'"/> ';

		//mostrando todos los que hay, y en su caso al que editar
		foreach ($salida as $key => $value) {
			
			if($value['idioma'] == $this->idioma){
				$tit_val = $value['titulo'];
				$tex_val = $value['descripcion'];
			}else{
				$return .= '<h3>Titulo y descripcion en '.$value['idioma'].'</h3>';
				$return .= '<h4>'.$value['titulo'].'</h4>';
				$return .= '<p>'.$value['descripcion'].'</p>';
		}	}

		$return .= '<h3>Traducir a '.$this->idioma.'</h3>';
		$return .= '<h4>Titulo</h4>';
		$return .= '<input type="text" name="titulo" value="'.$tit_val.'" req="required" />';
		$return .= '<h4>Descripcion</h4>';
		$return .= '<textarea name="descripcion" req="required">'.$tex_val.'</textarea>';
						
		$return .= '<div class="foot-btn">
					<input type="submit" class="btn btn-warning" value="Completar" /></div>';

	}else{
		$return .= 'No hay texto original';
	}
	return $return;
}

	//mostrara los banners pendientes de realizar
	//===========================================
	public function show_pedidos_banners(){
		$return = '';
	
		//extraera banners donde confirmacion sea "pagado"
		//y realizacion no sea "completo"
		$cols = array('id','num_pedido','user','fecha_limite','tipo','imagen',
					  'caracteristicas', 'texto');
		$this->where('confirmacion','pagado');
		if($salida = $this->get('banners_pedido',NULL,$cols)){

			$return .= '<table id="t_traducciones" 
						 class="table table-striped table-hover table-bordered">';
			//titulos
			$tit = array('Numero pedido','Usuario','Tipo','Caracteristicas',
						 'Texto a incluir','Fecha Limite','Opcion');
			foreach ($tit as $value) {
				$return .= '<th>'.$value.'</th>';
			}
			foreach ($salida as $key => $value) {
				$return .= '<tr rel="'.$value['id'].'" data="'.$value['num_pedido'].'">';
				$return .= '<td>'.$value['num_pedido'].'</td>
							<td>'.$value['user'].'</td>
							<td>'.$value['tipo'].'</td>
							<td>'.$value['caracteristicas'].'</td>
							<td>'.$value['texto'].'</td>
							<td>'.$value['fecha_limite'].'</td>';

				$return .= '<td class="td_option"></td>';
				$return .= '</tr>';
			}
			$return .= '</table>';

			}else{
				$return = 'No hay nuevos pedidos';
			}

		return $return;

	}



	//respuesta ajax al pedido nuevo banner
	//===============================================	
	public function pedidio_es_nuevo_banner($datos_pedido){
		//texto original del anuncio
		//var_dump($datos_pedido);
		$return = '';
		$ruta   = 'imagenes/banners/pedidos/';
		$imagen = $ruta.$datos_pedido[2]['imagen'];
		$empresa 	  = $datos_pedido[2]['user'];
		$tipo_bann 	  = $datos_pedido[2]['tipo'];
		$this->pedido = $datos_pedido[2]['num_pedido'];
		$cols = array('titulo','descripcion');

		$return .= '<h3>Datos proporcionados por el usuario</h3>';
		$return .= '<h4>Imagen proporcionada por el usuario</h4>';
		$return .= '<img src="'.$imagen.'" />';
		$return .= '<h4>Texto</h4>';
		$return .= '<p>'.$datos_pedido[2]['texto'].'</p>';
		$return .= '<h4>Caracteristicas</h4>';
		$return .= '<p>'.$datos_pedido[2]['caracteristicas'].'</p>';

		$return .= '<h3>Subir banner</h3>';
		$return .= '<input type="hidden" name="pedido" value="'.$this->pedido.'"/>';
		$return .= '<input type="hidden" name="mda_control" value="new_bann"/>';
		$return .= '<input type="hidden" name="empresa" value="'.$empresa.'"/>';
		$return .= '<input type="hidden" name="tipo" value="'.$tipo_bann.'"/>';

		$return .= '<input id="imagenes" type="file" name="imagen" />';
		$return .= '<div class="foot-btn">
					<input type="submit" class="btn btn-warning" value="Completar" /></div>';


		return $return;
	}











	public function todas_reservas(){

		$titulos = array('user','producto','especificacion','fecha_inicio','fecha_final',
			'timestamp','estado');

		$return = '<table class="table table-striped table-hover table-bordered">';
		foreach ($titulos as $value) {
			$return .= '<th>'.$value.'</th>';
		}
		$this->orderBy('timestamp','Desc');
		$salida = $this->get('reservas_efectivas',null,$titulos);
		foreach ($salida as $key => $value) {
			$return .= '<tr>';
			$return .= '<td>'.$value['user'].'</td>
						<td>'.$value['producto'].'</td>
						<td>'.$value['especificacion'].'</td>
						<td>'.$value['fecha_inicio'].'</td>
						<td>'.$value['fecha_final'].'</td>
						<td>'.$value['timestamp'].'</td>
						<td>'.$value['estado'].'</td>';
			$return .= '</tr>';
		}
		$return .= '</table>';


		return $return;

	}




public function reservas_star(){

$titulos = array('Usuario','Timestamp','Producto','Especificacion','Fecha inicio',
	'Fecha final','Payerid','Transid','Estado');

$campos = array('user','timestamp','producto','especificacion','fecha_inicio',
	'fecha_final','payerid','transid','estado');


		$return = '<table class="table table-striped table-hover table-bordered">';
		foreach ($titulos as $value) {
			$return .= '<th>'.$value.'</th>';
		}

		$this->where('reserva','star_area');
		$this->orderBy('timestamp','Desc');
		$salida = $this->get('reservas_efectivas',null,$campos);
		foreach ($salida as $key => $value) {
			$return .= '<tr>';
			$return .= '<td>'.$value['user'].'</td>
						<td>'.$value['timestamp'].'</td>
						<td>'.$value['producto'].'</td>
						<td>'.$value['especificacion'].'</td>
						<td>'.$value['fecha_inicio'].'</td>
						<td>'.$value['fecha_final'].'</td>
						<td>'.$value['payerid'].'</td>
						<td>'.$value['transid'].'</td>
						<td>'.$value['estado'].'</td>';
			$return .= '</tr>';
		}
		$return .= '</table>';

		return $return;

}









public function mostrar_reservas($reserva){

if($reserva == 'star_area'){$espe = 'Provincia';		}
if($reserva == 'special'){	$espe = 'Seccion';			}
if($reserva == 'banner'){	$espe = 'Tipo de banner';	}

$titulos = array('Usuario','Timestamp','Producto',$espe,'Fecha inicio',
	'Fecha final','Payerid','Transid','Estado');

$campos = array('user','timestamp','producto','especificacion','fecha_inicio',
	'fecha_final','payerid','transid','estado');


		$return = '<table class="table table-striped table-hover table-bordered">';
		foreach ($titulos as $value) {
			$return .= '<th>'.$value.'</th>';
		}

		$this->where('reserva',$reserva);
		$this->orderBy('timestamp','Desc');
		$salida = $this->get('reservas_efectivas',null,$campos);
		foreach ($salida as $key => $value) {
			$return .= '<tr>';
			$return .= '<td>'.$value['user'].'</td>
						<td>'.$value['timestamp'].'</td>
						<td>'.$value['producto'].'</td>
						<td>'.$value['especificacion'].'</td>
						<td>'.$value['fecha_inicio'].'</td>
						<td>'.$value['fecha_final'].'</td>
						<td>'.$value['payerid'].'</td>
						<td>'.$value['transid'].'</td>
						<td>'.$value['estado'].'</td>';
			$return .= '</tr>';
		}
		$return .= '</table>';

		return $return;

}











}







?>