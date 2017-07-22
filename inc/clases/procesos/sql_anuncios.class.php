<?php 

include_once'sql_operations.class.php';

class sql_anuncios extends Sql_operations {
	


	//prepara las variables para introducir en tabla exteriores (creando anuncio)
	//===================================================================
	public function superficies_varias(){

		$suelos[0] = '';
		$metros[0] = '';
		$superficies[0] = '';
		
		$i=0;
		if( (!empty($_POST['suelo'])) && (is_array($_POST['suelo'])) ){
			foreach($_POST['suelo'] as $key => $value){
				if($value != 0){
					$i++;
					$suelos[$i] = $value;					
				}

			}	
		}
		
		$i=0;
		if( (!empty($_POST['metros'])) && (is_array($_POST['metros'])) ){
			foreach($_POST['metros'] as $key => $value){
				$i++;
				$metros[$i] = $value;
			}	
		}
		//borro los post que ya no necesito
		unset($_POST['suelo']);
		unset($_POST['metros']);

		//borro espacios vacios en el array
		foreach($suelos as $key => $value){if($value == ''){	unset($suelos[$key]);}}
		foreach($metros as $key => $value){if($value == ''){	unset($metros[$key]);}}
		
		$i=1;
		foreach($suelos as $value){
			$superficies[$value] = $metros[$i];
			$i++;
		}
		unset($superficies[0]);
	return $superficies;
	
	}
	
	//introduce en tabla exteriores las distintas superficies del anuncio (creando anuncio)
	//==============================================================
	public function introducir_superficies($superficies, $anuncio){
	
		foreach($superficies as $key => $value){
			$cols = array('anuncio' => $anuncio,
						  'suelo'   => $key,
						  'metros'  => $value);
			$this->insert('anuncios_exteriores',$cols);
		}
	}

	//operaciones de compra de paquetes
	//comprueba si el paquete esta lleno y lo marca si asi es.
	//==============================================================
	public function paquete_full($paquete){
	
		//cuantos anuncios hay con ese numero de paquete
		$this->where('paquete',$paquete);
		$this->get('anuncios' ,NULL, 'id');
		$cuantos = $this->count;
		
		//busco paquete y compruebo
		$this->Reserva->where('id',$paquete);
		$salida = $this->Reserva->getOne('paquetes','paquete');
		
		if($cuantos >= $salida['paquete']){
			$this->Reserva->where('id',$paquete);
			$cols = array('full' => '1');
			$this->Reserva->update('paquetes', $cols);
		}	
	}
	
	//sumar 1 al paquete correspondiente
	//==============================================================
	public function sumar_paquete($paquete,$operador){
		
		$this->Reserva->where('id',$paquete);
		$salida = $this->Reserva->getOne('paquetes','anuncios');

		if($operador == 'sumar'){		$anuncios = $salida['anuncios'] + 1;}
		else if($operador == 'restar'){	$anuncios = $salida['anuncios'] - 1;}			
		
		$this->Reserva->where('id',$paquete);
		$cols = array('anuncios' => $anuncios);
		$this->Reserva->update('paquetes',$cols);
		
		$this->paquete_full($paquete);
	}	









	//actualizando campo idiomas en anuncio
	//=====================================================
	public function update_anuncio_idiomas($anuncio,$idiomas_existentes,$nuevo_idioma){
		$new_string = $idiomas_existentes.','.$nuevo_idioma;
		//quitamos coma inicial si la hay
		if(substr($new_string, 0,1)== ','){	$new_string = substr($new_string, 1);	}
		$campos = array('idiomas' => $new_string);
		$this->where('id',$anuncio);
		$this->update('anuncios',$campos);
	}

	//tras actualizar tabla idiomas, hay que actualizar anuncio con su nuevo idioma
	//===========================================================================
	public function check_anuncio_idiomas($anuncio, $idioma_elegido){
		$this->where('id',$anuncio);
		$salida = $this->getOne('anuncios','idiomas');
		if(!empty($salida['idiomas'])){
			if(!strpos($salida['idiomas'], $idioma_elegido)){
				$this->update_anuncio_idiomas($anuncio,$salida['idiomas'],$idioma_elegido);
			}
		}else{
			$this->update_anuncio_idiomas($anuncio,$salida['idiomas'],$idioma_elegido);
		}
	}









//fin
}

?>