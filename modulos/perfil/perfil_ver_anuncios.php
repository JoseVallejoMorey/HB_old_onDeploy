<?php
//muestra catalogo de anuncios que tiene el user

require 'inc/clases/perfil/catalogo_anuncios.class.php';
$Anuncio = new catalogo_anuncios();
$borradores = $Anuncio->mostrar_tabla_borradores();

?>

<div class="panel panel-default">
	<div class="panel-heading">
		<ul class="nav nav-tabs pull-left" id="tabs">
		  	<li class="active"><a href="#pestanna_anuncios"  data-toggle="tab">Activity</a></li>
		  	<?php echo $borradores['pestanna_borradores']; ?>

		</ul>
	</div>
	<div class="panel-body">
		
		<div class="tab-content">
		  	<div class="tab-pane active" id="pestanna_anuncios">
				<?php echo $Anuncio->mostrar_tabla_anuncios(); ?>			
		  	</div>
		  	<div class="tab-pane" id="pestanna_borradores">
				<?php echo $borradores['contenido_borradores']; ?>			
		  	</div>
		</div>
	</div>
</div>