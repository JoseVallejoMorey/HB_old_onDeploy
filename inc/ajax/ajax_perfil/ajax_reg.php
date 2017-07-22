<?php

include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	if( (!empty($_POST['tipo'])) && ($_POST['tipo'] == 'empresa') ){

echo '<div class="form-group">
	    <label class="col-md-3 control-label" for="select">Tipo de empresa </label>
	    <div class="col-md-9">        
	        <select name="tipo_empresa" class="form-control" size="1">
				<option>Inmobiliaria</option>
				<option>Promotora</option>
				<option>Constructora</option>
			</select>
	    </div>
	</div>';
echo '<div class="form-group">
	    <div class="input-group">
	        <input placeholder="Nombre de empresa" type="text"  class="form-control"
	        	   name="nombre_empresa" value="" req="required"/>
	        <span class="input-group-addon"><i class="fa fa-tag "></i></span>
	    </div>
	</div>';
echo '<div class="form-group">
	    <div class="input-group">
			<input placeholder="Telefono de empresa" type="text"  class="form-control"
		   		   name="empresa_telefono" value="" req="required"/>
	        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
	    </div>
	</div>';
echo '<div class="form-group">
	    <div class="input-group">
	        <input placeholder="NIF" type="text" name="nif" class="form-control"
				   value="" req="required"/>
	        <span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
	    </div>
	</div>';

	}
}

?>