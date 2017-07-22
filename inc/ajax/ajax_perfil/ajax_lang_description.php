<?php

include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){	

	include '../../vars.php';		//vars, idiomas admitidos
	include '../../config.php';		//constantes de redireccion
	includes_edit_anuncio();
	//include '/inc/clases/perfil/usuarios.class.php';	//clase

	$titulo = '';				//nulos
	$descripcion = '';			//nulos
	$llamada = 'Guardar';		//por defecto guardar
	$editable = true;			//traduccion editable, por defecto true

	//objetos
	$Con = new usuarios();

	//primero sacar anuncio, que lo tendra que hacer siempre
	if(!empty($_POST['art'])){
		$art =$_POST['art'];
		if(!$anuncio = $Con->resolver_enigma($art)){ 
			$anuncio = false;
		}
		//si hay idioma y anuncio existe
		if( (!empty($_POST['idioma_extra'])) && ($anuncio != false) ){

			$save_mod  = '';

			if($_POST['idioma_extra'] == 'all'){	
				
				echo $Con->cargar_idiomas($anuncio);

			}else if(array_key_exists($_POST['idioma_extra'], $__idiomas)){
				//comprobamos si idioma existe o queremos introducir uno nuevo
				$lang = $_POST['idioma_extra'];
				$Con->where('anuncio',$anuncio);
				$Con->where('idioma',$lang);

				if($idiomas_desc = $Con->getOne('anuncios_idiomas')){	
					//si el idioma sugerido existe comprobaremos si 
					//es editable o no
					$titulo = $idiomas_desc['titulo'];
					$descripcion = $idiomas_desc['descripcion'];					
					$llamada = 'Modificar';
					//si fue comprado no puede traducirse
					if($idiomas_desc['num_pedido'] > 0){
						$editable = false;
						$save_mod = '';

						echo '<div class="panel panel-default">';
						echo '<div class="panel-heading" data-original-title="">';
						echo '<h2><i class="fa fa-list"></i>'.$titulo.'</h2>';
						echo '</div>';
						echo '<div class="panel-body">';
						echo '<p>'.$descripcion.'</p>';
						echo '<p>Las traducciones adquiridas no son editables</p>';
						echo '</div>';
						echo '</div>';
					}
				}

				if($editable == true){
					//formulario de idiomas
					echo '<input type="hidden" name="idioma" value="'.$lang.'"/>';
					echo '<input type="hidden" name="art" value="'.$art.'"/>';
					echo '<div class="col-lg-12 lang-text">';
					echo '<h4>Titulo</h4>';
					echo '<input type="text" class="form-control" name="titulo" 
								value="'.$titulo.'" req="required" />';
					echo '<h4>Descripcion</h4>';
					echo '<textarea class="form-control" name="descripcion" req="required">'
							.$descripcion.'</textarea></div>';

					$save_mod  = '<input class="btn btn-success" type="submit" value="'.
								 $llamada.'" />';						
				}
					  
				$footer = '<div class="lang-foot">'.$save_mod.'</div>';
				echo $footer;
			}
			
		}else{
			echo 'No hay coincidencias.';
		}
	}

}


?>