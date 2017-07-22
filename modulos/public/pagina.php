<?php 
//pagina principal del anuncio

require 'inc/vars.php';
$Form    = new busqueda_builder();
$Config  = new show_config();
$Anuncio = new Pagina_anuncio();
$Empresa = $Config->Empresa;
echo $Config->page_header();
?>
<div id="public_content" class="container">
	<div class="main">

		<div class="row">
			<div class="col-md-9">

<?php
			echo $Anuncio->nueva_galeria();	
			echo $Anuncio->features_anuncio();
			echo $Anuncio->new_anuncio_main_panel($__idiomas);
	        echo $Empresa->show_datos_ycontacto($Form); 
?>

			</div>
			<div class="col-md-3">
				<?php echo $Anuncio->anuncios_col($Anuncio->user, ''); ?>
			</div>
		</div>
	</div>
</div>		