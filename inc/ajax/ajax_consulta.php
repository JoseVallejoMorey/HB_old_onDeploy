<?php

include 'ajax_friend.php';
if( (requested() == true) && (tok_y_token() == true) ){

	//includes para busqueda
	includes_busqueda();

	//objetos
	$Con = new salida_anuncios();

	//consulta a la base con los parametros escogidos para la paginacion
	//=================================================================
	// echo '<div id="consulta_main" class="col-lg-9 col-md-9 col-sm-12">';
	echo $Con->showStar();
	echo $Con->paginacion_anuncios();
	echo $Con->showSpecial();
	// echo '</div>';

	//columna derecha
	//=====================================================
// 	echo '<div id="anunciantes_v" class="col-lg-3 col-md-3 hidden-sm hidden-xs bann3" 
// 			   style="margin-top:-50px">'.
// 			$Con->show_banner('lateral').'</div>';
}

?>