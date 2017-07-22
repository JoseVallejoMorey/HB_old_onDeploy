<?php 

include 'inc/vars.php';
require 'inc/clases/perfil/anuncio_creator.class.php';

$Creator = new anuncio_creator();

	echo '<div class="row">';
	echo '<div id="" class="col-md-8">';
	echo '<div id="wizard1" class="wizard-type1">';


if( (empty($_GET['art'])) || (!$anuncio = $Con->resolver_enigma($_GET['art'])) ){

	// echo '<div class="col-lg-12">';
	 	echo $Con->seleccione_anuncio('idioma');
	// echo '</div>';	

}else{
	
	$art =$_GET['art'];
	echo $Creator->idiomas_wizard_creator($art,$__idiomas,$anuncio);
}

?>

		</div>
	</div>
</div>