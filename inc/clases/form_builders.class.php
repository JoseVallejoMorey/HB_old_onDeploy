<?php


include_once'builders.class.php';

class form_builder extends builders{

	public function __construct(){
		//include_once 'inc/vars.php';

		parent::__construct();
	}

	//muestra checkbox de idiomas para todos los form igual
	//=====================================================
	public function form_check_idiomas($__idiomas){
		$return = '<label class="control-label" for="inputError"><h4>Idioma</h4>';
		foreach ($__idiomas as $key => $value) {
            $return .= '<input type="checkbox" name="idiomas[]" value="'.$key.'" />'.$value;
        }
        $return .= '</label>';
        return $return;
	}

	//mostrara un select con las opciones de tipo operacion definidas en vars
	//$v_tipoventa solo cuenta a la hora de actualizar un anuncio o borrador
	//=======================================================================
	public function select_tipo_venta($__tipo_venta, $v_tipoventa = null){


		$return = '<label class="control-label" for="inputError">';
		$return .= '<h4>tipo de operacion</h4>';
		$return .= '</label>';
        $return .= '<select class="form-control" name="tipo_venta">'; 
            foreach($__tipo_venta as $venta){
                if($venta == $v_tipoventa){	$check = 'selected="selected"';	
            	}else{						$check = '';			}
                $return .= '<option '.$check.'>'.$venta.'</option>';
            }
        $return .= '</select>';

        return $return;
	}

	//muestra una tabla con los input precio minimo/maximo
	//superficie minimo/maximo
	//======================================================
	public function input_precio_superficie_minimo(){
		return '<table>
				    <tr class="form-group">
				        <td>precio minimo</td>
				        <td><input type="text" class="form-control" name="min_precio" value=""/> </td>
				        <td>precio maximo</td>
				        <td><input type="text" class="form-control" name="max_precio" value=""/> </td>           
				    </tr>
				    <tr class="form-group">
				        <td>superficie minima</td>
				        <td><input type="text" class="form-control" name="metros_min" value=""/> </td>
				        <td>superficie maxima</td>
				        <td><input type="text" class="form-control" name="metros_max" value=""/> </td>
				    </tr>
				</table>';
	}






	//mostrara dos selects provincia y municipio, el segundo recoje
	//la respuesta ajax del primero. incuidos valores de actualizar anuncio
	//=============================================================
	public function modulo_provincia_municipio($v_provincia = NULL, $v_municipio = NULL){

		//provincias select
		$check = '';
		$prov_val = '';
		if( (!is_null($v_provincia)) && ($v_provincia != '') ){
			$prov_val = $v_provincia;
		}

		$return ='<div class="form-group">';
		$return .=	'<label class="control-label" for="inputError">';
		$return .=		'<h4>provincia</h4>';
		$return .=	'</label>';
		$return .=	'<select class="form-control" name="provincia">';
		$return .=		'<option value="0">todos</option>';
		$return .= 		$this->getPro($prov_val); 
		$return .=	'</select>';
		$return .='</div>';
		//municipios select (incluye ajax de respuesta de provincias)
		$return .='<div class="form-group">';
		$return .=	'<label class="control-label" for="inputError">';
		$return .=		'<h4>municipio</h4>';
		$return .=	'</label>';
		$return .=	'<select class="form-control municipios" name="municipio" >';
		if( (!is_null($v_municipio)) && ($v_municipio != '') ){
			if($mun = $this->show_municipio($v_municipio)){
				$return.=  '<option value="'.$v_municipio.'">'.$mun.'</option>';  
			}
		}		
		$return .=		'<option value="0">todos</option>';
		$return .=	'</select>';
		$return .='</div>';

		return $return;
	}










	//mostrara dos select, tipo y subtipo de inmueble
	//el segundo contiene la respuesta ajax del primero
	//=============================================================
	public function modulo_tipo_subtipo($v_t_inm = NULL, $v_st_inm = NULL){
		//tipo de inmueble select
		$check = '';
		$tipo_val = '';
		$subt_val = '';
		if( (!is_null($v_t_inm)) && ($v_t_inm != '') ){	$tipo_val = $v_t_inm;	}

		$return = '<div class="form-group">';
		$return .= 	'<label class="control-label" for="inputError">';
		$return .= 		'<h4>Tipo de inmueble</h4>';
		$return .= 	'</label>';
		$return .= 	'<select class="form-control" name="tipo_inmueble">';
		$return .= 		'<option value="0">todos</option>';
		$return .= 		$this->get_tipo($v_t_inm);

		$return .= 	'</select>';
		$return .= '</div>';
		//subtipo de inmueble select (respuesta ajax del anterior)
		$return .= '<div class="form-group">';
		$return .= 	'<label class="control-label" for="inputError">';
		$return .= 		'<h4>Subtipo de inmueble</h4>';
		$return .= 	'</label>';		
		$return .= 	'<select class="form-control subtipo_inmueble" name="subtipo_inmueble">';
		if( (!is_null($v_st_inm)) && ($v_st_inm != '') ){
			if($mun = $this->show_subtipo_inmueble($v_st_inm)){
				$return.=  '<option value="'.$v_st_inm.'">'.$mun.'</option>';  
			}


			//$return .= '<option>'.$v_st_inm.'</option>';
		}

		$return .= 		'<option value="0">todos</option>';
		$return .= 	'</select>';
		$return .= '</div>';

		return $return;
	}





	//mostrara un toggle por cada seccion de extras
	public function modulo_toggle_extras($extras){
		$return = '<div class="panel-group" id="accordion">';

		$i=0;			 
		foreach($extras as $key => $value){
			$i++;
			$return .='<div class="panel panel-default">';

			$return .=	'<div class="panel-heading">
							<a data-toggle="collapse" data-parent="#accordion" href="#alert-extras-'.$i.'">
						 		'.$key.'
							</a>
						</div>';
			$return .=	'<div id="alert-extras-'.$i.'" class="panel-collapse collapse ">
					  		<div class="panel-body">';
			foreach($value as $key2 => $value2){
				$return .='<input type="checkbox" name="extras[]" value="'.$value2.'" />&nbsp;'.$value2.'';
			}
			$return .= 		'</div>';
			$return .= 	'</div>';
			$return .= '</div>';
		}			 

		$return .= '</div>';

		return $return;
	}



	//mostrara extras segun tipo de inmueble
	public function modulo_extras_tipo($tipo, $subtipo = NULL, $extras_anuncio = NULL){
		if(!empty($_SESSION['lg'])){	$lang = $_SESSION['lg'];
		}else{						$lang = 'esp';				}

		$checked = '';
		$final = array();
		//subtipo extras
		$sub_extras = array();
		if(!is_null($subtipo)){
			$cols = array('id',$lang);
			$this->where('subtipo_inmueble', array('LIKE' => "%$subtipo%"));
			$sub_extras = $this->get('extras',NULL,$cols);
		}

		$cols = array('id',$lang);
		$this->where('tipo_inmueble', array('LIKE' => "%$tipo%"));
		$tip_extras = $this->get('extras',NULL,$cols);
		$extras = array_merge($sub_extras, $tip_extras);

		foreach ($extras as $key => $value) {
			$final[$value['id']] = $value[$lang];
		}
		
		asort($final);
		$return = '<ul id="extras-list">';
		foreach($final as $key => $valor){	
			if(!is_null($extras_anuncio)){
				if(in_array($key, $extras_anuncio)){	
					$checked = 'checked="checked"'; 
				}else{									$checked = '';					}
			}

			$return .='<li class="col-md-4 col-sm-6 col-xs-12">
						<label class="checkbox-inline" for="p-negociable">
							<input '.$checked.' type="checkbox" name="extras[]" value="'.$key.'" />
							'.$valor.'
						</label></li>';
			}
			
		$return .= '</ul>';	
		return $return;
	}


	//unicamente empleada en creacion de anuncios
	//==============================================
	public function modulo_direccion_info($dir_p = NULL, $dir = NULL){

		$dir_permiso = array('1' => 'Mostrar direccion y mapa',
							 '2' => 'Mostrar solo direccion',
							 '3' => 'Mostrar solo mapa',
							 '4' => 'No mostrar nada');

		$return  =	'<div id="direccion_datos" class="form-group">';
		$return .=	  '<label class="control-label" for="inputError"><h4>direccion</h4></label>';
		$return .=	  '<select class="form-control" name="direccion_permisos">';
		foreach ($dir_permiso as $key => $value) {
			if($key == $dir_p){$check = 'selected="selected"';
			}else{			   $check = '';	}
			$return .='<option value="'.$key.'" '.$check.'>'.$value.'</option>';
		}
		
		$return .=	  '</select>';
		$return .=  '</div>';
		$return .=  '<div id="dir-out" class="form-group">';
		$return .=		'<input type="text" class="form-control" name="direccion" optional="false" 
                   			req="required" value="'.$dir.'" /> ';
		$return .=  '</div> ';

		return $return;
        
	}


	//sacamos modulo con dos select bannos y habitaciones
	//===================================================
	public function modulo_bannos_rooms($rooms, $bannos){

		$return  = '<div id="rooms-bannos" class="form-group">';
		$return .=  '<table id="carac_ajax">';
		$return .=    '<tr class="form-group">';
		$return .= 		'<td>habitaciones</td>';
		$return .= 		'<td>';
		$return .= 		  '<select name="habitaciones" class="form-control">';
		$return .= 		'<option></option>';		
		for($i=1;$i<8;$i++){
			if($i == $rooms){	$check = 'selected="selected"';
			}else{				$check = '';	}
			$return .= '<option '.$check.'>'.$i.'</option>';
		}
		$return .= 	  '</select></td></tr>';
		$return .= 	  '<tr class="form-group">';
		$return .= 		'<td>baños</td>';
		$return .= 		'<td><select name="banos" class="form-control">';
		$return .= 		'<option></option>';
		for($i=1;$i<6;$i++){
			if($i == $bannos){	$check = 'selected="selected"';
			}else{				$check = '';	}
			$return .= '<option '.$check.'>'.$i.'</option>';
		}

		$return .= 	  '</select></td></tr>';		
		$return .= 	 '</table>';
		$return .=  '</div>';		

		return $return;
	}










}

?>