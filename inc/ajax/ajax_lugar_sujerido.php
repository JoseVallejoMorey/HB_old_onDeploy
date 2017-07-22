<?php 

include 'ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){

	includes_simples();
	$Con = new builders(); 
	$cadena = $_POST['sujerencia'];
	$pro = $Con->get('zonas');
	$mun = $Con->get('municipios');


// var_dump($pro);
// var_dump($mun);
	function normaliza ($cadena){
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    $cadena = strtolower($cadena);
    return utf8_encode($cadena);
}


	$municipios = array();
	$i=0;
	foreach ($mun as $key => $value) {
		if(stripos(normaliza($value['municipio']),$cadena ) !== false){
			$i++;
			$municipios[$i]['id'] = $value['id'];
			$municipios[$i]['zona'] = $value['zona'];
			$municipios[$i]['poblacion'] = $value['municipio'];		
		}
	}

	$i = 0;
	echo '<ul>';
	foreach ($municipios as $key => $value) {
		$i++;
		$zona = 'Mallorca';
		if($value['zona'] == 2){$zona = 'Palma de Mallorca';}
		if($value['zona'] == 9){$zona = 'Menorca';}
		if($value['zona'] == 10){$zona = 'Ibiza';}

		echo '<li rel="'.$value['zona'].'" rol="'.$value['id'].'">'.$value['poblacion'].', '.$zona.'</li>';
		if($i == 10){
			break;
		}

	}
	echo '</ul>';

	//var_dump($municipios);

}

?>