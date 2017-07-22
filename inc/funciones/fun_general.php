<?php
//algunos modulos de codigo

//$Form = new form_builder();

function modal_subscription($Form){

$return = '<div class="modal fade" id="modal-subscribe" tabindex="-1" role="dialog" 
			aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">';
$return.= 	'<form id="alert_subscribe" method="post" class="family_alert">	';
$return.= 	'<input type="hidden" name="ip_enc" 	value="'.md5($_SERVER['REMOTE_ADDR']).'"/>';
$return.= 	'<input type="hidden" name="aleatorio" value="'.$_SESSION['invisible']['token_key'].'"/>';
$return.= 	'<input type="hidden" name="lang_form" value="'.$_SESSION['lg'].'"/>';
$return.= 	'<input type="hidden" name="alerta"  	value="alert_subscribe" />';
	
			
			
$return.=  	'<div class="modal-content">';
$return.=	 '<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Contacte con el anunciante</h4>
			  </div>';

$return.=	'<div class="modal-body">';
			  //body del modal
				
				
				
				
				
				
$return.=	'<div class="row">
				<div class="col-lg-12 col-sm-12">
			        <p>Puedes crear una alerta con tus intereses, recibiras novedades y 
			        ofertas por email</p>  
			    </div><hr />';

			    //<!-- primera fase -->
$return.=		'<div class="col-lg-12 col-sm-12">
			    	<div class="col-lg-4 col-sm-4">
			    	<label class="control-label" for="inputError"><h4>Nombre</h4></label>
			        <input type="text" class="form-control" name="nombre" value=""/>
			        </div>
			        <div class="col-lg-4 col-sm-4">
			        <label class="control-label" for="inputError"><h4>Email</h4></label>
			        <input type="text" class="form-control" name="email" value=""/>
			        </div>
			        <div class="col-lg-4 col-sm-4">
			        <label class="control-label" for="inputError"><h4>Telefono</h4></label>
			        <input type="text" class="form-control" name="telefono" value=""/>
			        </div>
			    </div>';

			    //<!-- segunda fase -->
$return.=		'<div class="col-lg-12 col-sm-12">
			    	<div class="col-lg-4 col-sm-4">';
$return.= 			$Form->form_check_idiomas($__idiomas); 
$return.=	    	'</div>';
$return.=			'<div class="col-lg-4 col-sm-4">
			            <label class="control-label" for="inputError"><h4>Periodicidad de subscripcion</h4></label>
			            <select class="form-control" name="periodicidad">
			                <option value="1">cada dia</option>
			                <option value="2">cada 2 dias</option>
			                <option value="7">cada semana</option>
			                <option value="14">cada 2 semanas</option></select>
			    	</div>';
$return.=			'<div class="col-lg-4 col-sm-4">
			            <label class="control-label" for="inputError"><h4>Periodicidad de subscripcion</h4></label>
			            <select class="form-control" name="maximo_informes">
			                <option value="0">sin limite</option>
			                <option value="5">5</option>
			                <option value="10">10</option>
			                <option value="20">20</option></select>
			    	</div>';
$return.=		'</div>';

			    //<!-- tercera fase -->
$return.=		'<div id="crear-anuncio-datos" class="col-lg-6 col-sm-6">';
$return.=		 	$Form->modulo_provincia_municipio(); 
$return.=		 	$Form->modulo_tipo_subtipo();
$return.=			'<div class="form-group">';
$return.=			    $Form->select_tipo_venta($__tipo_venta, null);
$return.=			'</div>';
$return.=			$Form->input_precio_superficie_minimo();  
$return.=		'</div>';
			    //<!-- fin de las preguntas -->
$return.=		$Form->privatepol();
$return.=	'</div>';



$return.=	'</div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input type="submit" class="btn btn-primary" value="Enviar" />
			  </div>
			</div>
			</form>';
$return.='</div></div>';			
		  
return $return;

}














?>