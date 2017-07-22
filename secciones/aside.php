<?php

include_once 'inc/clases/form_busqueda_builder.class.php';	//exclusiva para formularios ($Form)
// $Config = new show_config();
$Form = new busqueda_builder();	
$Con = new Modulos();




//archivo
if(!empty($_GET['archivo'])){

	echo '<a href="index.php">
			<button type="button" class="btn btn-primary btn-lg">Nueva Busqueda</button>
		 </a>';
	echo $Con->btn_crear_alerta('big');	
	echo $Con->btn_subscripcion ();
}else if(!empty($_GET['pagg'])){
	//si hay paginacion
	echo '<a href="index.php">
				<button type="button" class="btn btn-primary btn-lg">Nueva Busqueda</button>
			  </a>';
	echo $Con->btn_crear_alerta('big');	 
}else if(!empty($_GET['inmv'])){

}else if( (empty($_GET['archivo'])) && (empty($_GET['pagg'])) ){

	//sin archivo ni paginacion sacamos formulario buscador
	include'scripts/forms/buscador.php';
}
	





?>