<?php 

//=========================================================
//fonciones para buscador y buscador-empresa
//=========================================================

include_once'form_builders.class.php';

class busqueda_builder extends form_builder{

	public function __construct(){
		parent::__construct();
	}


public function buscador_operacion(){
	$return  = '<select name="operacion">';
	$return .= '<option value="0">'.TODOS_OPERACION.'</option>';
	$return .= '<option value="venta">'.VENTA.'</option>';
	$return .= '<option value="alquiler">'.ALQUILER.'</option>';
	$return .= '</select>';
	return $return;
}

public function buscador_provincia(){
	$return  = '<select name="provincia">';
	$return .= '<option value="0">'.TODOS_PROVINCIA.'</option>';
	$return .= $this->getPro(); 
	$return .= '</select>';
	return $return;
}


public function buscador_poblacion(){
	$return  ='<select name="municipio" class="municipios">';	
	$return .='<option value="0">'.TODOS_POBLACION.'</option>';
	$return .='</select>';
	return $return;
}


public function buscador_tipo(){
	$return  = 	'<select name="tipo_inmueble">';
	$return .= 		'<option value="0">'.TODOS_TIPO.'</option>';
	$return .= 		$this->get_tipo();
	$return .= 	'</select>';
	return $return;
}
public function buscador_subtipo(){
	$return  = '<select class="subtipo_inmueble" name="subtipo_inmueble">';
	$return .= '<option value="0">'.TODOS_SUBTIPO.'</option>';
	$return .= '</select>';
	return $return;
}


public function buscador_empresas(){
	$return  = '<select name="empresa">';
	$cols = array('empresa','nik_empresa');
	//aqui debe saber si esta en una empresa esa sera la unica opcion
	if(!empty($_GET['inmv'])){
		$nik = $_GET['inmv'];
		$this->Perfil->where('nik_empresa',$nik);
	}else{
		$cols = array('empresa','nik_empresa');
		$this->Perfil->where('apto',1);
		$return .= '<option value="0">'.TODOS_EMPRESA.'</option>';
	}
	$salida = $this->Perfil->get('perfiles_emp',null,$cols);		

	foreach ($salida as $key => $value) {
		$return .= '<option value="'.$value['nik_empresa'].'">'.$value['empresa'].'</option>';
	}
	
	$return .= '</select>';
	return $return;
}

	//buscador precio min-max (precio max nunca sera menor que precio min)
	//=========================================================
	public function buscador_precio($cua,$min = NULL){
		
		$c 			= '';
		$min_limit 	= '';
		$max_limit  = '';
		$principio 	= '';
		$final 		= '';
		$precios = array('10000' => '10.000',		'20000' => '20.000',	'50000' => '50.000',
						 '100000' => '100.000',		'250000' => '250.000',	'500000' => '500.000',
						 '1000000' => '1.000.000',	'5000000' => '5.000.000');

		$precios_max = array('15000' => '15.000',	'30000' => '30.000',	'75000' => '75.000',
						 	 '150000' => '150.000',	'350000' => '350.000',	'750000' => '750.000',
						 	 '2000000' => '2.000.000',	'10000000' => '10.000.000');

		//distinguiremos si el select es precio minimo o precio maximo
		if($cua == 'min'){
			$name 		= 'name="precio_min"';
			$min_limit  = '<option value="min">'.MIN_PRECIO.'</option>';
		}else if($cua == 'max'){
			$name 		= 'id="precio_max" name="precio_max"';
			$max_limit  = '<option value="max" selected>'.MAX_PRECIO.'</option>';
		}

		//si hay marcado un precio minimo devolvemos solo option (porque es ajax)
		//y elimino los precios mas bajos para no mostrarlos en max
		if(!is_null($min)){
			foreach ($precios_max as $key => $value) {
				if($key < $min){	unset($precios_max[$key]);	}
			}
			$precios = $precios_max;
		}else{
			$principio = '<select '.$name.'>';
			$final = '</select>';
		}

		//pegote de vuelta
		$return = $principio;
		$return .= $min_limit;
		foreach ($precios as $key => $value) {
			if($cua == 'max'){
				if( ($key == '500000') || ($key == '750000') ){	$c = 'selected="selected"';	
				}else{											$c = '';					}
			}
			$return .= '<option value="'.$key.'" '.$c.'>'.$value.'</option>';
		}
		$return .= $max_limit;
		$return .= $final;

		return $return;

	}


	//mostramos dos selects superficie min y max, por defecto en "all"
	//===============================================================
	private function buscador_superficie($cua){

		$min_limit = '';
		$max_limit = '';
		$min_check = '';
		$max_check = '';
		
		if($cua == 'min'){
			$name 	   = 'name="superf_min"';
			$min_check = 'selected="selected"';
			$min_limit = '<option value="min" '.$min_check.'>'.MIN_SUPERF.'</option>';
		}else if($cua == 'max'){
			$name 	   = 'id="superf_max" name="superf_max"';
			$max_check = 'selected="selected"';
			$max_limit = '<option value="max" '.$max_check.'>'.MAX_SUPERF.'</option>';
		}

		$return  = '<select '.$name.'>';
		//$return .= '<option value=""></option>';
		$return .= $min_limit;
		$return .= '<option>50</option>';
		$return .= '<option>75</option>';
		$return .= '<option>100</option>';
		$return .= '<option>150</option>';
		$return .= '<option>200</option>';
		$return .= '<option>300</option>';
		$return .= '<option>500</option>';
		$return .= $max_limit;
		$return .= '</select>';

		return $return;


	}


	//minimo de habitaciones requeridas por el buscante
	//=======================================================
	public function buscador_min_rooms(){
		$return  = '<select name="rooms">
                	<option value="0" selected="selected">'.HABITACIONES.'</option>';
        for($i=1;$i<9;$i++){$return .= '<option>'.$i.'</option>';} 
        $return .= '</select>';
        return $return;
	}



private function buscador_extras(){

	if(!empty($_SESSION['lg'])){	$lang = $_SESSION['lg'];
	}else{							$lang = 'esp';				}

	$return = '';
	$this->where('id',array('in' => array('1','2','8','12','30')));
	$campos = array('id',$lang);


	$return .= '<span id="form-extras" class="col-sm-2 dropdown" >';
	$return .= '<button id="ul-extras" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
	$return .= '<span>Extras</span>';
	$return .= '<span class="flechita"></span>';
	$return .= '</button>';
	$return .= '<ul class="dropdown-menu" aria-labelledby="ul-extras">';
	$return .= '<i class="fa fa-times"></i>';

	if($s = $this->get('extras',NULL,$campos)){
		foreach ($s as $key => $v) {
			$return .= '<li>
	            		<input type="checkbox" name="filtro_extras[]" value="'.$v['id'].'" />
	            		'.$v[$lang].'
	       				</li>';
		}
	}

	$return .= '</ul>';
	$return .= '</span>';

	return $return;
  




}




	private function buscador_sujerencias(){

		$return = '<input id="sujerencia-input" name="sujerencia" placeholder="Poblacion"
					 autocomplete="off" type="text" />';
		return $return;

	}




	//lo mismo para perfil de empresa
	public function form_empresa_big($empresa){
		//#form-inter sera secuestrado por js y llevado a sidebar
		$return = '<div  class="form-frontal form-inmv hidden-xs hidden-sm">';
		$return .= '<div id="form-inter">';
		$return .= '<form id="buscador" method="post"  action="index.php?pagg=1">';

		$return .= '<a class="btn btn-primary" data-toggle="collapse" href="#coll-form" ';
		$return .= 'aria-expanded="false" aria-controls="collapseExample">';
		$return .= 'Busqueda detallada</a>';

		$return .= '<div id="mod_selector" class="btn-group" data-toggle="buttons">
					  <label class="btn btn-warning active">
					    <input type="radio" name="options" id="mod1" autocomplete="off" checked> mod1
					  </label>
					  <label class="btn btn-warning">
					    <input type="radio" name="options" id="mod3" autocomplete="off"> mod3
					  </label>
					</div>';
		$return .= '<div class="orderby">		
						<span>'.ORDENAR.'</span>
						<select name="ordenar">
							<option value="1">'.PMAYORMENOR.'</option>
							<option value="2">'.PMENORMAYOR.'</option>
							<option value="3">'.SMAYORMENOR.'</option>
							<option value="4">'.SMENORMAYOR.'</option>
							<option value="5">'.MASNUEVO.'</option>
							<option value="6">'.MASANTIGUO.'</option>
						</select>
					</div>';

		$return .= '<div class="collapse" id="coll-form">';

		$return .= '<input type="hidden" name="ip_enc" value="'.md5($_SERVER['REMOTE_ADDR']).'"/>';
		$return .= '<input type="hidden" name="aleatorio" value="'.$_SESSION['invisible']['token_key'].'"/>';
		$return .= '<input type="hidden" name="lang_form" value="'.$_SESSION['lg'].'"/>';
		$return .= '<input type="hidden" name="empresa" value="'.$empresa.'"/>';

		$return .= '<div id="busqueda_main_empresa" class="col-lg-12 col-sm-12 col-12">';
		$return .= '<div id="datos" class="col-sm-4">';
		$return .= $this->buscador_provincia_municipio();
		$return .= $this->buscador_tipo_subtipo(); 
		$return .= '</div>';

		$return .= '<div class="col-sm-4"> ';
		$return .= $this->buscador_precio_completo();
		$return .= $this->buscador_superficie_completo();
		$return .= '</div>';

		$return .= '<div id="filtros" class="col-sm-4 ">';
		$return .= $this->buscador_min_rooms();
		$return .= $this->buscador_tipos_venta();
		$return .= '</div>';
		$return .= '</div>';
		$return .= '</div>';

		$return .= '</form>';
		$return .= '</div>';
		$return .= '</div>';

		return $return;
	}




public function buscador_intercambiable(){

	$destino = 'index.php?pagg=1';							//destino por defecto
$ident = '<input type="hidden" name="busqueda" value="1"/>';
if(!empty($_GET['inmv'])){
	$destino = 'index.php?inmv='.$_GET['inmv'];	//destino si es empresa
	$ident = '<input type="hidden" name="inmv" value="'.$_GET['inmv'].'"/>';
}

$return = '<div id="form-viajante" class="row" rel="on-top">';

//cambiando metodo para busquedas (el unico form que va por get)
$return .= '<form id="buscador" method="get" action="'.$destino.'">';
$return .= $ident;
$return .= '<input type="hidden" name="lang_form" value="'.$_SESSION['lg'].'"/> ';

$return .= '<div class="form-box">';
$return .= '<span class="col-sm-2">'.$this->buscador_operacion().'</span>';
$return .= '<span class="col-sm-10">'.$this->buscador_sujerencias().'</span>';

$return .= '</div>';
$return .= '<div class="form-box show-less">';
$return .= '<span class="col-sm-2">'.$this->buscador_provincia().'</span>';
$return .= '<span class="col-sm-2">'.$this->buscador_poblacion().'</span>';
$return .= '<span class="col-sm-2">'.$this->buscador_precio('min').'</span>';
$return .= '<span class="col-sm-2">'.$this->buscador_superficie('min').'</span>';
$return .= '<span class="col-sm-2">'.$this->buscador_superficie('max').'</span>';
//$return .= '<span class="col-sm-2">'.$this->buscador_extras().'</span>';
$return .= $this->buscador_extras();

$return .= '</div>';
$return .= '<div class="form-box">';
$return .= '<span class="col-sm-2">'.$this->buscador_tipo().'</span>';
$return .= '<span class="col-sm-2">'.$this->buscador_subtipo().'</span>';
$return .= '<span class="col-sm-2">'.$this->buscador_precio('max',NULL).'</span> ';
$return .= '<span class="col-sm-2">'.$this->buscador_min_rooms().'</span>';
$return .= '<span class="col-sm-2">'.$this->buscador_empresas().'</span> ';
$return .= '<span class="col-sm-2"><input class="btn btn-success" type="submit" />';
$return .= '</div>';
$return .= '<div class="form-box">';
$return .= '<span id="refresh" class="col-sm-2 "><a href="#"><i class="fa fa-refresh"></i> Resetear</a></span>';
$return .= '<span id="show-more" class="col-sm-2 right"><a href="#">Mas opciones <i class="fa fa-chevron-down"></i></a></span>';
$return .= '<span id="show-less" class="col-sm-2 right"><a href="#">Menos opciones <i class="fa fa-chevron-up"></i></a></span>';
$return .= '</div>';

$return .= '</form>';
$return .= '</div>';

	
return $return;


}



//busqueda simple para promo
public function form_promo_simple(){

$return = '<div id="form-viajante" class="row" rel="on-top">';
//cambiando metodo para busquedas (el unico form que va por get)
$return .= '<form id="buscador" method="get" action="index.php?pagg=1">';
$return .= '<input type="hidden" name="busqueda" value="1"/>';
//$return .= '<input type="hidden" name="lang_form" value="'.$_SESSION['lg'].'"/> ';
$return .= '<div class="form-box">';
$return .= '<span class="col-sm-3">'.$this->buscador_operacion().'</span>';
$return .= '<span class="col-sm-9">'.$this->buscador_sujerencias().'</span>';
$return .= '</div>';

$return .= '<div class="form-box">';
$return .= '<span class="col-sm-3">'.$this->buscador_subtipo().'</span>';
$return .= '<span class="col-sm-3">'.$this->buscador_superficie('min').'</span>';
$return .= '<span class="col-sm-3">'.$this->buscador_precio('max',NULL).'</span> ';
$return .= '<span class="col-sm-3"><input class="btn btn-success" type="submit" />';
$return .= '</div>';

$return .= '</form>';
$return .= '</div>';

return $return;

}




public function form_promo_avanzada(){


$return = '<div class="row" rel="on-top">';

//cambiando metodo para busquedas (el unico form que va por get)
$return .= '<form id="buscador" method="get" action="index.php?pagg=1">';
$return .= '<input type="hidden" name="busqueda" value="1"/>';
$return .= '<input type="hidden" name="lang_form" value="'.$_SESSION['lg'].'"/> ';

$return .= '<div class="form-box">';
$return .= '<span class="col-sm-3">'.$this->buscador_operacion().'</span>';
$return .= '<span class="col-sm-9">'.$this->buscador_sujerencias().'</span>';

$return .= '</div>';
$return .= '<div class="form-box">';
$return .= '<span class="col-sm-3">'.$this->buscador_provincia().'</span>';
$return .= '<span class="col-sm-3">'.$this->buscador_poblacion().'</span>';
$return .= '<span class="col-sm-3">'.$this->buscador_min_rooms().'</span>';
$return .= '<span class="col-sm-3">'.$this->buscador_superficie('min').'</span>';

$return .= '</div>';
$return .= '<div class="form-box">';
$return .= '<span class="col-sm-3">'.$this->buscador_subtipo().'</span>';
$return .= '<span class="col-sm-3">'.$this->buscador_precio('max',NULL).'</span> ';
$return .= '<span class="col-sm-3">'.$this->buscador_empresas().'</span> ';
$return .= '<span class="col-sm-3"><input class="btn btn-success" type="submit" />';
$return .= '</div>';


$return .= '</form>';
$return .= '</div>';

	
return $return;


}




public function form_promo_empresas(){


$return = '<div class="row" rel="on-top">';

//cambiando metodo para busquedas (el unico form que va por get)
$return .= '<form id="buscador" method="get" action="index.php?pagg=1">';
$return .= '<input type="hidden" name="busqueda" value="1"/>';

$return .= '<div class="form-box">';
$return .= '<span class="col-sm-3">'.$this->buscador_operacion().'</span>';
$return .= '<span class="col-sm-9">'.$this->buscador_sujerencias().'</span>';

$return .= '</div>';

$return .= '<div class="form-box">';
$return .= '<span class="col-sm-3">'.$this->buscador_poblacion().'</span>';
$return .= '<span class="col-sm-3">'.$this->buscador_subtipo().'</span>';
$return .= '<span class="col-sm-3">'.$this->buscador_empresas().'</span> ';
$return .= '<span class="col-sm-3"><input class="btn btn-success" type="submit" />';
$return .= '</div>';


$return .= '</form>';
$return .= '</div>';

	
return $return;


}


















}
?>