<?php

include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){



	echo '<input type="hidden" name="act" value="baja"/>';


	//si es particular no se le pide nada mas
	if($_POST['tipouser']=='empresa'){
		//es empresa hay que pedirle contraseña
		echo '<div class="control-group">
				<input placeholder="Password" type="password" name="password"/>
			  </div>';
		echo '<div class="control-group">
				<input placeholder="Confirme password" type="password" name="password2"/>
			  </div>';
	}
		echo '<div class="control-group">
				<select>
					<option>He vendido la propiedad que anunciaba</option>
					<option>Llevo mucho tiempo anunciandome sin resultado</option>
					<option>Ya no me interesa</option>
					<option>Otro</option>
				</select>
			  </div>';
		
		echo '<div class="control-group">
				<select>';
				for ($i=0; $i <11 ; $i++) { 
					echo '<option>'.$i.'</option>';
				}
		echo   '</select>
			  </div>';
			  
		echo '<div class="control-group">
				
			  </div>';			  	  


	echo '<h4>¿Desea continuar con la baja?</h4>';
	echo '<div class="foot-btn">
			<input class="btn btn-default" type="submit" value="Si, Continuar">
			<a class="btn btn-info">No, Atras</a>
		 </div>';

}





?>
