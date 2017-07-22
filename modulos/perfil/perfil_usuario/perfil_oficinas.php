<div class="row">
	<div id="form-oficina" class="col-md-8">



		<div class="panel panel-default">
			<div class="panel-heading">
				<h2><i class="fa fa-pencil-square-o"></i>Agregar Oficina</h2>
				<div class="panel-actions">
					<a href="#" class="btn-minimize"><i class="icon-arrow-down"></i></a>
				</div>
			</div>
			<div style="display: none;" class="panel-body">

		  	<form id="nueva_oficina" method="post" enctype="multipart/form-data" class="">
		    <input type="hidden" name="ip_enc" value="<?php   echo md5($_SERVER['REMOTE_ADDR'])?>"/>
		    <input type="hidden" name="aleatorio" value="<?php  echo $_SESSION['invisible']['token_key'] ?>"/>
		    <input type="hidden" name="form_to" value="nueva_oficina"/>

				<div class="row">
					<div class="col-sm-6">
						<div class="img-assist">
							<label class="control-label" for="inputError">
							<h4>Imagen de Oficina</h4></label>
							<input class="form-control" id="imagenes" type="file" name="img" />
						</div>

						<label class="checkbox-inline" for="inputError">
						<input type="checkbox" name="sede_central" value="1" />Marcar como Sede Central
						</label>
						<br>
						<label class="control-label" for="inputError">
						<h4>Nombre</h4></label>
						<input class="form-control" type="text" name="nombre" value="" req="required" />
						<label class="control-label" for="inputError">
						<h4>Poblacion</h4></label>
						<select class="form-control" name="poblacion"></select>
						<label class="control-label" for="inputError">
						<h4>Direccion</h4></label>
						<input class="form-control" type="text" name="direccion" value="" req="required" />
					</div>

					<div class="col-sm-6">

						<label class="control-label" for="inputError">
						<h4>Fax</h4></label>
						<input class="form-control" type="text" name="fax" value="" class="tel-val"/>
						<label class="control-label" for="inputError">
						<h4>Telefono de oficina</h4></label>
						<input class="form-control tel-val" type="text" name="tel" req="required" value="" />
						<label class="control-label" for="inputError">
						<h4>Moviles</h4></label>
						<input class="form-control tel-val" type="text" name="movil" value="" />
						<input class="form-control tel-val" type="text" name="movil2" value="" />
						<label class="control-label" for="inputError">
						<h4>Email</h4></label>
						<input class="form-control email-val" type="text" name="email" value="" />

						<input class="btn btn-success" type="submit" value="Guardar" />
					</div>
				</div>

			</form>

			</div>
		</div>





		<div class="panel panel-default">
			<div class="panel-heading">
				<h2><i class="fa fa-user"></i>Oficinas</h2>
			</div>
			<div class="panel-body no-padding">
				<?php echo $Con->show_oficinas_perfil(); ?>
			</div>
		</div>




	</div>



	<div class="col-md-3">
		<div class="panel panel-success">
			<div class="panel-heading">
				<h2><i class="fa fa-user"></i>Sede Principal</h2>
			</div>
			<div class="panel-body no-padding">
				<?php echo $Con->show_sede_central(); ?>
			</div>
		</div>
	</div>









</div>