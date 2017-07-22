<?php
require 'inc/vars.php';

$Config = new perfil_config();
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<ul class="nav nav-tabs pull-left" id="tabs">
			<li class="active"><a href="#links_validos" data-toggle="tab">Links validos</a></li>
			<li><a href="#nu_link" data-toggle="tab">Nuevo link footer</a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<!--nuevas alertas-->
			<div class="tab-pane active" id="links_validos">    
				<?php echo $Config->links_table();	?>
			</div>

			<div class="tab-pane" id="nu_link">
			<form id="link_foot_form" method="post">
<?php
		echo '<input type="hidden" name="ip_enc" value="'. md5($_SERVER['REMOTE_ADDR']).'"/>
			  <input type="hidden" name="aleatorio" value="'. $_SESSION['invisible']['token_key'].'"/>
			  <input type="hidden" name="mda_control" value="nu_link"/>	';
?>
				
				<table class="table table-bordered">
					<tr>
						<th>titulo</th><th>seccion</th><th>primer parametro</th>
						<th>segundo parametro</th><th>idioma</th><th>opcion</th>
					</tr>
					<tr>
					<td></td>
					<td></td>
<?php	
					//titulo del link
					echo '<td class="linkeros"><select rel="one" name="one_que">';
					echo '<option></option>';
					foreach ($__links_posibles as $key => $value) {
						echo '<option value="'.$key.'">'.$value.'</option>';
					}
					echo '</select></td>';

					echo '<td class="linkeros"><select rel="two" name="two_que">';
					echo '<option></option>';
					foreach ($__links_posibles as $key => $value) {
						echo '<option value="'.$key.'">'.$value.'</option>';
					}
					echo '</select></td>';
?>				
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td><input type="text" name="link_titulo" /></td>
<?php
						//seccion a que pertenece el link
						echo '<td><select name="section">';
						echo '<option></option>';
						foreach ($__links_categorias as $value) {	echo '<option>'.$value.'</option>';	}
						echo '</select></td>';
?>				
						<td><select id="one_response" name="one_cual"></select></td>
						<td><select id="two_response" name="two_cual"></select></td>
						
<?php
						echo '<td><select name="idioma">';
						foreach ($__idiomas as $key => $value) {	
							echo '<option value="'.$key.'">'.$value.'</option>';	
						}
						echo '</select></td>';
?>

						<td><input class="btn btn-success" type="submit" value="Crear link footer" /></td>
					</tr>
				</table>
			
			</form>
			</div>
		</div>
	</div>
</div>