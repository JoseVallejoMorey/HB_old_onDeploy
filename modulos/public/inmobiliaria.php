<?php

$Form    = new busqueda_builder();
$Config  = new show_config();	//objeto config
$Empresa = $Config->Empresa;	//ya iniciado en config, heredamos
$Modulos = $Config->Modulos;	//ya iniciado den config
//var_dump($Empresa);

echo $Config->page_header();
?>

<div id="public_content" class="container">
	<div class="main">
		<div class="row ">





			<div id="form-top">
				<?php echo $Form->buscador_intercambiable(); ?>
			</div>	



			<div class="col-md-9">


<?php

if( (!empty($_GET['inmv_section'])) && ($_GET['inmv_section']=='agentes') ){
	echo $Empresa->show_agentes_empresa();
}else if( (!empty($_GET['inmv_section'])) && ($_GET['inmv_section']=='oficinas') ){
	echo $Empresa->show_oficinas_empresa();
}else{
	//empresa propiedades
				echo '<div id="inmv-ress">';
				echo $Modulos->paginacion_anuncios();
				echo '</div>';
				echo $Empresa->show_datos_ycontacto($Form);
}




?>




			</div>



			<div class="col-md-3" style="padding-right:0">
				<?php		
				echo $Empresa->empresa_botonera();
				echo $Modulos->anuncios_col(NULL, ''); 
				?>
			</div>
		</div>
	</div>
</div>		