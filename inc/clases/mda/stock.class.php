<?php

class Stock extends seg_builder{
	
	public $logos = array();

	private $u_part 	= array();
	private $u_total 	= array();
	private $u_empresas = array();
	



	//primer construscto bien hecho
	public function __construct(){
		
		//$this->logos;
		parent::__construct();
		$this->sacar_logos();
		$this->get_users();

		
	}
	
	//obtiene los logos de las empresas, para colorear el asunto
	public function sacar_logos(){
		$campos = array('id','img');
		$logos_ = $this->get('perfiles_emp', NULL, $campos);
		$user_logos = array();
		foreach($logos_ as $key => $value){
			$user_logos[$value['id']]=$value['img'];	
		}
		$this->logos = $user_logos;
	}

	//sacando usuarios de todo tipo
	public function get_users(){
		$campos = array('id','tipo_usuario');
		$total = $this->get('usuarios',NULL,$campos);
		foreach ($total as $key => $value) {
			if($value['tipo_usuario'] == 'empresa'){
				array_push($this->u_empresas, $value['id']);
				array_push($this->u_total, $value['id']);
			}else if($value['tipo_usuario'] == 'particular'){
				array_push($this->u_part, $value['id']);
				array_push($this->u_total, $value['id']);
			}
		}	
	}



//unico para star, special y banners devuelve array con los ocupados en tal fecha
public function show_service($Anuncios, $Tienda, $tipo = NULL,$fecha = NULL){

	$especimen = NULL;
	$cual = explode('_', $tipo);
	if(is_null($fecha)){$fecha = $this->date;}

	//star_area
	if(strstr($cual[0], 'star')){
		$tablas = array('1'	 => 'reserva_star_1',	'2'  => 'reserva_star_2',
						'3'  => 'reserva_star_3',	'4'  => 'reserva_star_4',
						'5'  => 'reserva_star_5',	'6'  => 'reserva_star_6',
						'7'  => 'reserva_star_7',	'8'  => 'reserva_star_8',
						'9'  => 'reserva_star_9',	'10' => 'reserva_star_10');
	//special	
	}else if(strstr($cual[0], 'special')){
		$tablas = array('alquiler' => 'reserva_special_alquiler',
						'comercial'=> 'reserva_special_comercial',
						'venta'    => 'reserva_special_venta');
	//banners	
	}else if(strstr($cual[0], 'banners')){
		$tablas = array('central' 	=> 'reserva_banners_central',
						'lateral'	=> 'reserva_banners_lateral',
						'superior'  => 'reserva_banners_superior');
	}

	if($cual[1]!='all'){$especimen = $cual[1];}
	
	if(!is_null($especimen)){
		$tabla = 'reserva_'.$cual[0].'_'.$especimen;
		if(!is_null($fecha)){$Tienda->where('date',$fecha);}
		$salida = $Tienda->get($tabla);
	}else{
		//mostrar todos
		$todas = array();		
		foreach ($tablas as $key => $value) {
			$Tienda->where('date',$fecha);
			$todas[$key] = $Tienda->get($value);
		}
		$salida = $todas;
	}
	return $this->show_buckets($Anuncios, $salida,$cual[0]);
}



private function list_buckets($Anuncios, $array, $tipo){
	
	$descartamos = array('id','date','full');	
	$salida = '<ul class="stock-monstruario">';	
	if($tipo == 'pack'){
		foreach ($array as $key => $value) {
			$salida .= '<li>';
			if(!empty($this->logos[$value])){
				$salida .=	'<img src="imagenes/logo/'.$this->logos[$value].'" />';
			}
			$salida .= '</li>';				
		}
	}else{
		foreach ($array as $key => $value) {
			if(!in_array($key, $descartamos)){
				$salida .= '<li>';
				if($tipo == 'star'){
					$Anuncios->where('id',$value);
					if($u = $Anuncios->getOne('anuncios','ussr')){$value = $u['ussr'];}
				}
				if(!empty($this->logos[$value])){
					$salida .=	'<img src="imagenes/logo/'.$this->logos[$value].'" />';
				}else{
					$salida .= $value;
				}
				$salida .= '</li>';
		}	}
	}
	$salida .= '</ul>';	
	return $salida;
}

//devuelve lista de buckets vacios
private function empty_buckets($max){
	$salida = '<ul class="stock-monstruario">';
	for ($i=1; $i < $max+1; $i++) { 
		$salida .= '<li>vacio '.$i.'</li>';
	}
	$salida .= '</ul>';
	return $salida;
}

public function show_buckets($Anuncios, $array,$tipo){
	//var_dump($array);
	$max_buckets = NULL;
	if($tipo == 'star'){			$max_buckets = 3;
	}else if($tipo == 'special'){	$max_buckets = 8;
	}else if($tipo == 'banners'){	$max_buckets = 8;	}

	//var_dump($this->logos);
	$salida = '';
	if(!empty($array)){
		foreach($array as $key => $value){
			if(is_array($value)){
				$salida .= '<div class="bucket-cont">';
				$salida .= '<h3>'.$key.'</h3>';
				//var_dump('habra varias listas que mostrar');
			 	if(!empty($value)){
					foreach ($value as $key2 => $value2) {
						if(is_array($value2)){
							$salida .=$this->list_buckets($Anuncios, $value2, $tipo);
						}else{
							$salida .=$this->list_buckets($Anuncios, $value, $tipo);
							break;
						}
					}					
				}else{
					//lista vacia
					$salida .= $this->empty_buckets($max_buckets);
				}
				$salida .= '</div>';
			}else{
				//var_dump('habra una sola lista');
				$salida .= '<div class="bucket-cont">';			
				$salida .=$this->list_buckets($Anuncios, $value, $tipo);
				$salida .= '</div>';
			}		
		}
	}else{
		$salida .= '<div class="bucket-cont">';
		$salida .= $this->empty_buckets($max_buckets);
		$salida .= '</div>';
	}
	return $salida;
}



//muestra cantidades de anuncios promocionados,
// por tipo de usuario y porcentajes
public function get_promanuncios($Anuncios){

	$promo_empresas 	= 0;
	$promo_part 		= 0;
	$nopromo_empresas 	= 0;
	$nopromo_part 		= 0;
	//porcentajes
	$precent_empresas 	= 0;
	$precent_part 		= 0;
	$percent_total		= 0;

	//sacando anuncios y contando segun corresponde
	$cols = array('id','ussr','anuncio_promocionado');
	$anus = $Anuncios->get('anuncios',NULL,$cols);
	foreach ($anus as $key => $value) {
		if(in_array($value['ussr'], $this->u_empresas)){
			if($value['anuncio_promocionado'] == '1'){	$promo_empresas++;
			}else{										$nopromo_empresas++;}
		}else if(in_array($value['ussr'], $this->u_part)){
			if($value['anuncio_promocionado'] == '1'){	$promo_part++;
			}else{										$nopromo_part++;	}
		}
	}
	
	$promo_total 		= $promo_part + $promo_empresas;
	$nopromo_total 		= $nopromo_part + $nopromo_empresas;
	$total_empresas 	= $promo_empresas + $nopromo_empresas;
	$total_part 		= $promo_part + $nopromo_part;
	$total_anuncios		= $total_part + $total_empresas;

	if($total_empresas != 0){$precent_empresas 	= ($promo_empresas / $total_empresas) * 100;}
	if($total_part != 0){	 $precent_part 		= ($promo_part / $total_part) * 100;		}
	if($total_anuncios != 0){$percent_total		= ($promo_total / $total_anuncios) * 100;	}

	//salida de la funcion 
	$salida ='<table class="table">';

	$salida .='<tr>';
	$salida .='<th></th><th>Promocionados</th><th>No promocionados</th>';
	$salida .='<th>Total</th><th>Porcentaje</th></tr>';

	$salida .='<tr>';
	$salida .='<td>Empresas</td><td>'.$promo_empresas.'</td><td>'.$nopromo_empresas.'</td>';
	$salida .='<td>'.$total_empresas.'</td><td>'.$precent_empresas.'</td></tr>';

	$salida .='<tr>';
	$salida .='<td>Particulares</td><td>'.$promo_part.'</td><td>'.$nopromo_part.'</td>';
	$salida .='<td>'.$total_part.'</td><td>'.$precent_part.'</td></tr>';		

	$salida .='<tr>';
	$salida .='<td>Total</td><td>'.$promo_total.'</td><td>'.$nopromo_total.'</td>';
	$salida .='<td>'.$total_anuncios.'</td><td>'.$percent_total.'</td></tr>';

	$salida .='</table>';

	//return
	return $salida;
}


	public function show_paquetes($Tienda){
		$salida = '';
		$q1  = array();
		$q5  = array();
		$q10 = array();
		$q20 = array();

		$cols = array('id','user','paquete');
		$paquetes = $Tienda->get('paquetes',NULL,$cols);
		foreach ($paquetes as $key => $value) {
			if($value['paquete'] == 1){			array_push($q1, $value['user']);
			}else if($value['paquete'] == 5){	array_push($q5, $value['user']);
			}else if($value['paquete'] == 10){	array_push($q10, $value['user']);
			}else if($value['paquete'] == 20){	array_push($q20, $value['user']);
		}	}

		//cada una de los tipos de paquete
		$salida .= '<h3>Paqietes de 1 Anuncio</h3>';
		$salida .= $this->list_buckets(NULL, $q1,'pack');
		$salida .= '<h3>Paqietes de 5 Anuncios</h3>';
		$salida .= $this->list_buckets(NULL, $q5,'pack');	
		$salida .= '<h3>Paqietes de 10 Anuncios</h3>';
		$salida .= $this->list_buckets(NULL, $q10,'pack');
		$salida .= '<h3>Paqietes de 20 Anuncios</h3>';
		$salida .= $this->list_buckets(NULL, $q20,'pack');
		
		return $salida;
	}





}//end class







?>