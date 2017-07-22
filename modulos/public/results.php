<?php

var_dump('estoy en results');	
include 'inc/clases/public/salida_anuncios.class.php';
$Config = new show_config();
$Con = new salida_anuncios();
?>

<div id="public_content" class="container">
	<div class="main">
		<div class="row">
		    <div id="consulta-ajax" class="col-md-9">

		    	<?php echo $Con->showStar(); ?>
				<!-- <div class="row container-property"> -->

<?php

		//salen todos los anuncios, banner si cabe y paginacion de anuncios
		echo $Con->paginacion_anuncios();	
		//sacamos special area
		echo $Con->showSpecial();

?>
				<!-- </div> -->
			</div>
			<div id="anunciantes_v" class="col-md-3 bann3" style="padding-right:0">

		<?php echo $Con->show_banner('lateral'); ?>
			</div>
		</div>
	</div>
</div>		