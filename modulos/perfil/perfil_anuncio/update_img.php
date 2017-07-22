<?php

require 'inc/clases/perfil/imagenes_anuncios.class.php';
$Imagen = new imagenes_anuncios();
?>


<div class="row">
	


<?php
//var_dump($Imagen);
//debe haber art	
if (!empty($_GET['art'])){
	if($Con->resolver_enigma($_GET['art'])){
		//anuncio existe
		$id_de_anuncio = $_GET['art'];

		echo '<div id="wizard1" class="wizard-type1 col-md-8">';

		//ver si todavia no hay imagenes en el anuncio,
		if(!$imagenes = $Imagen->get_img($id_de_anuncio)){
			var_dump('anuncio no tiene imagenes');
		}
		
		echo $Imagen->idiomas_wizard_creator($id_de_anuncio,$imagenes);
		echo '</div>';

		echo '<div class="col-md-3">';
		if(!empty($imagenes)){
			echo $Imagen->mostrar_preferida($imagenes);
		}
		echo '</div>';

	}else{
		//anuncio no existe, mostraremos anuncios para seleccionar
		echo $Con->seleccione_anuncio('upload');
	}

}else{
	//no hay nada por GET le muestro anuncios para que seleccione uno
	echo $Con->seleccione_anuncio('upload');
}




?>



</div>