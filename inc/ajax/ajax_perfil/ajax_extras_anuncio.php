<?php

include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	include '../../vars.php';
	require '../../clases/mysqlidb.main.php';
	require '../../clases/seg/seg_builder.class.php';
	include '../../clases/form_builders.class.php';

	$Form = new form_builder();

	if (!empty($_POST['anuncio'])) {
		$anuncio = $Form->resolver_enigma($_POST['anuncio']);
		$Form->where('id',$anuncio);
		$salida = $Form->getOne('anuncios','extras');
		$extras_anuncio = explode(',',$salida['extras']);
	}else{
		$extras_anuncio = NULL;
	}

	if( (!empty($_POST['tipo'])) && (!empty($_POST['subtipo'])) ){
		
		//al cambiar los select en ajax hay un desajuste que hay que corregir
		//comprobar que subtipo pertenece a tipo, solo entonces se tiene en cuenta		
		$subtipo = NULL;
		if($_POST['subtipo'] != 0){
			$Form->where('id',$_POST['subtipo']);
			$salida = $Form->getOne('subtipo_inmueble','tipo_inmueble');
			if($_POST['tipo'] == $salida['tipo_inmueble']){
				$subtipo = $_POST['subtipo'];	
			}
		}

		if($_POST['tipo'] != 0){
			//llamar funcion
			echo $Form->modulo_extras_tipo($_POST['tipo'],$subtipo,$extras_anuncio);
		}
	}

}


?>