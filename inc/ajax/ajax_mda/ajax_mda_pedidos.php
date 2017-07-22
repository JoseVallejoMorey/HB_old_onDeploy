<?php
//mostraremos aqui si es un pedido de un banner, si es una traduccion nueva o si
//se quiere editar una traduccion existente (mda)
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	//includes razonables
	resolve_includes();

	require_once '../../clases/mda/pedidos.class.php';
	//objetos
	$Pedidos = new Pedidos();

	//datos del pedido
	$num   = $_POST['pedido'];	//id de fila en tabla (idiomas o banners)
	$tiket = $_POST['tiket'];	//numero del pedido

	$datos_pedido = $Pedidos->discernir_pedido($tiket,$num);

	if($datos_pedido[1] == 'banner'){
		echo $Pedidos->pedidio_es_nuevo_banner($datos_pedido);
	}else if($datos_pedido[1] == 'traduccion'){
		echo $Pedidos->pedidio_es_traduccion($datos_pedido);
	}else{
		echo 'Resultado Inesperado';
	}

}

?>