<?php 

//devuelve array con salt, input_salt y lo que mostrar star
$star = $Con->get_anuncios_star();

echo '<form id="form_star" method="post" class="form_buy">
		'.$star['saltador_input'].'					
		<input type="hidden" name="ip_enc" value="'.md5($_SERVER['REMOTE_ADDR']).'"/>
		<input type="hidden" name="aleatorio" value="'. $_SESSION['invisible']['token_key'] .'"/>';
?>


<div class="row">
	<div class="col-md-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<ul class="nav nav-tabs pull-left tienda-helper" id="tabs">
			<li class="active"><a href="#comprar_star" data-toggle="tab">Anuncio estrella</a></li>
			<li class=""><a href="#renovar_star" data-toggle="tab">Ver area contratada</a></li>
				</ul>
			</div>

			<div class="panel-body">
				<div class="tab-content">


					<!--nuevas alertas-->
					<div class="tab-pane active" id="comprar_star">
						<div id="" class="col-lg-2">
							<div id="periodo_star">
								<h5>Duracion que desea contratar</h5>
								<select class="" name="periodo">
									<option value="14">Dos Semanas (14 dias)</option>
									<option value="30">Un Mes (30 dias)</option>
									<option value="90">Tres meses (90 dias)</option>
								</select>
						  	</div>
						</div>
						<div class="col-lg-10">
		<?php 		  echo '<div id="star_monstruario" class="col-lg-12">'.$star['mostrar'].'</div>';	?>
							<div class="col-lg-6 disp_response"></div>
						</div>
					</div>
					<!--alertas vistas-->
					<div class="tab-pane" id="renovar_star">
						<?php echo $Con->efectivas_renew('star_area'); ?>
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
					<div id="renew-response" class="bann_response"></div>
				</div>
			</div>
		</div>

	</div>



</div>
</form>