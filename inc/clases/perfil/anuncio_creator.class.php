<?php

require_once'inc/clases/perfil/usuarios.class.php';

class anuncio_creator extends usuarios{

	public $huecos_totales;
	public $anuncios_actuales;
	public $paquete_elegido;
	public $estado_anuncio;
	public $anuncio;
	public $val = array();


	public function __construct(){
		parent::__construct();
		$this->art 			  = '';
		$this->hiddden_borr   = NULL;
		$this->tabla 		  = 'anuncios';
		$this->estado_anuncio = 'nuevo';
		$this->anuncios_actuales = $this->anuncios_actuales();

		if (!empty($_GET['borr'])){
			if($this->borrador_propietario($_GET['borr']) == true){
				$this->tabla 	= 'anuncios_borradores';
				$this->estado_anuncio 	= 'borrador';
				$this->hiddden_borr 	= $_GET['borr'];
			}
		}
		if(!empty($_GET['art'])){
			if($this->anuncio = $this->resolver_enigma($_GET['art'])){
				$this->art 		= $_GET['art'];
				$this->tabla 	= 'anuncios';
				$this->estado_anuncio = 'actualizar';
			}
		}
		//establece paquete correspondiente al anuncio
		$this->paquete_correspondiente();
	}


	//obtener anuncio concreto
	private function get_anuncio(){
		//en metodo para anuncios tanto anuncio como tabla estarian en propiedades
		$this->where('id',$this->anuncio);
		if($res = $this->getOne($this->tabla)){
			return $res;
		}else{
			return false;
		}
	}

	//anuncios actuales (cantidad de anuncios que tiene actualmente un usuario)
	//===================================================================
	private function anuncios_actuales(){
		$this->where('ussr',$this->user);
		$salida = $this->get('anuncios');
 		return count($salida);
	}

	//devuelve paquete en el que meter el nuevo anuncio sino false
	//====================================================
	public function paquete_correspondiente(){

		$paquete = array();
		$numero  = array();
		$cols 	 = array('id', 'paquete', 'full');
		$return  = array('num_anuncios' => 0 ,
						 'paquete_elegido' =>'');

		$this->Reserva->where('user',$this->user);
		$this->Reserva->orderBy('paquete','asc');
		$this->Reserva->orderBy('id','asc');

		//coje todos los paquetes de ese usuario
		if($paquetes = $this->Reserva->get('paquetes', NULL, $cols)){
			//enumero paquetes, quito los paquetes y completos sumo los anuncios 
			foreach($paquetes as $key => $value){
				$this->huecos_totales = $this->huecos_totales + $value['paquete'];
				if($value['full'] == 1){
					unset($paquetes[$key]);
				}
			}

			//si quedan paquetes escojo el primero disponible
			if(!empty($paquetes)){
				foreach($paquetes as $value){
					$this->paquete_elegido = $value['id'];
					break;
				}
			}else{
				$this->paquete_elegido = false;
			}

		}else{
			$this->paquete_elegido = false;
		}
		//$anuncios_totales = $datos['num_anuncios'];
		//$paquete_elegido = $datos['paquete_elegido'];

	}

	//calcula cuantos anuncios caben en los paquetes que tiene
	public function huecos_restantes(){
		$hr = $this->huecos_totales - $this->anuncios_actuales;
		return'<h5>anuncios restantes '.$hr.'</h5>';
	}

	//añadimos al formulario cuando sea un borrador
	public function hidden_borrator(){
		if(!is_null($this->hiddden_borr)){
			return '<input type="hidden" name="ex_borr" value="'.$this->hiddden_borr.'"/>';
		}
	}


	// //devuelve la opcion de hacer borrador o no, segun tipo usuario
	// public function old_opcion_borrador(){
	// 	$borrador = '';
	// 	if($this->tipo_usuario == 'empresa'){
	// 	$borrador = '<div class="check-friend btn btn-default">
	//                     <input type="checkbox" id="borrador" name="borrador" value="true" />
	//                     <p>Guardar como Borrador</p> 
	//                 </div>';
	// 	}
	// 	return $borrador;
	// }



	//devuelve la opcion de hacer borrador o no, segun tipo usuario
	public function opcion_borrador(){
		$borrador = '';
		if($this->tipo_usuario == 'empresa'){
		$borrador = '<div class="borrador-cont">
						<label class="checkbox-inline" for="borrador">
			            	<input type="checkbox" id="borrador" name="borrador" value="true" />
			            	Guardar como Borrador
			          	</label>
	                </div>';
		}
		return $borrador;
	}






	//comprueba que el borrador que trata sea del user actual
	//========================================
	public function borrador_propietario($borr){
		$this->where('id',$borr);
		if($salida = $this->getOne('anuncios_borradores','ussr')){
			if($this->user == $salida['ussr']){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}




	//devolvera un array con los values definidos para el form
	public function definiendo_values(){
		$definidos = array();
		if($this->estado_anuncio == 'nuevo'){
			$values_ = array('art','v_provincia','v_municipio','v_t_in','v_st_in',
							 'v_tipoventa','v_precio','v_superf','v_rooms','v_banos','v_clave',
							 'v_direccion','v_dir_d','v_dir_p');

			foreach ($values_ as $key => $value) {
				$definidos[$value] = '';
			}
			$definidos['v_extras'] = NULL;
			$definidos['v_paquete'] = $this->paquete_elegido;

		}else{
			if($res = $this->get_anuncio()){
						
				//pequeñas escepciones
				if($this->estado_anuncio == 'borrador'){
					$definidos['v_paquete'] = $this->paquete_elegido;
				}else{
					$definidos['v_paquete']	= $res['paquete'];
				}	

				$definidos['v_provincia']   = $res['provincia'];
				$definidos['v_municipio']   = $res['municipio'];
				$definidos['v_t_in']  = $res['tipo_inmueble'];
				$definidos['v_st_in'] = $res['subtipo_inmueble'];
				$definidos['v_tipoventa']   = $res['tipo_venta'];
				$definidos['v_precio'] 	   	= $res['precio'];	
				$definidos['v_superf']	   	= $res['superficie'];
				$definidos['v_rooms'] 	   	= $res['habitaciones'];	
				$definidos['v_banos'] 	   	= $res['banos'];	
				$definidos['v_clave'] 	   	= $res['clave_interna'];
				$definidos['v_direccion']   = $res['direccion'];
				$definidos['v_dir_d']   	= $res['direccion_data'];
				$definidos['v_dir_p']   	= $res['direccion_permisos'];
				$definidos['v_extras']		= explode(',', $res['extras']);
					
			}else{
				$definidos = NULL;
			}	
		}
		$this->val = $definidos;
		//return $definidos;	
	}


	//GOOGLE MAPS
	//sujerencia de direccion
	private function modulo_direccion_geo(){
		$return = '<div id="panel" class="col-sm-12">';
		$return .= '<label class="control-label" for="inputError"><h4>Localizacion de la propiedad</h4></label>';
    $return .= '<input id="direccion" class="col-sm-9" type="textbox" value="Mallorca">';
    $return .= '<input type="button" class="col-sm-2 btn btn-primary" value="Localizar" onclick="codeAddress()">';
    $return .= '</div>';
    return $return;
	}

	//GOOGLE MAPS
	//geolocalizacion de direccion	
  private function modulo_direccion_maps(){
  	//intentaremos meter aqui la geolocacizacion de google maps
  	//si funciona me doy una fiesta
  	$return = '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places&sensor=false"></script>';
  	//$return .= '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>';
  	$return .= '<script src="assets/js/maps/perfil_anuncio_geo.js"></script>';
  	$return .= '<div id="anuncio-map-canvas"></div>';

  	return $return;





  }



//apartir de aqui los metodos correspondientes al antiguo formulario
//========================================================

public function antiguedad(){
	return '<div class="form-group">
	        <label class="control-label" for="inputError"><h4>Antiguedad</h4></label>
	          <select class="form-control" name="antiguedad">
	            <option value="no">No especificar</option>
	            <option>Obra nueva</option><option>mas de 5 años</option>
	            <option>mas de 10 años</option><option>mas de 20 años</option>
	            <option>mas de 30 años</option><option>mas de 40 años</option>
	            <option>mas de 50 años</option><option>mas de 60 años</option>      
	          </select>
	        </div>';
}



public function precio_group(){
return '<div id="precio-group" class="form-group">
	        <label class="control-label" for="inputError"><h4>Precio</h4></label>
	        <div class="input-group">
	            <input type="text" class="form-control" name="precio" value="'.$this->val['v_precio'].'" /> 
	            <div class="input-group-addon">€</div>       
	        </div>
	        <div>
	          <label class="checkbox-inline" for="p-negociable">
	            <input id="p-negociable" type="checkbox" name="precio_negociable" value="1" />Negociable 
	          </label>
	        </div>  
        </div>
		<div id="mpa-group" class="form-group">
          <label class="checkbox-inline" for="mpa">
            <input type="checkbox" id="mpa" value="" />Mostrar precio antiguo
          </label>
          <fieldset disabled>  
            <div class="input-group">  
              <input type="text" placeholder="Precio antiguo" class="form-control" 
              name="precio_antiguo" value="" optional="true"/> 
              <div class="input-group-addon">€</div> 
            </div>
          </fieldset>  
        </div>';
}



// //grupo creador de varios exteriores en un anuncio
public function selects_superficies($i){
	$return  = '<select name="suelo[]" class="form-control" optional="true" rel="'.$i.'">';
	$return .= '<option value="0"></option>';
	$return .= '<option value="parcela">parcela</option>';
	$return .= '<option value="teraza">teraza</option>';
	$return .= '<option value="patio">patio</option>';
	$return .= '</select>';
	return $return;
}

public function inputs_superficies($i){
	$return = '<div class="input-group"> ';
	$return .= '<input type="text" rel="'.$i.'" class="form-control" name="metros[]" 
					   value="" optional="true" disabled="disabled"> ';
	$return .= '<div class="input-group-addon">m2</div>';
	return $return;
}

//grupo creador de varios exteriores en un anuncio
public function superficies_exteriores(){
	$return  = '<div id="exteriores">';
	$return .= '<h4>Superficie exterior</h4>';

		$return .=  '<table id="">';
		for($i=1;$i<4;$i++){
			$return .= '<tr class="form-group">';
			$return .= 	 '<td>'.$this->selects_superficies($i).'</td>';
			$return .= 	 '<td>'.$this->inputs_superficies($i).'</td>';
			$return .= '</tr>';
		}

	$return .= 	 '</table>';
	$return .= '</div>';
	return $return;
}














public function clave_interna(){

	return '<div class="form-group">
          	<label class="control-label" for="inputError"><h4>clave_interna</h4></label>
          	<input type="text" class="form-control" name="clave_interna" optional="true" 
                value="'.$this->val['v_clave'].'" />
            </div>';
}

public function modulo_superficies(){
  $return = '<div id="caracteristicas-superficie" class="form-group">
          
          		<label class="control-label" for="inputError"><h4>Superficie</h4></label>
              	<div class="input-group">  
              		<input type="text" class="form-control" placeholder="metros construidos"
                     	   name="superficie" req="num-required" value="'.$this->val['v_superf'].'"/>
              		<div class="input-group-addon">m2</div> 
          	  	</div>
          </div>';

  $return .= $this->superficies_exteriores(); 
  

  return $return;
}


//este es el creador de todos los campos de formulario, cuenta con
//el valor si se trata de actualizar
public function anuncio_big_creator($Form,$__tipo_venta){

//abriendo divs
$return = '<div class="panel panel-default">';
$return .= '<div class="panel-heading"></div>';
$return .= '<div class="panel-body">';

//pestañas del wizard
$return .= '<ul class="steps">';
$return .= '<li class="pasos"><a href="#tab1" data-toggle="tab"><span class="badge badge-info">1</span> Ubicacion</a></li>';
$return .= '<li class="pasos"><a href="#tab2" data-toggle="tab"><span class="badge badge-info">2</span> Propiedad</a></li>';
$return .= '<li class="pasos"><a href="#tab3" data-toggle="tab"><span class="badge badge-info">3</span> Detalles</a></li>';
//$return .= '<li><a href="#tab14" data-toggle="tab"><span class="badge badge-info">4</span> Descripcion</a></li>';
//$return .= '<li><a href="#tab15" data-toggle="tab"><span class="badge badge-info">5</span> Imagenes</a></li>';

$return .= '<li class="disabled"><a><span class="badge badge-info">4</span> Descripcion</a></li>';
$return .= '<li class="disabled"><a><span class="badge badge-info">5</span> Imagenes</a></li>';

$return .= '</ul>';

//barra de progreso
$return .= '<div class="progress thin">';
$return .= '<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">';
$return .= '</div>';
$return .= '</div>';

//abre contenido
$return .= '<div class="tab-content">';
//pestaña del wizard 1
$return .= '<div class="tab-pane" id="tab1">'; 
$return .= '<div class="row">';	
$return .= '<div class=" col-md-6">';
$return .= $Form->modulo_provincia_municipio($this->val['v_provincia'], $this->val['v_municipio']);
$return .= $this->modulo_direccion_geo();
$return .= '</div>'; 
$return .= '<div class=" col-md-6">';
//$return .= $Form->modulo_direccion_info($this->val['v_dir_p'],$this->val['v_direccion']);
$return .= $this->modulo_direccion_maps();
$return .= '</div>'; 
$return .= '</div>'; //row
$return .= '</div>';
//pestaña del wizard 2
$return .= '<div class="tab-pane" id="tab2">';
$return .= '<div class="row">';	

$return .= '<div class=" col-md-6">';
$return .= $Form->select_tipo_venta($__tipo_venta, $this->val['v_tipoventa']);
$return .= $Form->modulo_tipo_subtipo($this->val['v_t_in'], $this->val['v_st_in']);
$return .= '</div>'; 

$return .= '<div class=" col-md-6">';
$return .= $this->clave_interna();
$return .= $this->precio_group();
$return .= '</div>'; 

$return .= '<div class=" col-md-6">';
$return .= $this->modulo_superficies(); 
$return .= '</div>';

$return .= '<div class=" col-md-6">';
$return .= $Form->modulo_bannos_rooms($this->val['v_rooms'], $this->val['v_banos']);
$return .= '</div>'; 

$return .= '</div>'; //row
$return .= '</div>';
//pestaña del wizard 3
$return .= '<div class="tab-pane" id="tab3">';

$return .= $this->antiguedad();
$return .= '<div id="extras" class="">';
if(!is_null($this->val['v_extras'])){
  $return .= $Form->modulo_extras_tipo($this->val['v_t_in'],$this->val['v_st_in'],$this->val['v_extras']);
}
$return .= '</div>';
$return .= '</div>';

//cierre contenido
$return .= '</div>';

//acciones
$return .= '<div class="actions">';
$return .= '<input type="button" class="btn btn-success button-next" value="Siguiente" />';
$return .= '<input type="submit" class="btn btn-primary button-finish" value="Guardar" style="display:none"/>';	
$return .= '<input type="button" class="btn btn-default button-previous" value="Anterior" />';	
$return .= $this->opcion_borrador(); 
$return .= '</div>';

//cierres
$return .= '</div>';	
$return .= '</div>';
$return .= '</div>';	

		
//hasta aqui todo lo que va dentro del form
return $return;    		
				

}











//muestra wizard para idiomas anuncio
public function idiomas_wizard_creator($art,$__idiomas,$anuncio){


//abriendo divs
$return = '<div class="panel panel-default">';
$return .= '<div class="panel-heading"></div>';
$return .= '<div class="panel-body">';

//pestañas del wizard
$return .= '<ul class="steps">';
$return .= '<li class="disabled"><a><span class="badge badge-info">1</span> Ubicacion</a></li>';
$return .= '<li class="disabled"><a><span class="badge badge-info">2</span> Propiedad</a></li>';
$return .= '<li class="disabled"><a><span class="badge badge-info">3</span> Detalles</a></li>';
$return .= '<li class="pasos active"><a href="#tab4" data-toggle="tab"><span class="badge badge-info">4</span> Descripcion</a></li>';
$return .= '<li class="pasos disabled"><a><span class="badge badge-info">5</span> Imagenes</a></li>';

// $return .= '<li><a><span class="badge badge-info">4</span> Descripcion</a></li>';
// $return .= '<li><a><span class="badge badge-info">5</span> Imagenes</a></li>';

$return .= '</ul>';

//barra de progreso
$return .= '<div class="progress thin">';
$return .= '<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">';
$return .= '</div>';
$return .= '</div>';
//abro form antes de abrir contenido
$return .= '<form id="publicar_3" method="post">';
$return .= '<input type="hidden" name="ip_enc" value="'.md5($_SERVER['REMOTE_ADDR']).'"/>';
$return .= '<input type="hidden" name="aleatorio" value="'.$_SESSION['invisible']['token_key'].'"/>';
$return .= '<input type="hidden" name="form_to" value="idiomas_anuncio"/>';
$return .= '<input type="hidden" name="art" value="'.$art.'"/>';

//abre contenido
$return .= '<div class="tab-content">';

//pestaña del wizard 4
$return .= '<div class="tab-pane active" id="tab4">';
$return .= '<div class="row">';

$return .= '<div class="col-md-2">';
$return .= '<select name="idiomas_extra">';
$return .= '<option value="all">Ver todos</option>';
	foreach($__idiomas as $key => $value){
		$return .= '<option value="'.$key.'">'.$value.'</option>';
	}
$return .= '</select>';
$return .= '</div>';

//cargara los idiomas existentes en anuncio o morira en el intento
$return .= '<div id="lang-response" class="col-md-10">';
$return .= $this->cargar_idiomas($anuncio);
$return .= '</div>';

$return .= '</div>';  //row
$return .= '</div>';

//cierre contenido
$return .= '</div>';

//acciones
$return .= '<div class="actions">';
//$return .= '<input type="button" class="btn btn-success button-next" value="Siguiente" />';
$return .= '<a class="btn btn-primary" href="'.ANUNCIO_PASO_3.'&art='.$art.'">Continuar</a>';
//$return .= '<input type="submit" class="btn btn-primary button-finish" value="Guardar" style="display:none"/>';	
//$return .= '<input type="button" class="btn btn-default button-previous" value="Anterior" />';	
$return .= '</div>';
//cierro form
$return .= '</form>';
//cierres
$return .= '</div>';	
$return .= '</div>';
$return .= '</div>';	

		
//hasta aqui todo lo que va dentro del form
return $return;    		
				

}




}









?>