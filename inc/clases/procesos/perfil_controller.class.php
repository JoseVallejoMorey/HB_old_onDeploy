<?php

//clase contenedora de metodos para controlar las acciones 
//del perfil de usuario al recargar la pagina
//=============================================
require_once'inc/clases/procesos/sql_operations.class.php';

class perfil_controller extends Sql_operations{

	//se recibe orden de borrar anuncio o borrador, comprobamos todo
	//=======================================================
	public function controlador_juvilador(){
		$this->Perfil->where('salt',$_GET['controlator']);
		if($salida = $this->Perfil->getOne('usuarios','id')){
			if($this->user == $salida['id']){
				//el usuario coincide con el conectado
				if(!empty($_GET['art'])){
					//var_dump('borraria bien pero desactivado');
					//comentada para que ahora no elimine anuncios
					//$this->validar_juvilar_anuncio($_GET['art'],$salida);
				}else if(!empty($_GET['borr'])){
					$this->validar_eliminar_borrador($salida);
	}	}	}	}

	//juvilamos un anuncio
	//=======================================================
	private function validar_juvilar_anuncio($art,$salida){
		if($id_anuncio = $this->resolver_enigma($art)){
			$this->where('id',$id_anuncio);
			if($prop = $this->getOne('anuncios','ussr')){
				if($prop['ussr'] == $salida['id']){
					//el user propietario del anuncio coincide con el de el enlace generado,
					//ademas de con el user conectado. son tres coincidencias
					$this->juvilar_anuncio($id_anuncio); 
	}	}	}	}

	//eliminamos un borrador
	//=======================================================
	private function validar_eliminar_borrador($salida){
		$borr = $_GET['borr'];
		$this->where('id',$borr);
		if($prop = $this->getOne('anuncios_borradores','ussr')){
			if($prop['ussr'] == $salida['id']){
				//coincidencia correcta, procedemos a eliminar borrador
				$this->where('id',$borr);
				$this->delete('anuncios_borradores');
				//echo 'Borrador eliminado';
	}	}	} 

	//de cara al user se elimina el anuncio pero lo guardamos en otra tabla
	//=====================================================================
	private function juvilar_anuncio($anuncio){
		
		//$this->now = date('Y-m-d H:i:s');
		$campos = array('id', 'provincia', 'municipio', 'tipo_inmueble', 
						'subtipo_inmueble', 'tipo_venta', 'habitaciones', 
						'superficie', 'precio', 'banos', 
						'extras', 'fecha_publicacion', 'fecha_actualizacion', 
						'ussr', 'paquete');
		
		$insertar = array();				
		$this->where('id',$anuncio);
		$original = $this->getOne('anuncios',$campos);
		
		//convierto original en insertar y obtengo paquete
		foreach($original as $key => $value){
			$insertar[$key] = $value; 
			if($key == 'paquete'){
				$paquete = $value;
			}	
		}
		$insertar['fecha_borrado'] = $this->now;
		$this->reset();
		//inserto el anuncio en anuncios_borrados
		if($this->insert('anuncios_borrados',$insertar)){
			//elimino el anuncio de la taba
			$this->where('id',$anuncio);
			$this->delete('anuncios');
		}

		//actualizo paquete de anuncios correspondiente
		$this->sumar_paquete($paquete,'restar');

	}


	//comprueba veracidad del user para borrar ese anuncio
	//=======================================================
	public function delete_img_from_index($art, $delete){
		$anuncio = $this->resolver_enigma($art);
		$this->where('id',$anuncio);
		if($salida = $this->getOne('anuncios','ussr')){
			if($_SESSION['user_id'] == $salida['ussr']){
				//es el usuario correspondiente al anuncio
				$this->delete_img($art,$delete);
				return true;
	}	}	}

	//verifica propiedad del user para promocioinar img
	//=======================================================
	public function promote_img_from_index($art, $promote){
		$anuncio = $this->resolver_enigma($art);
		$this->where('id',$anuncio);
		if($salida = $this->getOne('anuncios','ussr')){
			if($_SESSION['user_id'] == $salida['ussr']){
				//es el usuario correspondiente al anuncio
				$this->promote_img($art,$promote);
				return true;
	}	}	}

	//promociona imagen indicada
	//=======================================================
	private function promote_img($id_anuncio,$promote){
		$this->where('id_e',$id_anuncio);
		$imagen = NULL;
		if($imagenes = $this->get('anuncios_img')){
			foreach($imagenes as $value){
				
				if($promote == hash('sha512', $id_anuncio.$this->user.$value['id'])){
					$imagen = $value['id'];
			}	}
			if(!is_null($imagen)){
				//poniendo todos a 0
				$ida = array('principal' => '0');
				$this->where('id_e',$id_anuncio);
				$this->update('anuncios_img',$ida);
				//elegido a 1
				$vuelta = array('principal' => '1');
				$this->where('id',$imagen);
				$this->update('anuncios_img',$vuelta);	

				return true;			
			}
		}else{
			return false;
		}
	}


	//se elimina imagen (de anuncio) seleccionada y su small(perfil_control.php)
	//=======================================================
	private function delete_img($id_de_anuncio, $delete){
		if($imagenes = $this->get_img($id_de_anuncio)){
			foreach($imagenes as $value){
				if($delete == hash('sha512', $id_de_anuncio.$this->user.$value['id'])){
					$img   = "imagenes/anuncios_img/$this->user/$value[img]";
					$small = "imagenes/anuncios_img/$this->user/small/small_$value[img]";

					if(file_exists($img)){		unlink($img);	}
					if(file_exists($small)){	unlink($small);	}
				
					$this->where('id',$value['id']);
					if(!($this->delete('anuncios_img'))){
						//echo 'no se borro archivo';
						return false;
					}
				}
			}	
			//return true;
			$anuncio = $this->resolver_enigma($id_de_anuncio);
			$this->check_anuncio_apto($anuncio);
			//header('Location: index.php?perfil=3&art='.$id_de_anuncio);
			
		}else{
			return false;
		}
	}


}

?>