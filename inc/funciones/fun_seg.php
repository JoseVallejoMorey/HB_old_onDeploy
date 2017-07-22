<?php

//valida post recibidos filtrando con lista negra
//valida key de get con lista blanca y value de get con listas negras
//valida token de session y listas blancas
//===============================================

//Objeto de filtrado inquisitor (get, post y session)
$check = new Inquisitor();
if($check->police_man() == false){

	var_dump($check->report);
	die(var_dump($check->report_key));
}

?>