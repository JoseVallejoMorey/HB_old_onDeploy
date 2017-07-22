<!-- start: JavaScript-->

<!--[if !IE]>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
<![endif]-->

<!--[if IE]>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
	<script src="assets/js/admin/jquery-migrate-1.2.1.min.js"></script>
<![endif]-->


<!-- fijos -->
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/sha512.js"></script>
<script src="assets/js/js_funciones/funciones_js.js"></script>


<!-- perfil theme scripts -->
<script src="assets/plugins/editable/js/bootstrap-editable.min.js"></script>
<script src="assets/plugins/pace/pace.min.js"></script>
<script src="assets/js/admin/jquery.mmenu.min.js"></script>
<script src="assets/js/admin/core_admin.js"></script>


<!-- propios de perfil -->
<script src="assets/js/admin/cliente.js"></script>
<script src="assets/js/admin/acciones.js"></script>


<?php
//exclusivos para algunas secciones de perfil de usuario
if(!empty($_GET['perfil'])){
	if($_GET['perfil'] == 14){
		echo '<script src="assets/js/admin/perfil_promo.js"></script>';
	}
		if($_GET['perfil'] == 16){
		echo '<script src="assets/js/admin/perfil_tradd.js"></script>';
	}
	if( ($_GET['perfil'] == 2) || ($_GET['perfil'] == 3) || ($_GET['perfil'] == 4) ){

		//ajax del form, wizard, validaciones (anuncios)
		echo '<script src="assets/js/con_ajax.js"></script>';
		echo '<script src="assets/plugins/wizard/jquery.bootstrap.wizard.js"></script>';		
		echo '<script src="assets/js/admin/perfil_anuncio.js"></script>';
		
	}
}

//necesarios si es accion
if(!empty($_GET['accion'])){
	echo '<script src="assets/plugins/backstretch/jquery.backstretch.min.js"></script>';
	echo '<script src="assets/js/admin/accion_plugins.js"></script>';
}

//master
if (!empty($_GET['perfil_mda'])){
	echo '<script src="assets/js/admin/mda.js"></script>';	
}

?>