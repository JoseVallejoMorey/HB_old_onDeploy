<?php
//contiene variables generales de tipos de vivienda... y demas
include 'inc/vars.php';

$Form = new form_builder();

echo '<div class="modal fade" id="modal-alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">';
		echo '<form id="new_alert" method="post" class="family_alert">';	
		echo '<input type="hidden" name="ip_enc" 	value="'.md5($_SERVER['REMOTE_ADDR']).'"/>';
		echo '<input type="hidden" name="aleatorio" value="'.$_SESSION['invisible']['token_key'].'"/>';
        echo '<input type="hidden" name="lang_form" value="'.$_SESSION['lg'].'"/>';
		echo '<input type="hidden" name="alerta"  	value="new_alert" />';
				
	
		 echo'<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Crear una nueva alerta</h4>
				  </div>
				  <div class="modal-body">';
			  //body del modal
	
?> 		

<div class="row">
	<div class="col-lg-12 col-sm-12">
        <p>Puedes crear una alerta con tus intereses, recibiras novedades y ofertas por email</p>
        
    </div>
    <hr />
    <div class="col-lg-12 col-sm-12">
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
        <div class="col-lg-8 col-sm-8">
            <?php echo $Form->form_check_idiomas($__idiomas); ?>
        </div>
        <div class="col-lg-4 col-sm-4">
        	<label class="control-label" for="inputError"><h4>Horario preferido</h4></label>
        	<select class="form-control" name="horario">
            					   <option></option>
            					   <option>Por la mañana</option>
            					   <option>Por la tarde</option>
            					   <option>Todo el dia</option></select>
        </div>

        <div class="col-lg-4 col-sm-4">
            <label class="control-label" for="inputError"><h4>Maximo de ofertas a recibir</h4></label>
            <select class="form-control" name="max_ofertas">
                                   <option></option>
                                   <option>5</option>
                                   <option>10</option>
                                   <option>sin limite</option></select>
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
				
				







<div id="extras" class="col-lg-8 col-sm-8"></div>
                                
    <div class="col-lg-8 col-sm-8">
        <?php echo $Form->input_precio_superficie_minimo(); ?>  

         <table id="carac_ajax">
         	<tr class="form-group">
                 <td>Minimo de habitaciones</td>
                 <td><select name="min_habitaciones" class="form-control">
                                                 <option></option>
                 <?php for($i=1;$i<8;$i++){echo '<option>'.$i.'</option>';}?>

                     </select>
                 </td>
              </tr>
              <tr class="form-group">
                 <td>Minimo de baños</td>
                 <td><select name="min_banos" class="form-control">
                                                 <option></option>
                 <?php for($i=1;$i<6;$i++){echo '<option>'.$i.'</option>';}?>

                      </select>
                 </td>
              </tr>
         
         
         </table>  
              
                                                                         
      </div>
      
<div class="col-lg-8 col-sm-8">
	<h4>Comentario</h4>
	<textarea style="width:100%;" name="comentario"></textarea>
</div>
      
      
<div class="col-lg-8 col-sm-8">
    <!-- almenos uno de los dos debera ser true , prefijo en telefono si nada es español-->
    <div class="check-friend">
        <input type="checkbox" name="permiso_email" value="true" />
        <p>Si, deseo recibir emails de anunciantes</p>
    </div>
    <div class="check-friend">
        <input type="checkbox" name="permiso_tel" value="true" />
        <p>Si, deseo que me llamen </p>
    </div>

    <?php echo $Form->privatepol(); ?>

</div>      
      
      
      
      				
				
</div>				
</form>

	
<?php				
				
				
				
echo'		  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input class="btn btn-primary" type="submit" value="Enviar" />
			  </div>
			</div>
		  </div>
	</div>';

?>