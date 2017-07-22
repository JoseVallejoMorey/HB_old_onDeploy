<?php

require_once 'inc/clases/mda/show_master_users.class.php';
$Master = new show_master_users();	

?>

<div class="panel panel-default">
	<div class="panel-heading">
		<ul class="nav nav-tabs pull-left" id="tabs">
		  <li class="active"><a href="#mda-profile" data-toggle="tab">Usuarios</a></li>
		  <li><a href="#mda-online" data-toggle="tab">Usuarios Online</a></li>
		  <li><a href="#mda-messages" data-toggle="tab">Actividad de Usuarios</a></li>
		  <li><a href="#mda-settings" data-toggle="tab">Conexiones</a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="tab-content">

		  <div class="tab-pane active" id="mda-profile">
			<?php echo $Master->mda_panel_usuarios(1);	?> 
		  </div>    
		  <div class="tab-pane" id="mda-online">    
			<?php echo $Master->mda_panel_usuarios(2);	?> 
		  </div>
		  <div class="tab-pane" id="mda-messages">
			<?php echo $Master->mda_panel_usuarios(3);	?>            
		  </div>
		  <div class="tab-pane" id="mda-settings">
			<?php echo $Master->mda_panel_usuarios(4);	?>
		  </div>

		</div>
	</div>
</div>