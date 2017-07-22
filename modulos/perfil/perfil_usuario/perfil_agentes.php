<div class="row">

	<div id="form-agente" class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2><i class="fa fa-pencil-square-o"></i>Agregar Agente</h2>
				<div class="panel-actions">
					<a href="#" class="btn-minimize"><i class="icon-arrow-down"></i></a>
				</div>
			</div>
			<div style="display: none;" class="panel-body">




  <form id="nuevo_agente" method="post" enctype="multipart/form-data" class="">
    <input type="hidden" name="ip_enc" value="<?php   echo md5($_SERVER['REMOTE_ADDR'])?>"/>
    <input type="hidden" name="aleatorio" value="<?php  echo $_SESSION['invisible']['token_key'] ?>"/>
    <input type="hidden" name="form_to" value="nuevo_agente"/>


<div class="row">	
<div class=" col-sm-6">
	<div class="img-assist">
		<label class="control-label" for="inputError">
		<h4>Imagen</h4></label>
		<input class="form-control" id="imagenes" type="file" name="img" />
	</div>
	<label class="control-label" for="inputError">
	<h4>Nombre</h4></label>
	<input class="form-control" type="text" name="nombre" value="" req="required" />
	<label class="control-label" for="inputError">
	<h4>Cargo</h4></label>
	<input class="form-control" type="text" name="cargo" value="" req="required" />
</div>


<div class=" col-sm-6">
	<label class="control-label" for="inputError">
	<h4>Idiomas</h4></label>
	<select class="form-control" name="idiomas"></select>
	<label class="control-label" for="inputError">
	<h4>Movil</h4></label>
	<input class="form-control" type="text" name="movil" value="" req="required" class="tel-val"/>
	<label class="control-label" for="inputError">
	<h4>Email</h4></label>
	<input class="form-control" type="text" name="email" value="" req="required" class="email-val"/>
	<input class="btn btn-success" type="submit" value="Guardar" />
</div>


</div>







    















  </form>












			</div>
		</div>
	</div>




	<div class="col-md-8">
		<div class="panel panel-default panel-agente">
			<div class="panel-heading">
				<h2><i class="fa fa-user"></i>Agentes</h2>
			</div>
			<div class="panel-body no-padding">
				<?php echo $Con->show_agentes_perfil();  ?>
			</div>
		</div>
	</div>





</div>