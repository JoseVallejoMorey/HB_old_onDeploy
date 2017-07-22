<?php
$Config = new perfil_config();
?>



<div class="panel panel-default">
  <div class="panel-heading">
	<ul class="nav nav-tabs pull-left" >
	  <li class="active"><a href="#msecciones" data-toggle="tab">Secciones</a></li>
	  <li><a href="#mdirectivas" data-toggle="tab">Directivas</a></li>
	  <li><a href="#mlanding" data-toggle="tab">Landing Page</a></li>
	  <li><a href="#mestado" data-toggle="tab">Estado portal</a></li>
	</ul>
  </div>
  <div class="panel-body">
	<div class="tab-content">

      <!-- Secciones -->
	  <div class="tab-pane active" id="msecciones">    

		<div class="panel panel-default">
		  <div class="panel-heading">
			  <ul class="nav nav-tabs pull-left">
			  <li class="active"><a href="#secciones1" data-toggle="tab">Secciones Activa</a></li>
			  <li class=""><a href="#secciones2" data-toggle="tab">Secciones Promo</a></li>
			  <li class=""><a href="#secciones3" data-toggle="tab">Secciones Mantenimiento</a></li>
			  </ul>
		  </div>
		  <div class="panel-body">
			<div class="tab-content">
			  <div class="tab-pane active" id="secciones1">    
				<?php echo $Config->master_control('activa');	?>
			  </div>
			  <div class="tab-pane" id="secciones2">
				<?php echo $Config->master_control('promo');	?>
			  </div>
			  <div class="tab-pane" id="secciones3">
				<?php echo $Config->master_control('mantenimiento');	?>
			  </div>
			</div>
		  </div>
		</div>



	  </div>
	  <!-- Directivas -->
	  <div class="tab-pane" id="mdirectivas">

		<div class="panel panel-default">
		  <div class="panel-heading">
			<ul class="nav nav-tabs pull-left">
		      <li class="active"><a href="#direct1" data-toggle="tab">Directivas empresa</a></li>
			  <li><a href="#direct2" data-toggle="tab">Directivas particular</a></li>
			  <li><a href="#direct3" data-toggle="tab">Directivas promo empresa</a></li>
			  <li><a href="#direct4" data-toggle="tab">Directivas promo particular</a></li>	
			</ul>
		  </div>
		  <div class="panel-body">
			<div class="tab-content">

			  <div class="tab-pane active" id="direct1">
			  	<?php echo $Config->master_control('directivas_empresa','directiva');	?>
			  </div>
			  <div class="tab-pane" id="direct2">
			  	<?php echo $Config->master_control('directivas_particular','directiva');	?>
			  </div>
			  <div class="tab-pane" id="direct3">
			  	<?php echo $Config->master_control('directivas_promo_empresa','directiva');	?>
			  </div>
			  <div class="tab-pane" id="direct4">
			  	<?php echo $Config->master_control('directivas_promo_particular','directiva');	?>
			  </div>

			</div>
		  </div>
		</div>
	  </div>

	  <!-- Landing Page  -->
	  <div class="tab-pane" id="mlanding">
	  	<?php echo $Config->perfil_landing();	?>
	  </div>
	  <!-- Estado -->
	  <div class="tab-pane" id="mestado">
		<form id="masterman" method="post">
<?php	
		echo '<input type="hidden" name="ip_enc" value="'. md5($_SERVER['REMOTE_ADDR']).'"/>
			  <input type="hidden" name="aleatorio" value="'. $_SESSION['invisible']['token_key'].'"/>
			  <input type="hidden" name="mda_control" value="estado"/>';
		//echo $Config->botonera_estado();

		if($salida = $this->getOne('estado')){
			foreach ($salida as $key => $value) {
				if($value == 1){ $estado = $key; }
		}	}

?>	
		<table class="table">
		  <tr><td>Estado actual</td>
		  	<td><?php echo $estado; ?></td>
		  </tr>
		  <tr><td>Cambiar estado</td>
		  	<td><select name="nuevo_estado">
		  		  <option value="1">Activo</option>
		  		  <option value="2">Promo</option>
		  		  <option value="3">Mantenimiento</option>
		  	  	</select></td>
		  </tr>
		  <tr><td></td>
		  	<td><input type="submit" value="Cambiar" /></td>
		  </tr>
		</table>

		</form>
	  </div>

	</div>
  </div>
</div>