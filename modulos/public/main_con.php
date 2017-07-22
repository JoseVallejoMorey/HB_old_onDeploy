<?php 

//var_dump('estoy en main_con');
include_once 'inc/clases/form_busqueda_builder.class.php';	//exclusiva para formularios ($Form)
include 'inc/clases/public/salida_anuncios.class.php';


//objetos
$Config = new show_config();
$Con    = new salida_anuncios();
$Form   = new busqueda_builder();	


?>
<div  id="public_content" class="container">
	<div id="main-con" class="">
			<div class="row">

				<div id="form-top">
					<?php echo $Form->buscador_intercambiable(); ?>
				</div>	
			    <div id="consulta-ajax" class="col-md-9">
					<!-- <div class="row container-property"> -->

<?php

//var_dump($Config->seccion['id']);

// if($Config->seccion['id'] == 'generico'){
// 	//es index mostraremos una consulta especial
// 	echo $Con->show_index_results();
// }else{

	
// }
	echo $Con->paginacion_anuncios();	
//echo $Con->mostrando_resultados('mod3');

//special area	
echo $Con->showSpecial();

?>

					<!-- </div> -->
				</div>
				<div class="col-md-3 bann3" style="padding-right:0">
<?php 
echo $Con->show_banner('lateral'); //baner lateral
?>		
				</div>
			</div>

	</div>
</div>		