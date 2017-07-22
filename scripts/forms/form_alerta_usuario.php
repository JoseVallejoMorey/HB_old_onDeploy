<?php

//formulario del usuario para crear especializaciones

include 'inc/vars.php';

$Form = new form_builder();

	echo '<form id="new_alert" method="post">';	
	echo 	'<input type="hidden" name="ip_enc" 	value="'. md5($_SERVER['REMOTE_ADDR']).'"/>';
	echo 	'<input type="hidden" name="aleatorio" 	value="'. $_SESSION['invisible']['token_key'].'"/>';
	echo 	'<input type="hidden" name="alerta"  	value="alert_user" />';
    echo    '<input type="hidden" name="lang_form"  value="esp"/> ';

?>
<div class="row">
	<div class="col-lg-12 col-sm-12">
        <p>Puedes crear una alerta con tus intereses, recibiras novedades y ofertas por email</p>
    </div>
    <hr />
    
    <div class="col-lg-12 col-sm-12">
        <div class="col-lg-8 col-sm-8">
            <?php echo $Form->form_check_idiomas($__idiomas); ?>
        </div>
        <div class="col-lg-4 col-sm-4">
        </div>
    </div>
    <hr />
    	
    <div id="crear-anuncio-datos" class=" col-lg-4 col-sm-4">
        <?php 
          echo $Form->modulo_provincia_municipio();  
          echo $Form->modulo_tipo_subtipo();
        ?>
        <div class="form-group">
             <?php echo $Form->select_tipo_venta($__tipo_venta, null); ?>
        </div>                      
    </div><!-- crear-anuncio-datos-->
                                
    <div class="col-lg-8 col-sm-8">
        <?php echo $Form->input_precio_superficie_minimo(); ?>                                 
    </div>
      
	<div id="extras" class="col-lg-8 col-sm-8"></div>      

    <div class="foot-btn">
      	<input class="btn btn-default" type="submit" value="Continuar" />
    </div>
      
      				
				
</div>				
</form>