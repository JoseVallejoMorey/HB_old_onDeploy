<?php

if(!empty($_GET['conf'])) {
	


	$cons2 = mysql_query("SELECT * FROM usuarios WHERE md5(id) = '".$_GET['conf']."' ");
	
	$cuantos = mysql_num_rows($cons2);
	
	if($cuantos > 0) {
		
		mysql_query("UPDATE usuarios SET verificado = 1 WHERE md5(id) = '".$_GET['conf']."'");
		echo 'Verificación exitosa!!';
	
	} else {
		echo 'este usuario no existe';
	}

}

?>