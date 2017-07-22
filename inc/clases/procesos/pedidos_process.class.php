<?php

//==============================================================================
//Proceso de pedidos
//==============================================================================
include_once 'inc/clases/mda/pedidos.class.php';
class PedidosProcess extends Pedidos{

	private $id;
	private $buyer;
	private $fecha_pedido;
	private $imagen;
	private $success = false;

	function __construct(){
		parent::__construct();
	}

	//TRADUCCIONES
	//======================

	//aqui comprobara si existe, es editar. sino es crear 
	//================================================
	public function procesar_traduccion(){
		//si existe hay que editar, sino crear nuevo
		$this->Builder->where('anuncio',$this->anuncio);
		$this->Builder->where('idioma',$this->idioma);

		if($salida = $this->Builder->getOne('anuncios_idiomas')){
			$this->id = $salida['id'];
			$this->actualizar_idioma();
			
		}else{
			$this->savenew_tradd();			
		}
	}

	//creara nuevo idioma y borrara lo ya innecesario
	//================================================
	private function savenew_tradd(){

		$this->nuevo_idioma();			//agrego nuevo idioma
		$this->actualiza_lg_anuncio();	//actualizo anuncio a mejor
		//preparo array para historial
		$hist = array('num_pedido' 	=> $this->pedido,	'user'			=> $this->buyer,
					  'tipo_pedido' => 'tradd',			'anuncio' 		=> $this->anuncio,
					  'idioma' 		=> $this->idioma,	'fecha_pedido' 	=> $this->fecha_pedido,
					  'fecha_realizacion' => $this->now);

		$this->to_historial_pedidos($hist);	//envio a historial el pedido realizado
		$this->borrar_tradd_pedido();		//borro info de pedido realizado

	}

	//actualiza un idioma ya existente (para modificar)
	//================================================
	private function actualizar_idioma(){
		$cols = array('titulo' => $_POST['titulo'],
					  'descripcion' => $_POST['descripcion']);
		
		$this->Builder->reset();
		$this->Builder->where('id',$this->id);
		$this->Builder->update('anuncios_idiomas',$cols);

	}


	//inserta nuevo idioma en tabla
	//==========================================
	private function nuevo_idioma(){

		$cols = array('id','fecha_pedido');
		$this->where('num_pedido',$this->pedido);
		$this->where('anuncio',$this->anuncio);
		$this->where('idioma',$this->idioma);

		if($salida = $this->getOne('traducciones_pedidos')){
			$this->id = $salida['id'];
			$this->buyer = $salida['user'];
			$this->fecha_pedido = $salida['fecha_pedido'];

			$cols = array('anuncio' 	=> $this->anuncio,	
						  'num_pedido' 	=> $this->pedido,
						  'idioma' 		=> $this->idioma,	
						  'titulo' 	  	=> $_POST['titulo'],
						  'descripcion' => $_POST['descripcion'],
						  'fecha_realizacion' => $this->now);
			if($this->Builder->insert('anuncios_idiomas',$cols)){
				$this->success = true;
			}
		}
	}


	//actualizo anuncio que ahora tiene un idioma mas
	//==================================================
	private function actualiza_lg_anuncio(){

		if($this->success == true){

			$cols = array('idiomas','ussr');
			$this->Builder->where('id',$this->anuncio);
			if($salida = $this->Builder->getOne('anuncios',$cols)){
				$this->buyer = $salida['ussr'];
				$new_lg = $salida['idiomas'].','.$this->idioma;
				$act = array('idiomas' => $new_lg);
				$this->Builder->where('id',$this->anuncio);
				if($this->Builder->update('anuncios',$act)){
					$this->success = true;
				}else{
					$this->success = false;
				}
			}

		}else{
			//ha habido error en la funcion anterior
			//die(var_dump('error anterior a actualiza_lg_anuncio'));
		}
	}

	//encontrara pedido de tradd a borrar
	//=============================================
	private function borrar_tradd_pedido(){
		if($this->success == true){
			$this->where('id',$this->id);
			if($this->delete('traducciones_pedidos')){
				$this->success = true;
			}else{
				$this->success = false;
			}
		}else{
			//ha habido error en la funcion anterior
		}
	}


	//BANNERS
	//===============

	//obtenemos nombre de empresa si lo tiene
	//===============================================
	private function get_empresa_name($Upimg,$Usuarios){
		$Usuarios->where('id',$Upimg->empresa);
		if($salida = $Usuarios->getOne('perfiles_emp','empresa')){
			if(!empty($salida['empresa'])){
				$Upimg->empresa_name = $salida['empresa'];
			}else{
				$Upimg->empresa_name = $Upimg->empresa;
			}
		}	
	}
	//guardara datos de pedido de un banner, procesara imagen, pasara datos al historial
	//y borrara datos de pedido_banners
	//=========================================
	public function procesar_info_new_bann($Upimg,$Usuarios){

		$tabla 			= 'banners_catalogo';
		$this->buyer 	= $_POST['empresa'];
		$Upimg->empresa = $_POST['empresa'];
		$Upimg->tipo_banner = $_POST['tipo'];

		$this->get_empresa_name($Upimg,$Usuarios);		//obtiene si hay nombre de empresa
		$this->get_info_pedido_bann();					//obtiene informacion del pedido
		if($_FILES){ $fotito = $Upimg->check_if_files($tabla); }	//proceso de la imagen
		
		//guardando datos de pedido
		$cols = array($Upimg->tipo_banner => $fotito['imagen']);
		$this->where('user',$Upimg->empresa);
		if($this->update($tabla,$cols)){
			$this->success = true;
			
			//preparo array para historial
			$hist = array('user'		=> $this->buyer,
						  'num_pedido' 	=> $this->pedido,	
						  'tipo_pedido' => 'banner',		
						  'fecha_pedido' 	  => $this->fecha_pedido,
						  'fecha_realizacion' => $this->now);

			$this->to_historial_pedidos($hist);	//envio a historial el pedido realizado
			$this->borrar_bann_pedido();		//borro info de pedido realizado
			
			//$this->borrar_bann_imagen();
		}
	}

	//obtiene info acerca del pedido de un banner
	//==================================
	private function get_info_pedido_bann(){
		$cols = array('id','tipo','imagen','fecha_pedido');
		$this->where('num_pedido',$this->pedido);
		if($salida = $this->getOne('banners_pedido',$cols)){
			$this->id = $salida['id'];
			$this->imagen = $salida['imagen'];
			$this->fecha_pedido = $salida['fecha_pedido'];
		}
	}


	//encontrara pedido de banner a borrar
	//=========================================
	private function borrar_bann_pedido(){



		//seleccionara dicho pedido, obtendra nombre de imagen si hay
		//se dirigira a su carpeta y si esta imagen la borrara
		if($this->success == true){
			$this->where('id',$this->id);

			if($this->delete('banners_pedido')){
				$this->success = true;
				//debera proceder a la eliminacion de la imagen que este subio
				$ruta = 'imagenes/banners/pedidos/';
				$img  = $ruta.$this->imagen;

				if(file_exists($img)){	unlink($img);	}




			}else{
				 	$this->success = false;
			}


		}else{
			//ha habido error en la funcion anterior
		}
	}



	//COMUNES A BANNER Y TRADUCCIONES
	//=========================================

	// guardara info en historial_pedidos
	//=========================================
	private function to_historial_pedidos($array){
		// id,pedido,user,tipo,(anuncio,idioma)fecha_pedido,fecha_realizacion

		if($this->success == true){
			if($this->insert('historial_pedidos',$array)){
				$this->success = true;
			}else{
				$this->success = false;
			}
		}else{
			//ha habido error en la funcion anterior
		}
	}







}


?>