<?php
//funcion que crea formularios
$aleatorio = $_SESSION['invisible']['token_key'];

if($_SESSION['tipo'] == 'empresa'){
	$disponibles = array('5','10','20');
	$duracion    = array('3','6','12');
}else{
	$disponibles = array('1');
	$duracion    = array('1');
}

?>	
<div class="row">
	<div class="col-md-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<ul class="nav nav-tabs pull-left tienda-helper" id="tabs">
					<li class="active"><a href="#paquetes_disp" data-toggle="tab">Paquetes disponibles</a></li>
					<li class=""><a href="#paquetes_actuales" data-toggle="tab">Paquetes actuales</a></li>
					<li class=""><a href="#paquetes_caducados" data-toggle="tab">Paquetes caducados</a></li>
				</ul>
			</div>

			<div class="panel-body">
				<div class="tab-content">

					<!--tres paginas para paquetes-->
					<div class="tab-pane active col-lg-5 col-lg-offset-3" id="paquetes_disp">    
						<?php echo $Con->seleccion_paquete($disponibles,$duracion); ?>
					</div>

					<div class="tab-pane" id="paquetes_actuales">
						<?php echo $Con->paquetes_actuales_renew(); ?>
					</div>
					<div class="tab-pane" id="paquetes_caducados">
						<?php echo $Con->paquetes_recomprar(); ?>
						
					</div>	

				</div>
			</div>
		</div>
	</div>
	<!-- respuesta de ajax -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				Resultado
			</div>
			<div class="panel-body">
				<div class="tab-content">
<?php
echo'<form method="post" id="form_paquetes" class="form_buy">
	 <input type="hidden" name="ip_enc" 		value="'.md5($_SERVER['REMOTE_ADDR']).'"/>
	 <input type="hidden" name="aleatorio" 	value="'. $aleatorio .'"/>
	 <input type="hidden" name ="tienda" 	value ="paqueteria" />';
?>

					<div id="renew-response" class="bann_response"></div>
</form>					
				</div>
			</div>
		</div>

	</div>
</div>