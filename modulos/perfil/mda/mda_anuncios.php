<?php
//viendo anuncios de los usuarios en mda
$Show = new show_master();
?>

<div class="panel panel-default">
	<div class="panel-heading">
		Anuncios
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<?php echo $Show->mostrando_anuncios(); ?>
		</div>
	</div>
</div>