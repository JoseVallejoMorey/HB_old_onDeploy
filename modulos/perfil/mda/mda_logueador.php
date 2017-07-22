<div class="panel panel-default">
	<div class="panel-heading">
		Master Login
	</div>
	<div class="panel-body">
		<div class="tab-content">
<?php
//Loguin de master
if($Con->logueador_mda() == true){
	include 'scripts/reg/form_log2.php';	//a loguear
}else{
	//esta logueado comprobacion extra de seccion
	if(!empty($_GET['perfil_mda'])){

		$perfil = $_GET['perfil_mda'];
		foreach($perfil_menu as $value){
			if(!empty($value['id'])){
				if($value['id'] == $perfil){		include($value['ruta']);	}
			}else if (!empty($value['submenu'])){
				foreach($value['submenu'] as $value2){
					if($value2['id'] == $perfil){	include($value2['ruta']);	}
		}	}	}	
	}
}

?>
		</div>
	</div>
</div>

