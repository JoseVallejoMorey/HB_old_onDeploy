<?php
$Fecha = new fechas();

echo'<form method="post" id="form-pro" class="form_buy">
	 <input type="hidden" name="ip_enc" value="'.md5($_SERVER['REMOTE_ADDR']).'"/>
     <input type="hidden" name="aleatorio" value="'. $_SESSION['invisible']['token_key'] .'"/>
     <input type="hidden" name ="tienda" value ="promocionar" />';
?>

<div class="row">
	<div class="col-md-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<ul class="nav nav-tabs pull-left tienda-helper" id="tabs">
		  <li class="active"><a href="#promocionar" data-toggle="tab">Promocionar</a></li>
		  <li class=""><a href="#promocionados" data-toggle="tab">Promocionados</a></li>

				</ul>
			</div>
			<div class="panel-body">	
				<div class="tab-content">
					<div class="tab-pane active" id="promocionar">
				    	
						<div id="promo_selects" class="col-lg-2">
					        <h5>Cantidad</h5>
							<select name="cantidad">
								<option value="0">Seleccione</option>
								<option value="1">Promocionar 1 anuncio</option>
								<option value="5">Promocionar 5 anuncios</option>
								<option value="10">Promocionar 10 anuncios</option>
							</select>

					        <h5>Periodo</h5>
							<select name="periodo">
								<option value="0">Seleccione</option>
								<option value="14">Dos Semanas (14 dias)</option>
								<option value="30">Un Mes (30 dias)</option>
								<option value="90">Tres meses (90 dias)</option>
							</select>
						</div>

						<div class="col-lg-10">
					    	<div id="anuncios_pro">
								<?php echo $Con->seleccione_promocionados(); ?>
							</div>
						</div>
				    </div>
		        
					<div class="tab-pane" id="promocionados">
						<?php echo $Con->get_promocionados($Fecha); ?>
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
				<div id="renew-response"  class="tab-content">
					
				</div>
			</div>
		</div>

	</div>

</div>
</form>