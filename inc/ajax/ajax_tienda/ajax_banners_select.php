<?php

include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	//includes correspondientes
	resolve_includes(true);

	$Perfil = new seg_builder();	
	$Secun 	= new fechas();
	
	if(!empty($_POST['saltador'])){
		$saltador = $_POST['saltador'];
		$user = $Perfil->id_from_salt($saltador);
	}
	
	if(!empty($_POST['tipo_bann'])){
		
		$tipo = $_POST['tipo_bann'];
		if($tipo == 'nuevo'){
			//formulario de nuevo banner
			echo'<a class="text_mini" data-toggle="modal" href="#modal_banners">ver tipos de banners</a>
				<h5>Caracteristicas:</h5><br />
				<p>Color, preferencias, sujerencias...</p><br />
				<textarea class="" name="detalles_bann"></textarea>
				<h5>Texto a incluir</h5><br />
				<textarea class="" name="texto_bann"></textarea>
				<h5>Agrege una imagen si lo desea</h5>
				<input id="imagenes" type="file" name="imagen" />';
		}else{	
			if($tipo == 'superior'){$numero = 4;
			}else if($tipo == 'lateral') {$numero = 4;
			}else if($tipo == 'central') {$numero = 8;}
	
			//consultamos si el user tiene ya un banner o no
			$Secun->where('user', $user);
			$salida = $Secun->getOne('banners_catalogo',$tipo);
	
			if($salida[$tipo] == '0'){
				echo 'hay que hacer un banner nuevo';
			}else{
				//echo 'hay banner';
				echo '<img src="imagenes/banners/'.$user.'/'.$salida[$tipo].'" />';
			}
		}
	}


}

?>