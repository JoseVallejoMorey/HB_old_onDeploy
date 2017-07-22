<meta charset="utf-8">
        
<?php


//aqui la maquina tiene que detectar en que seccion de la pagina se encuentra

//rel-alternate


//verifica como se ven distintos idiomas, probar
//http://flang.dejanseo.com.au/	



    $project_name = 'PisosMallorca';


	$anterior = '';
	$posterior = '';
	$title = '';
	$description = '';
	$keywords = '';
	
	//var_dump($Config);

//en tehoria cuando quiero potenciar la seccion la meto antes del nombre
//sino despues	

	if(!empty($_GET['pag'])){
		//config debera cojer el titulo del anuncio y mostrarlo aqui
		$anterior = $Config->anuncio['subtipo_inmueble'].' en 
		    	  '.$Config->anuncio['tipo_venta'].' en 
		          '.$Config->anuncio['municipio'].' | ';
	}

    if(!empty($_GET['pagg'])){}


    if(isset($_GET['inmv'])){
    	if(empty($_GET['inmv'])){
			$anterior = 'Inmobiliarias en Mallorca | ';    		
    	}else{
    		$anterior = $_GET['inmv'].' | ';
    	}
    }


    if(!empty($_GET['archivo'])){
		if($_GET['archivo'] == 'condiciones'){
			$posterior = ' | Condiciones del servicio';
		}
		if($_GET['archivo'] == 'legal'){
			$posterior = ' | Aviso Legal';
		}
	}



	if(!empty($_GET['mn_nav'])){
        //perfil de usuario
		if($_GET['mn_nav'] == 'comercial'){
			$anterior = 'Propiedades Comerciales | ';
			$description = 'Venta y Alquiler de Oficinas, Locales, Naves en Mallorca, menorca e ibiza';
		}else{
			$anterior = 'Propiedades en '.$_GET['mn_nav'].' | ';
			$description = 'Pisos, chalets, Aticos en '.$_GET['mn_nav'].' en Mallorca, menorca e ibiza';
		}
    }


	?>

    <title><?php echo $anterior . $project_name . $posterior;  ?></title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="author" content="" />
    <meta name="keywords" content="" />