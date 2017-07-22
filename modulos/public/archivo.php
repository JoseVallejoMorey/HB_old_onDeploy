<?php
$Con = new modulos();
?>

<div id="public_content" class="container">
	<div class="main">	
		<div class="row" style="margin:0">
			<div id="archivo" class="col-sm-12">
<?php
if(!empty($_GET['archivo'])){
	if($_GET['archivo'] == 'legal'){
		//aviso legal
		include_once'modulos/archivo/legal.php';
	
	}else if($_GET['archivo'] == 'condiciones'){
		//condiciones de servicio
		include_once'modulos/archivo/condiciones.php';
	
	}else{
		echo 'Error 404';
		echo $Con->no_results(NULL);
	}
}	
?>

			</div>
		</div>
	</div>
</div>
