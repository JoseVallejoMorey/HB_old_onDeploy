<?php
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	require '../../clases/mysqlidb.main.php';
	require '../../clases/builders.class.php';
	require '../../clases/seg/seg_builder.class.php';	
	require '../../clases/mda/stock.class.php';  //modulos

	require '../../clases/procesos/reserva_builder.class.php';	//reservas
	require '../../clases/procesos/fechas.class.php';

	$Tienda = new fechas();
	$Stock 	= new Stock();
	$Anuncios = new builders();
			
	if($_POST['stock'] =='all' ){
		//mostrar todo (nay)	
	}else if($_POST['stock'] == 'paquetes' ){
		//mostrar paquetes
		echo $Stock->show_paquetes($Tienda);
	}else if ($_POST['stock'] == 'promo'){
		//anuncios promocionados
		echo $Stock->get_promanuncios($Anuncios);
	}else{
		//universal pictures presents
		echo $Stock->show_service($Anuncios, $Tienda,$_POST['stock']);
	}
}

?>