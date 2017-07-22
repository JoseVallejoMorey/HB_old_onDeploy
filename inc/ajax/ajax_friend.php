<?php 
//funciones e includes comunes a todos los ajax


//intenta verificar que sea una llamada ajax y no se este accediendo al archivo de otro modo
function requested(){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
	&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return true;
	}else{
		return false;
	}
}

//cada ajax enviado contiene un tok y un token
// cuenta los post recibidos y compara con los enviados
// (arrays no se declaran)
function tok_y_token(){

	//var_dump($_POST);
	//miramos que numero de campos coincida
	//manual para que no me jodan las array
	$cuantos = 0;
	foreach ($_POST as $key => $value) {
		if(!is_array($value)){$cuantos++;}
	}
	//var_dump($cuantos);
	if(!empty($_POST['tok'])){
		$tok_recibido = explode('_',$_POST['tok']);
		$tok_generado = $cuantos.'_'.$tok_recibido[1];
		unset($_POST['tok']);

	}else{
		return false;
	}
	$string = $tok_generado;

	//token recibido borramos de POST
	if(!empty($_POST['token'])){
		$token_recibido = $_POST['token'];
		unset($_POST['token']);
	}else{
		return false;
	}
	//construimos cadena a convertir
	foreach ($_POST as $key => $value) {
		if(!is_array($value)){
			$string .= $value;

		}
	}

	$token_generado = hash('sha512', $string);

	// var_dump($token_generado);
	// var_dump($token_recibido);
	//datos enviados coinciden con datos recibidos
	if($token_recibido == $token_generado){
		return true;
	}else{
		  // var_dump($token_recibido);
		  // var_dump($token_generado);
		return false;
	}

}



// funcion vital para todos los tienda_ajax (incluye clases de los objetos que
// luego seran construidos)
function resolve_includes($seg = NULL){
	require_once '../../clases/mysqlidb.main.php';
	require_once '../../clases/seg/seg_builder.class.php';
	require_once '../../clases/builders.class.php';
	if($seg == true){
		//require_once '../../clases/mysqlidb.main.php';		//aveces reservas necesita un empujon
		require '../../clases/seg/seg_actions.class.php';	//para reservas
	}
	//algunos me estan requiriendo builders, y luego updateimg
	require '../../clases/procesos/sql_operations.class.php';
	require '../../clases/procesos/reserva_builder.class.php';	//reservas
	require '../../clases/procesos/fechas.class.php';			//clase fechas
	require '../../clases/procesos/productos.class.php';	//info de los productos
}

function includes_simples(){
	require '../clases/mysqlidb.main.php';
	require '../clases/seg/seg_builder.class.php';
	require '../clases/builders.class.php';
}

function includes_edit_anuncio(){
	require '../../clases/mysqlidb.main.php';
	include '../../clases/builders.class.php';
	require '../../clases/seg/seg_builder.class.php';
	require '../../clases/procesos/reserva_builder.class.php';	//para modulos
	include '../../clases/perfil/usuarios.class.php';	//clase
}

//trae includes para ajax de perfil empresa, usuario y logo
function includes_perfil_info(){
	require_once '../../clases/mysqlidb.main.php';		//aveces reservas necesita un empujon
	require_once '../../clases/seg/seg_actions.class.php';
}

//para busquedas con ajax (busqueda principal y busqueda empresa) MAGNIFICO
function includes_busqueda(){
	require_once '../clases/mysqlidb.main.php';
	require_once '../config.php';					//algunas variables globales
	require_once '../funciones/fun_session.php';	//inicia session
	require_once '../clases/builders.class.php';	//necesaria para empezar
	require_once '../clases/procesos/reserva_builder.class.php';	//para modulos
	require_once '../clases/public/modulos.class.php';				//extends builders
	require_once '../clases/public/salida_anuncios.class.php';		//extends modulos
	require_once '../clases/seg/seg_builder.class.php';				//objeto perfil

	require '../clases/paginate.php';	//paginacion de anuncios

}


?>