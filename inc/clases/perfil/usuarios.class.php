<?php

//funciones para manejar en perfil de usuario

//include_once'inc/clases/builders.class.php';

class Usuarios extends builders {
	


	public $tabla;
	public $tipo_usuario;
	public $username;
	public $empresa;
	
	//objetos
	public $Reserva;


	public function __construct(){
		parent::__construct();	
		if($this->tipo_usuario == 'empresa'){	$this->empresa = $this->obten_empresa();}

		$this->Reserva = new reserva_builder();
	}	
	
	private function obten_empresa(){
		$this->Perfil->where('id',$this->user);
		$s = $this->Perfil->getOne('perfiles_emp','empresa');
		return $s['empresa'];
	}

	//saca una imagen, la principal del anuncio, sin links
	//======================================================
	public function get_one_img($id_e, $ussr){

		//sacara imagen desderoot o desde ajax
		if(!file_exists("imagenes/anuncios_img/".$ussr)){
			if(!file_exists("../../imagenes/anuncios_img/".$ussr)){
				$ussr = 'par';	
			}
		}

		$this->where('id_e', $id_e);
		$this->orderBy('principal','desc');
		if(!$salida = $this->getOne('anuncios_img','img')){
			return false;
		}else{
			//devolvemos array de imagenes
			return '<img src="imagenes/anuncios_img/'.$ussr.'/'.$salida['img'].'"/>';
		}
	}


	//mostrar anuncios (seleccionables para upload_img, star_area, puede que promocionar)
	//funciona bien para upload_img, añade enlace 
	//en star codifica md5() id del anuncio
	//en promo busca que sean anuncios sin promocionar y añade para jq
	public function seleccione_anuncio($tipus){

		$return = '';
		$destino = '';
		$visual = '';
		$link = 'href="index.php?perfil=';

		$cols = array('id','clave_interna', 'provincia','tipo_venta');
		$this->where('ussr',$this->user);
		$this->where('apto',1);	//el anuncio debe ser apto

		if($tipus == 'promo'){		//si es promo añadimos un criterio a la busqueda
			$this->where('anuncio_promocionado',array('not in'=> array(1)));
		}

		if($salida = $this->get('anuncios',NULL,$cols)){
			$return .= '<h3>seleccione anuncio</h3>';

			$return .= '<table class="table table-striped table-hover table-bordered">
							<tr>
								<th>Imagen</th><th>Clave interna</th><th>Zona</th>
								<th>Poblacion</th><th>Tipo</th><th>Seleccion</th>
							</tr>';
			foreach($salida as $value){
				$imagen = $this->get_one_img(md5($value['id']),$this->user);
				if($tipus == 'anuncio'){$destino = $link.'2&art='.md5($value['id']).'"';}
				if($tipus == 'upload'){ $destino = $link.'3&art='.md5($value['id']).'"';}
				if($tipus == 'idioma'){ $destino = $link.'4&art='.md5($value['id']).'"';}
				
				if($tipus == 'star_area'){$value['id'] = md5($value['id']);}
				if($tipus == 'promo'){$visual = "visual='off'";}

				$return .= '<tr class="anuncio_pro" data-ref="'.$value['id'].'" '.$visual.'>
								<td><a '.$destino.'>'.$imagen.'</a></td>
								<td>'.$value['clave_interna'].'</td>
								<td>'.$value['provincia'].'</td>
								<td></td>
								<td></td>
								<td class="marcador_pro"></td>
							</tr>';
			}
			$return .= '</table>';

			return $return;
		}else{
			$return = '<p class="msj_p">No hay anuncios todavia</p>';
			return $return;
		}
		


	}



	public function seleccione_promocionados(){
		$cols = array('id','ussr','clave_interna', 'provincia','tipo_venta');

		$return = '<div class="row">';
		$this->where('ussr',$this->user);
		$this->where('apto',1);	//el anuncio debe ser apto
		$this->where('anuncio_promocionado',array('not in'=> array(1)));
		if($salida = $this->get('anuncios',NULL,$cols)){
			foreach ($salida as $value) {
				$id_md   = md5($value['id']);
				$src_imagen = $this->sacar_solo_foto($id_md, $value['ussr']);
				$return .= '<div style="margin-bottom:30px" class="col-sm-3 col-xs-6">';
				$return .= '<img class="img-thumbnail" src="'.$src_imagen.'" />';
				
				//$return .= 'src="imagenes/anuncios_img/'.$this->folder.'/small/small_'.$value['img'].'" />';
				$return .= '<div class="gallery-cont">';
				$return .= '<input type="checkbox" name="promocionar[]" value="'.$id_md.'" />';
				$return .= '</div>';
				$return .= '</div>';				
			}
		}

		$return .= '</div>'; //row

		return $return;


	}



	//inserta input para anuncio/idioma
	//=====================================
	private function insert_input_tradd($anuncio,$idiomas){
		$return = '';
		//si no existe el idioma lo insertamos, si no hay idiomas insertamos los dos
		$eng = '<input type="checkbox" name="tradd[]" value="'.$anuncio.'_eng" />Ingles';
		$ger = '<input type="checkbox" name="tradd[]" value="'.$anuncio.'_ger" />Aleman';

		//si el idioma esta en alguna de las tres arrays borra la opcion

		foreach ($idiomas as $value) {
				if(in_array('eng', $value)){$eng = '';}
				if(in_array('ger', $value)){$ger = '';}
		}
		$return = $eng.$ger;

		return $return;
	}

	//comprobando que idiomas hay que ofrecer
	private function idiomas_info($anuncio, $idioma){
		$return['actuales'] = array();
		$return['reservados'] = array();

		//traducciones actuales
		$this->where('anuncio',$anuncio);
		if ($actuales = $this->get('anuncios_idiomas',NULL,'idioma')) {
			foreach ($actuales as $value) {
				if (is_array($value)) {
					foreach ($value as $value2) {
						array_push($return['actuales'], $value2);
		}	}	}	}

		//pendientes de traduccion
		$this->Reserva->where('anuncio',$anuncio);
		if ($reservados = $this->Reserva->get('traducciones_pedidos',NULL,'idioma')) {
			foreach ($reservados as $value) {
				if (is_array($value)) {
					foreach ($value as $value2) {
						array_push($return['reservados'], $value2);
		}	}	}	}

		//segun el anuncio
		$pos = strpos($idioma,',');
		if($pos !== false){
			$idi = explode(',',$idioma);
			$return['anunciados'] = $idi;
		}
		return $return;
	}

	//seleccione los anuncios que desea traducir
	//===================================================
	public function select_to_translate(){
		$return = '';
		$cols = array('id','clave_interna','provincia','tipo_venta','idiomas');
		$this->where('ussr',$this->user);
		$this->where('apto',1);	//anuncio debe ser apto
		if($salida = $this->get('anuncios',NULL,$cols)){

			$return .= '<table class="table table-striped table-hover table-bordered">
						<tr><th>Imagen</th><th>Clave interna</th><th>Poblacion</th>
						<th>Tipo</th><th>Idiomas actuales</th><th>Idiomas en proceso</th>
						<th>Traducir al</th></tr class="tradd_selection">';

			foreach ($salida as $key => $value) {
					//si $value['idiomas'] haya una ,  explode,
				$imagen = $this->get_one_img(md5($value['id']),$this->user);
				$return .= '<tr class="anuncio_tradd">
								<td>'.$imagen.'</td>
								<td>'.$value['clave_interna'].'</td>
								<td>'.$value['provincia'].'</td>
								<td>'.$value['tipo_venta'].'</td>
								<td>'.$value['idiomas'].'</td>';
				$idiomas = $this->idiomas_info($value['id'],$value['idiomas']);								

				//si hay idiomas en proceso
				$return .= 		'<td>';
				if(!empty($idiomas['reservados'])){
					$res = implode(',',$idiomas['reservados']);
					$return .= $res;
				}
				
				$return .= '	</td>';
				//idiomas que se pueden adquirir
				$return .= 		'<td>';
				$return .= $this->insert_input_tradd($value['id'],$idiomas);
				$return .= '	</td>
							</tr>';

			}

			$return .= '</table>';

			return $return;

		}
	}



	//saca una tabla facil pasandole arrays de titulo y filas
	//=========================================================
	public function easy_table($columnas,$filas){
		$return = '';
		$return .= '<table class="table table-stripped">';
		foreach ($columnas as $value) {
			$return .= '<th>'.$value.'</th>';
		}
		foreach ($filas as $key => $value) {
			$return .= '<tr>';
			foreach ($value as $key2 => $value2) {
				$return .= '<td>'.$value2.'</td>';
			}
			$return .= '</tr>';
		}
		$return .= '</table>';
		return $return;
	}


	//sin tipo es 2(paquetes) con tipo es 1(services)
	//junto con ajax_renew renovaran anuncios existentes por toda la galaxia
	//(tipo=null) = services /(tipo=renew) = renovar /(tipo=rebuy) = recomprar
	//=========================================================
	public function easy_renovator($columnas,$filas,$especie = NULL){
		$salt = $this->Perfil->select_salt();
		$texto    = 'Renovar';
		if(!is_null($especie)){
			$tipo = 1;
			$response = 'renew-response-1';
			if($especie == 'rebuy'){
				$response 	= 'renew-response-recomprator';  
				$texto 		= 'Recomprar';}
		}else{
			$tipo = 2;
			$response = 'renew-response-2';
		}
		echo '<table class="table table-striped table-hover table-bordered">';
		foreach ($columnas as $value) {	echo '<th>'.$value.'</th>';	}
		foreach ($filas as $key => $value) {
			$electo = $value['id'];
			unset($value['id']);
			echo '<tr>';
			foreach ($value as $key2 => $value2) {
				echo '<td>'.$value2.'</td>';
			}
				echo '<td><a class="btn btn-info ajax-renew" tipo="'.$tipo.'" rel="'.$electo.'" 
							 data-modder="'.$salt.'">'.$texto.'</a></td>';
			echo '</tr>';
		}
		echo '</table>';

	}	


	//mostrar info de tabla reservas_efectivas
	//$option es una accion que pueda hacerle (borrar, renovar)
	//=============================================
	public function efectivas_renew($reserva = NULL){

		$cols = array('id','reserva','especificacion','fecha_inicio','fecha_final','estado');
		$fail = 'No hay nada que renovar';
		if(!is_null($reserva)){
			$this->Reserva->where('reserva',$reserva);
			$fail = 'No hay '.$reserva.' que renovar';
		}
		$this->Reserva->where('user',$this->user);
		if($salida = $this->Reserva->get('reservas_efectivas',NULL,$cols)){
			$titulos = array('reserva','especificacion','fecha inicio','fecha final','estado','opcion');
			return $this->easy_renovator($titulos ,$salida);
		}else{
			$return = '<p class="msj_p">'.$fail.'</p>';
			return $return;
		}
	}
	//mostrar efectivas(solo molstrar)
	//===============================================
	public function mostrar_efectivas($reserva = NULL){
		$cols = array('reserva','especificacion','fecha_inicio','fecha_final','estado');
		if(!is_null($reserva)){
			$this->Reserva->where('reserva',$reserva);
		}
		$this->Reserva->where('user',$this->user);
		if($salida = $this->Reserva->get('reservas_efectivas',NULL,$cols)){
			$titulos = array('reserva','especificacion','fecha inicio','fecha final','estado');
			return $this->easy_table($titulos,$salida);
		}else{
			return false;
		}
	}



	//seleccion para compra de paquetes
	//=================================================
	public function seleccion_paquete($tipos, $duracion){
		$return  = '<table id="paquetes_tabla" class="table table-striped table-hover table-bordered">';
		$return .= '<tr><td class="title_table">Anuncios</td><td>';
		$return .= '<select name="paquete">';
		$return .= '<option value="null">Seleccione cantidad</option>';
			foreach ($tipos as $key => $value) {
				$return.= '<option>'.$value.'</option>';
			}
		$return .= '</select></td></tr>';	
		$return .= '<tr><td class="title_table">Duracion</td><td>';
		$return .= '<select name="periodo">';
		$return .= '<option value="null">Seleccione duracion</option>';
			foreach ($duracion as $key => $value) {
				$return.= '<option>'.$value.'</option>';
			}
		$return .= '</select></td></tr>';
						
		$return .= '</table>';
	
		return $return;
	}


	//mostrar renew paquetes actuales
	//=============================================
	public function paquetes_actuales_renew(){
		$cols= array('id','paquete','anuncios','fecha_inicio','fecha_final','duracion',
					 'duracion_total','full');
		$this->Reserva->where('user',$this->user);
		$this->Reserva->where('estado',array('in' => array(1,2)));

		if($salida = $this->Reserva->get('paquetes',NULL,$cols)){
			$titulos = array('paquete','anuncios','fecha inicio','fecha final','duracion',
							 'duracion total','completo','opcion');
			return $this->easy_renovator($titulos,$salida ,'renew');
		}else{
			$return = '<p class="msj_p">No ha adquirido ningun paquete todavia</p>';
			return $return;
		}
	}

	//mostrar recomprar paquetes actuales
	//=============================================
	public function paquetes_recomprar(){
		$cols= array('id','paquete','anuncios','fecha_inicio','fecha_final','duracion',
					 'duracion_total','full');
		$this->Reserva->where('user',$this->user);
		$this->Reserva->where('estado',3);
		if($salida = $this->Reserva->get('paquetes',NULL,$cols)){
			$titulos = array('paquete','anuncios','fecha inicio','fecha final','duracion',
							 'duracion total','completo','opcion');
			return $this->easy_renovator($titulos,$salida ,'rebuy');
		}else{
			$return = '<p class="msj_p">No hay ningun paquete que recuperar</p>';
			return $return;
		}
	}

	//mostrar info paquetes actuales
	//=============================================
	public function mostrar_paquetes_actuales(){
		$cols= array('paquete','anuncios','fecha_inicio','fecha_final','duracion','duracion_total','full');
		$this->Reserva->where('user',$this->user);
		if($salida = $this->Reserva->get('paquetes',NULL,$cols)){
			$titulos = array('paquete','anuncios','fecha inicio','fecha final','duracion',
							 'duracion total','completo');
			return $this->easy_table($titulos,$salida);		
		}else{
			return false;
		}
	}



//devuelve tablacon anuncios promocionados
public function get_promocionados($Fecha){

	$anuncios = array();
	$cols = array('id', 'clave_interna', 'provincia','municipio', 'tipo_venta', 
				  'anuncio_promocionado', 'promo_fecha_inicio', 'promo_fecha_final');

	$this->where('ussr',$this->user);
	if($salida = $this->get('anuncios', NULL, $cols)){

		$return  = '<table class="table table-striped table-hover table-bordered" >';
		$return .= '<tr>
				<th>Imagen</th><th>Clave interna</th><th>Provincia</th>
				<th>operacion</th><th>Fecha inicio</th><th>Fecha final</th>
			</tr>';		

		foreach($salida as $value){
			if($value['anuncio_promocionado'] == 1){

				$fi = $Fecha->hacerLegible($value['promo_fecha_inicio']);
				$ff = $Fecha->hacerLegible($value['promo_fecha_final']);
				
				$imagen = $this->sacar_foto(md5($value['id']),$this->user);
				$return .='<tr data-ref="'.$value['id'].'">
						<td>'.$imagen.'</td>
						<td class="ref">'.$value['id'].'</td>
						<td>'.$value['provincia'].'<br />'.$value['municipio'].'</td>
						<td>'.$value['tipo_venta'].'</td>
						<td>'.$fi[1].'/'.$fi[2].'/'.$fi[3].'</td>
						<td>'.$ff[1].'/'.$ff[2].'/'.$ff[3].'</td>
					 </tr>';
			}
		}
		$return .='</table>';
		return $return;

	}else{
		return '<p class="msj_p">No hay anuncios promocionados todavia.</p>';
	}


}

//devuelve array con anuncios star
public function get_anuncios_star(){
	$star = array();
	$cols = array('id', 'provincia', 'municipio', 'tipo_venta', 'anuncio_promocionado','anuncio_e');
	$this->where('ussr',$this->user);
	$salida = $this->get('anuncios', NULL, $cols);
		
	if ($this->count==0){
		$star['saltador'] = '';
		$star['saltador_input'] = '';
		$star['mostrar'] = '<p class="msj_p">No hay anuncios </p>';
	}else{	
		
		$i=1;
		$star['saltador'] = $this->Perfil->select_salt();
		$star['saltador_input'] = '<input type="hidden" name="saltador" value="'.$star['saltador'].'"/>';
		$star['mostrar'] = $this->seleccione_anuncio('star_area');
	}
	return $star;
}

//obtiene los banners ya existentes en catalogo de usuario
public function banners_existentes(){
	$banners = '';
	$campos = array('superior', 'lateral', 'central');
	$this->Reserva->where('user',$this->user);

	if($salida = $this->Reserva->getOne('banners_catalogo',$campos)){
		foreach($salida as $key => $value){
			if($value != '0'){
				$banners.= '<option value="'.$key.'">Renovar '.$key.'</option>';
			}
		}
	}
	return $banners;
}




//obtioen datos de perfil de empresa
public function get_datos_for_empresa(){
	$cols = array('img','descripcion','descripcion_larga','web');
	$this->Perfil->where('id',$this->Perfil->user);
	$datos = $this->Perfil->getOne('perfiles_emp',$cols);

	$cols = array('img_fondo');
	$this->Perfil->where('id',$this->Perfil->user);
	$datos2 = $this->Perfil->getOne('empresa_fondo',$cols);

	$datos['img'] = 'imagenes/logo/'.$datos['img'];
	$datos['img_fondo'] = 'imagenes/fondos/'.$datos2['img_fondo'];	

	return $datos;
}

//obtiene datos de perfil de usuario
public function get_datos_for_user(){

	$cols = array('user_telefono','user_email');
	$this->Perfil->where('id',$this->Perfil->user);
	$datos = $this->Perfil->getOne('usuarios',$cols);	
	return $datos;
}



	//cargara los idiomas existentes en anuncio o morira en el intento
	public function cargar_idiomas($anuncio){
		$return = '';
		$this->where('anuncio',$anuncio);
		if($idiomas_desc = $this->get('anuncios_idiomas')){
			foreach($idiomas_desc as $value){
				$return .= '<div class="panel panel-default">';
				$return .= '<div class="panel-heading" data-original-title="">';
				$return .= '<h2><i class="fa fa-list"></i><span class="break"></span>'.$value['titulo'].'</h2>';
				$return .= '</div>';
				$return .= '<div class="panel-body">';
				$return .= '<p>'.$value['descripcion'].'</p>';
				$return .= '</div>';
				$return .= '</div>';
				
			}	
		}else{
			$return .= 'Ningun idioma extra en el anuncio'; 
		}
		return $return;
	}


				



//usado para el login de mda
public function logueador_mda(){

	if( ($this->tipo_usuario == 'mda') && ($this->user == USER_MDA) ){
		//continuo
		if(empty($_SESSION['mda_id'])){
			//no esta logueado como mda
			return true;
		}else{	
			if($_SESSION['mda_id'] != '1'){
				return false;	//no es el que toca
		}	}
	}else{
		return false;
	}
}




//devuelve estado de anuncios del usuario
private function info_anuncios_user(){
	//una destinada a anuncios a marcar no aptos, desactivados y borradores
	$anuncios['no_aptos'] 	  = 0;
	$anuncios['desactivados'] = 0;
	$anuncios['borradores']	  = 0;

	$cols = array('apto','activo');
	$this->where('ussr',$this->user);
	$s = $this->get('anuncios', NULL,$cols);
	foreach ($s as $value) {
		if ($value['apto'] == 0) {	  $anuncios['no_aptos']++;		}
		if ($value['activo'] == 0) {  $anuncios['desactivados']++;	}
	}
	$this->reset();

	$this->where('ussr',$this->user);
	$s = $this->get('anuncios_borradores',NULL,'id');
	$anuncios['borradores'] = count($s);
	
	return $anuncios;
	//otro destinado a perfil no apto

	//otro destinado a paquetes
}

//devuelve el estado del perfil de usuario
private function info_perfil_user(){
	$cols = array('apto','visible');
	$this->Perfil->where('id',$this->user);
	$s = $this->Perfil->getOne('perfiles_emp',$cols);
	$a['apto'] = $s['apto'];
	$a['visible'] = $s['visible'];
	return $a;
}

public function show_info_user(){

	$on = '';
	$ret_empresa = '';

	$anuncios = $this->info_anuncios_user();
	if($this->tipo_usuario =='empresa'){
		$perfil_estado = $this->info_perfil_user();
		foreach ($perfil_estado as $key => $value) {
			if($value == 0){
				$on = '<span class="badge">!</span>';
				$pe[$key] = '<span class="label label-danger">No</span>';
			}else{
				$pe[$key] = '<span class="label label-success">Si</span>';
			}
		}	

		$ret_empresa .='<li class="dropdown-menu-header text-center"><strong>Perfil de Empresa</strong></li>';
		$ret_empresa .='<li><a href="index.php?perfil=1"><i class="fa fa-check"></i> Perfil apto '.$pe['apto'].'</a></li>';
		$ret_empresa .='<li><a href="index.php?perfil=1"><i class="fa fa-eye"></i> Perfil visible '.$pe['visible'].'</a></li>';
	

	}
	
	foreach ($anuncios as $value) {
		if($value != 0){$on = '<span class="badge">!</span>';}
	}


	$return  ='<li class="dropdown visible-md visible-lg">';
	$return .='<a href="index.html#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-settings"></i>'.$on.'</a>';
	$return .='<ul class="dropdown-menu">';
	$return .='<li class="dropdown-menu-header text-center"><strong>Anuncios</strong></li>';
	$return .='<li><a href="index.php?perfil=1"><i class="fa fa-times"></i> No aptos <span class="label label-danger">'.$anuncios['no_aptos'].'</span></a></li>';
	$return .='<li><a href="index.php?perfil=1"><i class="fa fa-exclamation-triangle"></i> Desactivados <span class="label label-warning">'.$anuncios['desactivados'].'</span></a></li>';
	$return .='<li><a href="index.php?perfil=1"><i class="fa fa-paperclip"></i> Borradores <span class="label label-info">'.$anuncios['borradores'].'</span></a></li>';
	$return .= $ret_empresa;
	$return .='</ul>';
	$return .='</li>';

	return $return;
		

		
}



public function show_sede_central(){
	$return = '';
	$movil2 = '';
	$this->Perfil->where('empresa',$this->Perfil->user);
	$this->Perfil->where('sede_central',1);
	if($salida = $this->Perfil->get('empresa_oficinas')){
		foreach ($salida as $key => $value) {
			if(!empty($value['movil2'])){
				$movil2 = '<i class="fa fa-mobile"></i> Movil2 : '.$value['movil2'].'<br>';
			}
			$return .= '<div class="oficina-sede">';
			$return .= '<img src="imagenes/oficinas/'.$value['img'].'" alt="" title="'.$value['nombre'].'"/>';
			$return .= '<h4>'.$value['nombre'].'</h4>';
			$return .= '<h5>'.$value['poblacion'].'</h5>';		
			$return .= '<p><i class="fa fa-phone"></i> Oficina : '.$value['tel'].'<br>';
			$return .= '<i class="fa fa-mobile"></i> Movil : '.$value['movil'].'<br>';	
			$return .= $movil2;
			$return .= '<i class="fa fa-print"></i> Fax : '.$value['fax'].'<br>';	
			$return .= '<i class="fa fa-envelope-o"></i> Email : '.$value['email'].'</p>';
			$return .= '<div class="alert alert-success" role="alert">Estos seran los datos de contacto
			que se mostraran para cualquiera de sus anuncios</div>';
			$return .= '</div>';	
		}
	}
	return $return;
}


public function show_oficinas_agentes_dropdown($caso,$array,$salt){
	$cesd = '';
	if($caso == 'oficina'){
		if($array['sede_central'] == 0){
			$cesd = '<li><a href="#" data-subject="'.$array['id'].'" data-action="central"
			 saltador="'.$salt.'">Convertir en sede central</a></li>';			
		}
	}

	if($array['activo'] == 1){
		$vision = 'ocultar';
	}else{
		$vision = 'mostrar';
	}


	$return = '<div class="dropdown" rel="'.$caso.'">
	  <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenu1" 
	  	data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
	    Opciones
	    <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu options-'.$caso.'" aria-labelledby="dropdownMenu1">
	    '.$cesd.'
	    <li><a href="#" data-subject="'.$array['id'].'" data-action="'.$vision.'" saltador="'.$salt.'">'.$vision.'</a></li>
	    <li><a href="#" data-subject="'.$array['id'].'" data-action="eliminar" saltador="'.$salt.'">Eliminar</a></li>
	  </ul>
	</div>';

return $return;

}

public function show_oficinas_perfil(){

	$return  = '';
	$salt = $this->Perfil->select_salt();		
	$this->Perfil->where('empresa',$this->Perfil->user);
	$this->Perfil->orderBy('sede_central','DESC');
	if($salida = $this->Perfil->get('empresa_oficinas')){

		foreach ($salida as $key => $value) {	
			$sede   = '';
			$movil2 = '';	
			$activo = '';		

			if($value['sede_central'] == 1){$sede = '<h3>Sede Central</h3>';}
			if(!empty($value['movil2'])){
				$movil2 = '<i class="fa fa-mobile"></i> Movil2 : '.$value['movil2'].'<br>';
			}
			if($value['activo'] == 0){
				$activo = '<span class="alert alert-danger" role="alert">Oculto</span>';
			}
			$return .= '<div class="col-sm-12 oficina-vista"><div class="row">';
			$return .= '<div class="col-sm-12 col-md-3 oficina_img">';
			$return .= '<img src="imagenes/oficinas/'.$value['img'].'" alt="" title="'.$value['nombre'].'"/>';
			$return .= '</div>';
			$return .= '<div class="col-sm-12 col-md-9">';
			$return .= '<div class="oficina-desc">';	
			$return .= $sede;		
			$return .= '<h4>'.$value['nombre'].'</h4>';
			$return .= '<h5>'.$value['poblacion'].'</h5>';		
			$return .= '<p><i class="fa fa-phone"></i> Oficina : '.$value['tel'].'<br>';
			$return .= '<i class="fa fa-mobile"></i> Movil : '.$value['movil'].'<br>';	
			$return .= $movil2;
			$return .= '<i class="fa fa-print"></i> Fax : '.$value['fax'].'<br>';	
			$return .= '<i class="fa fa-envelope-o"></i> Email : '.$value['email'].'</p>';
			$return .= '</div>';

			$return .= $this->show_oficinas_agentes_dropdown('oficina',$value,$salt);
			$return .= $activo;	
			$return .= '</div>';	
			$return .= '</div></div>';	

		}
	}else{
		$return = 'No hay Oficinas Inscritas. Porfavor inscriba almenos una.';
	}
	return $return;
}



public function show_agentes_perfil(){
	$return = '';
	$salt = $this->Perfil->select_salt();	
	$this->Perfil->where('empresa',$this->Perfil->user);
	if($salida = $this->Perfil->get('empresa_agentes')){
		$return .= '<div class="row">';
		foreach ($salida as $key => $value) {
			$activo = '';
			if($value['activo'] == 0){
				$activo = '<span class="alert alert-danger" role="alert">Oculto</span>';
			}			
			$return .= '<div class="col-sm-4 agente-vista">';
			$return .= '<div class="thumbnail">';
			$return .= '<img src="imagenes/agentes/'.$value['img'].'" alt="'.$value['nombre'].'">';
			$return .= '<div class="caption">';
			$return .= '<h3>'.$value['nombre'].'</h3>';
			$return .= '<h4>'.$value['cargo'].'</h4>';
			$return .= '<h5>'.$value['idiomas'].'</h5>';

			$return .= '<p><i class="fa fa-mobile"></i> Movil : '.$value['movil'].'<br>';			
			$return .= '<i class="fa fa-envelope-o"></i> Email : '.$value['email'].'</p>';
			$return .= '</div>';	

			$return .= $this->show_oficinas_agentes_dropdown('agente',$value,$salt);
			$return .= $activo;					

			$return .= '</div>';		
			$return .= '</div>';
		}
		$return .= '</div>';
	}else{
		$return = 'No hay asesores inmobiliarios inscritos.';
	}
	return $return;
}




	//clase destruct
	//======================================
	public function __destruct(){
		parent::__destruct();
	}




}//clase Usuarios







?>