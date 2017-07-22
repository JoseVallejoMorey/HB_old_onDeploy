<?php

//la clase y metodos definidos aqui van dedicados a realizar las operaciones
//de insercion en base de datos compras, actualizaciones de anuncios
//definicion bastante mala, ya mejorara

//include_once 'inc/clases/builders.class.php';
//include_once 'inc/clases/uploadimg.class.php';

class Sql_operations extends builders {


	//objetos
	public $Reserva;

	public function __construct(){
		parent::__construct();

		$this->Reserva = new reserva_builder();
	}

	//FUNCION INSERTAR REGISTROS EN TABLA
	//=====================================
	public function agregar_db($tabla,$Perfil=null){
	
		//si hay imagenes
	    if($_FILES) {
	    	// die(var_dump($_FILES));
	    	$Upimg = new Upimg();
			$fotito = $Upimg->check_if_files($tabla);
		}

		//quito los all
		$campos = array();
		foreach($_POST as $key => $value){
			if($key == 'form_to') {unset($_POST[$key]);	}
			if($value == '0') {unset($_POST[$key]);	}
		}

		//si es un array por cada uno de ellos metera un registro en tabla
		if((isset($_POST['img'])) && (is_array($_POST['img'])) ){
			foreach ($_POST['img'] as $key => $value) {
				$campos['img']  = $value;
				$campos['id_e'] = $_POST['id_e'];
				$this->insert($tabla,$campos);
			}

		}else{
			//no tiene que ver con imagenes
			foreach($_POST as $nombre_post => $valor_post) {
				//esto es por si hay varios extras por ejemplo
				if(is_array($valor_post)) {
					$campos[$nombre_post] = implode(',',$valor_post);
				}else{
					$campos[$nombre_post] = $valor_post;
				}
			}	
			if(!is_null($Perfil)){
				$this->last_id = $Perfil->insert($tabla,$campos);
			}else{
				$this->last_id = $this->insert($tabla,$campos);	
			}
			
		}

	}//fin de agregar_db
	
	
	
	//actualiza con lo que viene por post de un formulario
	//=========================================================
	public function actualizar_db($tabla, $where, $Objeto = null){

		//si hay objeto($Perfil)guardara db externa al objeto
		if(is_null($Objeto)){$Objeto = $this;}

		//si hay imagenes
	    if($_FILES) {
	    	$Upimg = new Upimg();
			$fotito = $Upimg->check_if_files($tabla);
		}

		$Objeto->where('id',$where);

		//quito los all
		$campos = array();
		foreach($_POST as $key => $value){
			if($key == 'form_to') {unset($_POST[$key]);	}
			if($value == '0') {unset($_POST[$key]);	}
		}
		
		foreach($_POST as $nombre_post => $valor_post) {
			if(is_array($valor_post)) {
				$campos[$nombre_post] = implode(',',$valor_post);
			}else{
				$campos[$nombre_post] = $valor_post;
			}
		}
		
		if($Objeto->update($tabla,$campos)){
			
		}
	}









//obtiene numero de paquete del anuncio
protected function get_paquete($anuncio){
	$this->where('id',$anuncio);
	if($salida = $this->getOne('anuncios','paquete')){
		return $salida['paquete'];
	}
}

//buscara anuncio en imagenes_anuncios
protected function check_apto1($anuncio){
	$md5_anuncio = md5($anuncio);
	$this->where('id_e',$md5_anuncio);
	if(!$imagenes = $this->get('anuncios_img')){
		return false;
	}else{
		return true;
	}
}

//buscara en anuncios_idiomas
protected function check_apto2($anuncio){
	$this->where('anuncio',$anuncio);
	if(!$textos = $this->get('anuncios_idiomas')){
		return false;
	}else{
		return true;
	}
}



//paquete donde el anuncio activo
protected function check_apto3($anuncio){
	//$Tienda substituido por this->reserva
	$pk = $this->get_paquete($anuncio);
	$this->Reserva->where('id',$pk);
	if($salida = $this->Reserva->getOne('paquetes','estado')){
		if($salida['estado'] == 1){ return true;		//si es 1 es que es bueno
		}else{						return false;		//cualquier otra cosa no
		}
	}else{  
		return false;									//si exito en la consulta
	}
}

private function interruptor_apto($anuncio,$que){
	// var_dump($que);
	// die('llega'.$que);
	if ($que == true) {
		$campos = array('apto' => '1');	
	}else{
		$campos = array('apto' => '0');	
	}
	//actualizamos si apto o no apto
	$this->where('id',$anuncio);
	$this->update('anuncios',$campos);
}

	//siempre que actualice imagenes o descripciones 
	//se comprobara que el anuncio sea apto
	//=================================================
	public function check_anuncio_apto($anuncio){
		
		//buscara dicho anuncio en imagenes_anuncios, si no hay devolvera false
		$imagenes = $this->check_apto1($anuncio);
		//buscara descripcion en anuncios_idiomas y si no hay almenos una dara false
		$textos   = $this->check_apto2($anuncio);
		//si su paquete correspondiente esta o no activo
		$paquetes = $this->check_apto3($anuncio);

		// var_dump($imagenes);
		// var_dump($textos);
		// var_dump($paquetes);

		if( ($imagenes == false) || ($textos == false) || ($paquetes == false) ){	
			$this->interruptor_apto($anuncio,false);
		}else{												
			$this->interruptor_apto($anuncio,true);
		}
		//fin
	}








	//clase destruct
	//======================================
	public function __destruct(){
		parent::__destruct();
	}


}





?>