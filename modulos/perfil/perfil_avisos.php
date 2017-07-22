<?php

$Alertas = new alertas_user();

if(!empty($_GET['user_alert'])){	
	echo $Alertas->mostrar_info_alerta($_GET['user_alert'],'alertas_preferencias_users');
}else{
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<ul class="nav nav-tabs pull-left" id="tabs">
  <li class=""><a href="#nueva_especializacion" data-toggle="tab">Definir especializacion</a></li>
  <li class="active"><a href="#especializacion_actual" data-toggle="tab">Preferencias de especializacion</a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<!--nuevo aviso-->
			<div class="tab-pane" id="nueva_especializacion">
				<?php 	echo $Alertas->nueva_especializacion();		?>	  
			</div>    
			<!--avisos creados-->
			<div class="tab-pane active" id="especializacion_actual">
				<?php 	echo $Alertas->especializacion_actual($Con);	?>
			</div>
		</div>
	</div>
</div>

<?php	
}
?>