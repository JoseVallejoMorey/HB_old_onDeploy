<?php

//perfil especial
$saltador = $Perfil->select_salt();
//head del formulario
echo'<form id="form_special" method="post" class="form_buy" >
		<input type="hidden" name="saltador" value="'.$saltador.'"/>
		<input type="hidden" name="ip_enc" value="'.md5($_SERVER['REMOTE_ADDR']).'"/>
    	<input type="hidden" name="aleatorio" value="'. $_SESSION['invisible']['token_key'] .'"/>';
?>

<div class="row">
	<div class="col-md-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<ul class="nav nav-tabs pull-left tienda-helper" id="tabs">
			<li class="active"><a href="#comprar_special" data-toggle="tab">Nueva area especial</a></li>
			<li class=""><a href="#renovar_special" data-toggle="tab">Areas contratadas</a></li>

				</ul>
			</div>
			<div class="panel-body">
				
				<div class="tab-content">
					<div class="tab-pane active" id="comprar_special">    

						<div id="disp_answer" class="col-lg-2">
							<div class="answer_0">
								<h5>Seccion en la que desea aparecer</h5> 
								<select class="" name="seccion">
									<option>venta</option>
									<option>alquiler</option>
									<option>comercial</option>
								</select>
							</div>
							<div class="answer_0">
								<h5>Duracion que desea contratar</h5>
								<select class="" name="periodo">
									<option value="7">Una Semana (7 dias)</option>
									<option value="14">Dos Semanas (14 dias)</option>
									<option value="30">Un Mes (30 dias)</option>
									<option value="90">Tres meses (90 dias)</option>
						    	</select>
							</div>
						</div>

						<div id="disp_response" class="col-lg-10"></div>

					</div>
					<!--alertas vistas-->
					<div class="tab-pane" id="renovar_special">
				<?php echo $Con->efectivas_renew('special'); ?>
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