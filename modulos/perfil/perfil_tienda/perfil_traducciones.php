<?php 
$Con = new Usuarios();	//objeto Usuarios
?>

<form method="post" id="form-tradd" class="form_buy">
<div class="row">
	<div class="col-md-8">


		<div class="panel panel-default">
			<div class="panel-heading">
				Seleccione anuncios a traducir
			</div>
			<div class="panel-body">
				<div class="tab-content">

		    		<div id="anuncios_trad">
						<?php echo $Con->select_to_translate();	?>
					</div>
			        <div class="form-container">
		            
<?php
	          echo'<input type="hidden" name="ip_enc" value="'.md5($_SERVER['REMOTE_ADDR']).'"/>
	               <input type="hidden" name="aleatorio" value="'. $_SESSION['invisible']['token_key'] .'"/>';
?>	               
	               <input type="hidden" name ="tienda" value ="traduccion" />
	                     <!-- //aqui que muestre factura -->

		            	<div class="foot-btn">
							<input class="btn btn-default" type="submit" value="Promocionar" />
						</div> 
			        </div>
				        
				</div>
			</div>
		</div>

	</div>
	<!-- respuesta de ajax -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				Resultado
			</div>
			<div class="panel-body">
				<div class="tab-content">
					<div id="renew-response" class="bann_response"></div>
				</div>
			</div>
		</div>

	</div>



</div>		
</form>