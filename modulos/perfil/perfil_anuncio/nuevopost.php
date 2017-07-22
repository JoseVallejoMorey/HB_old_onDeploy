<?php

//si no resuelve_enigma sera un anuncio nuevo...
include 'inc/vars.php';
require 'inc/clases/perfil/anuncio_creator.class.php';
//$Con es objeto Usuarios 

$Creator = new anuncio_creator();
$Form 	 = new form_builder();


if($Creator->estado_anuncio == 'nuevo'){
	$titulo = 'Nuevo anuncio';
}else if($Creator->estado_anuncio == 'actualizar'){
	$titulo = 'Actualizar anuncio';
}else if($Creator->estado_anuncio == 'borrador'){
	$titulo = 'Borrador de anuncio';
}



//echo $titulo;
?>




<?php

//si es nuevo anuncio comprueba que haya paquete disponible, si es actualizar
//o borrador le permite continuar
if( ($Creator->estado_anuncio == 'nuevo') && ($Creator->paquete_elegido == false) ){
	
	//tendra que comprar otro paquetre de anuncios
	echo '<p class="msj_p">No tiene espacio para mas anuncios, puede consultar 
		  sobre los paquetes de anuncios disponibles.</p>';
	include 'modulos/perfil/perfil_tienda/perfil_paquetes.php';
	//fin de este camino


}else{
	//dara un numero de paquete valido
	//echo $Creator->huecos_restantes();
	//se definen variables del formulario segun estado del anuncio
	$Creator->definiendo_values();



//formulario
//====================================================
	echo '<div class="row">';
	echo '<div id="crear-anuncio" class="col-md-8">';
	echo '<div id="wizard1" class="wizard-type1">';
	echo '<form id="publicar_1" method="post" action="">';

	echo '<input type="hidden" name="ip_enc" 	value="'. md5($_SERVER['REMOTE_ADDR']).'"/>';
	echo '<input type="hidden" name="aleatorio" value="'. $_SESSION['invisible']['token_key'].'"/>';
	echo '<input type="hidden" name="paquete"   value="'. $Creator->val['v_paquete'].'"/>';
	echo '<input type="hidden" name="ussr"	    value="'. $_SESSION['user_id'].'" />';
	echo '<input type="hidden" name="art"  		value="'. $Creator->art.'" />';
	echo '<input type="hidden" name="form_to" 	value="new_anuncio"/>';
	echo '<input type="hidden" name="lang_form" value="'.$_SESSION['lg'].'"/> ';
	echo $Creator->hidden_borrator();
	//echamos todo el contenido del form
	echo $Creator->anuncio_big_creator($Form,$__tipo_venta);

	echo '</form>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}

?>