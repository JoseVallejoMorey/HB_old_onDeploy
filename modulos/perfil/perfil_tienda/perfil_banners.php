 <?php

$saltador = $Perfil->select_salt();
//obtiene los banners ya existentes en catalogo de usuario
$banners = $Con->banners_existentes();

echo '<form id="form_bann" class="form_buy" method="post" enctype="multipart/form-data">';
echo 	'<input type="hidden" name="saltador" value="'.$saltador.'"/>';
echo 	'<input type="hidden" name="ip_enc" value="'.md5($_SERVER['REMOTE_ADDR']).'"/>';
echo 	'<input type="hidden" name="aleatorio" value="'.$_SESSION['invisible']['token_key'] .'"/>';

?>


<div class="row">
	<div class="col-md-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<ul class="nav nav-tabs pull-left tienda-helper" id="tabs">
					<li class="active"><a href="#comprar_banners" data-toggle="tab">Nuevo banner</a></li>
					<li class=""><a href="#renovar_banners" data-toggle="tab">Ver contratados</a></li>
				</ul>
			</div>

			<div class="panel-body">
				<div class="tab-content">

					<!--nuevo banner-->
					<div class="tab-pane active" id="comprar_banners">

						<div id="perfil_banner_form" class="col-lg-2 col-sm-2 col-2">      
						    <div id="" class="">
						    	 <h5>Tipo de Banner</h5>
						    	<select class="" name="tipo_bann">
						            <option></option>
						            <option value="nuevo">Nuevo banner</option>
									<?php echo $banners;?>
						        </select>
						        <fieldset disabled>
							        <h5>Seleccione un tipo de banner</h5>
							        <select class="launcher" name="tipo_bann_nuevo">
							            <option></option>
										<option value="superior">Superior(728x90)</option>
							            <option value="central">Central(600x70)</option>
							            <option value="lateral">Lateral(260x600)</option>
									</select>
						        </fieldset>
							</div>

							<div id="banner_data" class="launcher">
								<h5>Periodo</h5>
								<select class="launcher" name="periodo">
									<option value="14">Dos Semanas (14 dias)</option>
									<option value="30">Un Mes (30 dias)</option>
									<option value="90">Tres meses (90 dias)</option>
								</select>
							</div>
						</div>

						<div class="col-lg-10">
							    
				<?php 		include 'scripts/modals/modal_banners.php';?>

							<div id="bann_response1" class="bann_response"></div>
						    
							
						</div>

					</div>
					<!--ver banners contratados-->
					<div class="tab-pane" id="renovar_banners">
				<?php 
					echo $Con->efectivas_renew('banner');
				?>
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