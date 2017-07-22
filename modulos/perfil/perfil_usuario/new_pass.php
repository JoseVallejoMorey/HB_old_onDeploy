
<div class="col-sm-6 col-sm-offset-3">
  <div class="panel panel-default">
	<div class="panel-heading">
		Cambiar contraseña
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<div id="rew_pass" class="col-lg-3 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-12 cute-box">

				<form id="form_perfil_rew" name="form_perfil_rew" method="post" 
					class="form form-horizontal" rel="form_act" >
			<?php 
			echo '	<input type="hidden" name="form_to" value="perfil_cambiar_contrasena"/>
					<input type="hidden" name="ip_enc" value="'. md5($_SERVER['REMOTE_ADDR']).'"/>
					<input type="hidden" name="aleatorio" value="'. $_SESSION['invisible']['token_key'].'"/>';
			?>
					<div class="control-group">
						<input placeholder="Password actual" type="password" name="old_password" value=""/>
					</div>
					<div class="control-group">
						<input placeholder="Confirme password actual" type="password" name="old_password2"/>
					</div>
					<div class="control-group">
						<input placeholder="Nuevo password" type="password" name="password" value=""/>
					</div>
					<div class="control-group">
						<input placeholder="Confirme nuevo password" type="password" name="password2"/>
					</div>

					<div id="error-container">
			<?php
						if(!empty($_SESSION['action_error'])){
							echo'<div id="" class="error-report alert alert-danger">'
								.$_SESSION['action_error'].'</div>';
						}
			?>
					</div>		
					<div class="foot-btn">					
						<input  class="btn btn-default" type="submit" value="enviar"/>
					</div>

			</form>
			</div>
		</div>
	</div>
  </div>
</div> 