<?php
$Config  = new show_config();	//objeto config
$Empresa = $Config->Empresa;	//ya iniciado en config, heredamos
$Modulos = $Config->Modulos;	//ya iniciado den config

?>

<div id="public_content" class="container">
	<div class="main">	
		<div class="row" style="margin:0">
			<div class="col-sm-8 col-sm-offset-2 bann1">
      <?php echo $Modulos->show_banner('superior'); ?>
      </div>

			<div id="list-e-cont" class="col-sm-12">

				<div class="row">
					<?php  echo $Empresa->paginacion_empresas(); ?>
				</div>

				<?php echo $Modulos->showSpecial(); ?>
			</div>

		</div>
	</div>
</div>