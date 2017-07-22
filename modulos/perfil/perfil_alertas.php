<?php

$Alertas = new alertas_user();

if(!empty($_GET['alert'])){
	echo $Alertas->mostrar_info_alerta($_GET['alert'],'alertas');
}else{
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<ul class="nav nav-tabs pull-left" id="tabs">
			<li class="active"><a href="#nu-alerts" data-toggle="tab">Nuevas alertas</a></li>
			<li class=""><a href="#old-alerts" data-toggle="tab">Alertas descartadas</a></li>
		</ul>
	</div>
	<div class="panel-body">	
		<div class="tab-content">
			<!--nuevas alertas-->
			<div class="tab-pane active" id="nu-alerts">    
		  		<?php echo $Alertas->ver_nuevas_alertas($Con); ?>
			</div>
			<!--alertas vistas-->
			<div class="tab-pane" id="old-alerts">
				<?php echo $Alertas->ver_alertas_descartadas($Con); ?>
			</div>
		</div>
	</div>
</div>

<?php
}
?>