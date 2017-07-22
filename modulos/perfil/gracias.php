<div class="panel panel-default">
	<div class="panel-heading">

	</div>
	<div class="panel-body">
		
		<div class="tab-content">
			
<?php
			//este sera el retorno de paypal process
			if(!empty($_GET['payprocess'])){
				echo 'El proceso ha terminado con exito. Gracias por su confianza.';
				require 'process.php';	
			}
			//este sera el retorno de paypal cancelado
			if(!empty($_GET['paycancel'])){	
				echo 'El proceso ha sido cancelado';
				require 'process.php';	
			}	
?>
		</div>
	</div>
</div>