<?php 

//objeto tienda
$Tienda = $Con->Reserva;

$permiso_baja = false;
$paquetes 	= $Con->mostrar_paquetes_actuales();
$star 	  	= $Con->mostrar_efectivas('star_area');
$banners  	= $Con->mostrar_efectivas('banner');
$special  	= $Con->mostrar_efectivas('special');
$salt 		= $Perfil->select_salt();


?>
<div class="panel panel-default">
	<div class="panel-heading">
		<ul class="nav nav-tabs pull-left" id="tabs">

<?php
if($paquetes != false){	
	echo '<li class="active"><a href="#wiew-paq" data-toggle="tab">Paquetes</a></li>';	}
if($star 	 != false){	
	echo '<li><a href="#wiew-star" data-toggle="tab">Area star</a></li>';				}
if($special  != false){	
	echo '<li><a href="#wiew-special" data-toggle="tab">Area especial</a></li>';		}
if($banners  != false){	
	echo '<li><a href="#wiew-banners" data-toggle="tab">Banners contratados</a></li>';	}
?>

		</ul>
	</div>
	<div class="panel-body">
		<div class="tab-content">
<?php
if($paquetes != false){	echo '<div class="tab-pane active" id="wiew-paq"> '.$paquetes.'</div>';	}
if($star 	 != false){	echo '<div class="tab-pane" id="wiew-star">'.$star.'</div>';			}
if($special  != false){	echo '<div class="tab-pane" id="wiew-special">'.$special.'</div>';		}
if($banners  != false){	echo '<div class="tab-pane" id="wiew-banners">'.$banners.'</div>';		}

if( ($star != false) || ($special != false) || ($banners != false) ){
	//var_dump('tiene cosas, no puede marcharse todavia');
	//permiso_baja seguira siendo false
}else{
	//si tiene un paquete comprado no podra largarse(inicial si)
	if($paquetes != false){
		$Tienda->where('user',$Perfil->user);
		if($salida = $Tienda->get('paquetes')){
			foreach ($salida as $key => $value) {
				if($value['paquete_inicial'] == 1){
					//var_dump('es paquete inicial');
					$permiso_baja = true;
	}	}	}	}
}


if($permiso_baja == false){
	echo '<p class="msj_p">No puede darse de baja mientras tenga reservas o servicios contratados<p>';
}else{
	echo '<p class="msj_p">Permanecer registrado en "pagina" es totalmente gratuito y es 
		  publicidad para su empresa, ¿seguro que desea continuar?<p>';
	echo '<div>
			<a class="btn btn-info">Atras</a>
			<a class="btn btn-default" id="confirm1" rel="'.$Con->tipo_usuario.'">
			   Continuar
			</a>
		  </div>';


	echo '<form id="form_baja" method="post" rel="form_act">
			<input type="hidden" name="ip_enc" 		value="'.md5($_SERVER['REMOTE_ADDR']).'"/>
			<input type="hidden" name="aleatorio" 	value="'.$_SESSION['invisible']['token_key'].'"/>
			<input type="hidden" name="saltador"	value="'.$salt.'" />';  
	echo '<div class="col-lg-6" id="confirm_response">';

	echo 	'<div id="error-container">';
				if(!empty($error)){
					echo'<div id="" class="error-report alert alert-error">'.$error.'</div>';
				}
	echo 	'</div>';
	echo '</div>';
	echo '</form>';
}


?>
		</div>
	</div>
</div>
<?php 

// $permiso_baja solo sera true si no tiene ningun servicio contratado
// $permiso_baja no incluye (paquete de regalo)
// si tiene algo sera siempre false


//si true le sacaremos informacion de lo que se pierde y botones de no y continuar
//que seran los que lanzan el ajax, (con empresa o particular)







//hay que saber si es empresa o particular
//si es particular le pondremos menos pegas

//si es particular y tiene servicios activos se le advierte, pero se le deja irse
//si es empresa y tiene servicios activos no se le deja darse dde baja hasta que estos terminen

//si puede darse de baja le mostrara un formulario cortito donde se le pidan sus motivos para abandonarnos

//-he vendido la/las propiedad/propiedades que anunciaba
//ha pasado mucho tiempo desde que publique, sin resultado
//me pica un huevo
//otros

//algun comentario para despedirse

//valore del 1 al 10 su experiencia en nuestra pagina

//bye bye

?>