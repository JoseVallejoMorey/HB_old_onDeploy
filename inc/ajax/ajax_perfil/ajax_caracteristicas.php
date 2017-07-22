<?php

include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){
	function habitaciones_group(){
		$var = '<tr class="form-group"><td>habitaciones</td><td><select name="habitaciones" class="form-control">';
	    $var .='<option></option>';     
		for($i=0;$i<8;$i++){$var.='<option>'.$i.'</option>';}
		$var .='</select></td></tr>';
		return $var;
	}

	function banos_group(){
		$var = '<tr class="form-group"><td>baños</td><td><select name="banos" class="form-control">';
		$var .='<option></option>';                     
	    for($i=0;$i<6;$i++){$var.= '<option>'.$i.'</option>';}
		$var .='</select></td></tr>';
		return $var;
	}
	
	$permitidos = array(0,1,2);
	if(in_array($_POST['inmueble'], $permitidos)){
		if( ($_POST['inmueble'] != '3') && ($_POST['inmueble'] != '4') ){
			echo habitaciones_group();
			echo banos_group();
		}
	}

}
?>