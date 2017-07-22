<?php

//sistema de alertas pasa por aqui
include_once 'inc/clases/perfil/usuarios.class.php';
include_once 'inc/clases/procesos/fechas.class.php';
include_once 'inc/clases/alertas/alertas.class.php';
require_once 'inc/clases/seg/seg_lost_pw.class.php';

//objetos

$Perfil  = new seg_lost_pw();
$Alertas = new alertas();
$Usuario = new usuarios();
$Fechas  = new Fechas();


if($Alertas->alerta == 'new_alert'){					
	//nueva alerta creada por un visitante
	$Alertas->new_alert($Usuario);

}else if($Alertas->alerta == 'user_contact'){
	//visitante desea contactar con usuario
	$Alertas->user_contact($Perfil);

}else if($Alertas->alerta == 'alert_user'){
	//si es alert_user es alerta creada por un user
	$Alertas->user_especializacion($Usuario);
	

}else if($Alertas->alerta == 'alert_subscribe'){
	//si es alert_user es alerta creada por un user
	$Alertas->user_subscribe($Fechas);



}else{
	//cualquier otra cosa en post[alert] y eso es raro
	header('Location:index.php');
	
}

?>