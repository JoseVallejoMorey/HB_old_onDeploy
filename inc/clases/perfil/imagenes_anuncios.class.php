<?php

require_once'inc/clases/perfil/usuarios.class.php';

class imagenes_anuncios extends usuarios{

	public $folder;
	public $maxium;

	public function __construct(){
		
		parent::__construct();	
		$this->folder = $this->folder_name();
		$this->maxium = $this->max_por_user();
		
	}	

	//2-IMAGENES DE ANUNCIOS //metodos de update_img.php
	//=================================================================
	
	//descubre carpetsa correspondiente
	private function folder_name(){
		if($this->tipo_usuario == 'particular'){
			$user_folder = 'par';
		}else{
			$user_folder = $this->user;
		}
		return $user_folder;
	}

	//segun tipo de usuario distinta cantidad de imagenes
	private function max_por_user(){
		if($this->tipo_usuario == 'particular'){
			$max = 5;
		}else{
			$max = 10;
		}
		return $max;
	}

	//mostrar imagen prefereida al usuario
	public function mostrar_preferida($imagenes){
		$return = '<div class="panel panel-default">';
		$return .= '<div class="panel-heading">';
		$return .= '<h3 class="panel-title">Imagen principal del anuncio</h3>';
		$return .= '</div>';
		$return .= '<div class="panel-body">';
		$return .= '<div class="tab-content">';

		foreach($imagenes as $value){		
			if($value['principal'] == 1){
				$return .= '<img src="imagenes/anuncios_img/'.$this->folder.'/small/small_'.$value['img'].'" />';
				$return .= '<h3>'.$value['titulo'].'</h3>';
				$return .= '<h3>'.$value['descripcion'].'</h3>';
				break;				
			}else{
				$return .= '<h3>Seleccione una imagen como principal</h3>';
			}
		}

		$return .= '</div>';
		$return .= '</div>';
		$return .= '</div>';

		return $return;
	}








	// //mostrar todas las imagenes al usuario
	// public function old_mostrar_todas_imagenes($imagenes){
	// 	//contabilizar imagenes para mostrar eliminar
	// 	$cuantos = count($imagenes);
		
	// 	$return = '<table class="table ">';
	// 	$return .='<tr>
	// 				<th>Estado</th><th>Imagen</th><th>MP</th><th>Titulo</th>
	// 				<th>Descripcion</th><th>Opcion</th>
	// 			   </tr>';
	// 	foreach($imagenes as $value){
	// 		$codigo_magico = hash('sha512', $value['id_e'].$this->user.$value['id']);

	// 		//para mostrar "hacer principal"
	// 		if($value['principal'] == 1){
	// 			$principal = '<a class="btn btn-success" role="button" >Principal</a>';
	// 		}else{
	// 			$link_pri = 'index.php?perfil=3&art='.$value['id_e'].'&prince='.$codigo_magico;
	// 			$principal = '<a class="btn btn-warning" role="button" href="'.$link_pri.'">Hacer principal</a>';
	// 		}

	// 		//para mostrar opcion de eliminar si se puede
	// 		if($cuantos <= 3){
	// 			$link_del = '';
	// 			$eliminar = '';
	// 		}else{
				
	// 			$link_del = 'index.php?perfil=3&art='.$value['id_e'].'&delimg='.$codigo_magico;
	// 			$eliminar = '<li class="deleteador" role="presentation">
	// 							<a role="menuitem" tabindex="-1" link="'.$link_del.'" >Eliminar</a>
	// 						</li>';
	// 		}
			
	// 		//menu desplegable con epciones de modificar o eliminar (ajax)
	// 		$botonera ='<div class="dropdown">
	// 			            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" 
	// 			            		data-toggle="dropdown">Opciones<span class="caret"></span>
	// 			            </button>';
	// 		$botonera .=   '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
	// 			                <li role="presentation">
	// 								<a class="img_option" role="menuitem" tabindex="-1" 
	// 								   data-ref="'.$value['id'].'">Modificar</a>
	// 							</li>
	// 			                <li role="presentation" class="divider"></li>
	// 			                '.$eliminar.'
	// 						</ul>';
	// 		$botonera .='</div>';

	// 		//filas con cada imagen e informacion
	// 		$return .='<tr class="anuncio_pro" data-ref="'.$value['id'].'" rel="'.$value['id_e'].'">
	// 					<td>'.$principal.'</td>
	// 					<td><img src="imagenes/anuncios_img/'.$this->folder.'/small/small_'.$value['img'].'" /></td>
	// 					<td class="marcador_pro"></td>

	// 					<td id="title-response'.$value['id'].'">'.$value['titulo'].'</td>
	// 					<td id="descr-response'.$value['id'].'">'.$value['descripcion'].'</td>
	// 					<td id="optionator">'.$botonera.'</td>

	// 				   </tr>';
	// 	}
	// 	$return .='</table>';

	// 	return $return;
	// }




	//mostrar todas las imagenes al usuario
	public function mostrar_todas_imagenes($imagenes){
		//contabilizar imagenes para mostrar eliminar
		$cuantos = count($imagenes);
		$return ='<div class="row">';

		foreach($imagenes as $value){
			$codigo_magico = hash('sha512', $value['id_e'].$this->user.$value['id']);

			//para mostrar "hacer principal"
			if($value['principal'] == 1){
				$principal = '';
			}else{
				$link_pri = 'index.php?perfil=3&art='.$value['id_e'].'&prince='.$codigo_magico;
				$principal  = '<li class="" role="presentation">';
				$principal .= '<a role="button" href="'.$link_pri.'">Hacer principal</a>';
				$principal .= '</li>';

			}

			//para mostrar opcion de eliminar si se puede
			if($cuantos <= 3){
				$link_del = '';
				$eliminar = '';
			}else{
				$link_del = 'index.php?perfil=3&art='.$value['id_e'].'&delimg='.$codigo_magico;
				$eliminar = '<li class="deleteador" role="presentation">
								<a role="menuitem" tabindex="-1" link="'.$link_del.'" >Eliminar</a>
							</li>';
			}
			
			//menu desplegable con epciones de modificar o eliminar (ajax)
			$botonera ='<div class="dropdown">
				            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" 
				            		data-toggle="dropdown">Opciones<span class="caret"></span>
				            </button>';
			$botonera .=   '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
								'.$principal.'
				                <li role="presentation" class="divider"></li>
				                '.$eliminar.'
							</ul>';
			$botonera .='</div>';



			//filas con cada imagen e informacion
			$return .= '<div style="margin-bottom:30px" class="col-sm-3 col-xs-6">';
			$return .= '<img class="img-thumbnail" src="imagenes/anuncios_img/'.$this->folder.'/small/small_'.$value['img'].'" />';
			$return .= '<div class="gallery-cont">';
			$return .= '<h3><a class="title-response" data-pk="'.$value['id'].'">'.$value['titulo'].'</a></h3>';
			$return .= '<h3><a class="descr-response" data-pk="'.$value['id'].'">'.$value['descripcion'].'</a></h3>';
			$return .= $botonera;
			$return .= '</div>';
			$return .= '</div>';


			// $return .='<tr class="anuncio_pro" data-ref="'.$value['id'].'" rel="'.$value['id_e'].'">
						
			// 			<td><img /></td>
			// 			<td class="marcador_pro"></td>

			// 			<td></td>
			// 			<td></td>
			// 			<td id="optionator">'.$botonera.'</td>

			// 		   </tr>';
		}
		// $return .='</table>';
				
			
			
			
			
			

	$return .='</div>';

		return $return;
	}

						

						
















	//maximo de imagenes que pueden subirse
	public function subidor_imagenes_anuncio($id_de_anuncio){
		$return = '<div id="img-updater" class="col-md-12">';

		if($this->count < $this->maxium){
			$return .='<form id="publicar_2" method="post" enctype="multipart/form-data">    
					  <input type="hidden" name="ip_enc" value="'.md5($_SERVER['REMOTE_ADDR']).'"/>
					  <input type="hidden" name="aleatorio" value="'. $_SESSION['invisible']['token_key'] .'"/>
					  <input type="hidden" name="form_to" value="lista_fotos"/>
					  <input type="hidden" name="id_e" value="'. $id_de_anuncio .'"/>';
			if($this->count < 1){
				//si es la primera la definimos favorita por defecto
				$sreturn .= '<input type="hidden" name="principal" value="1"/>';
			}				
			$return .= '<input id="imagenes" type="file" name="imagen[]" multiple="multiple"/>';
			$return .= '<input id="imgenvio" class="btn btn-success" type="submit" value="Subir"/>';
			$return .= '</form>';
					
		}else{
			$return .= 'solo puedes subir '.$this->maxium.' fotos';
		}
		$return .= '</div>';
		return $return;
	}





//mostrara wizard para subidor de imagenes
public function idiomas_wizard_creator($id_anuncio,$imagenes){


//abriendo divs
$return  = '<div class="panel panel-default">';
$return .= '<div class="panel-heading"></div>';
$return .= '<div class="panel-body">';

//pestañas del wizard
$return .= '<ul class="steps">';
$return .= '<li class="disabled"><a><span class="badge badge-info">1</span> Ubicacion</a></li>';
$return .= '<li class="disabled"><a><span class="badge badge-info">2</span> Propiedad</a></li>';
$return .= '<li class="disabled"><a><span class="badge badge-info">3</span> Detalles</a></li>';
$return .= '<li class="disabled"><a><span class="badge badge-info">4</span> Descripcion</a></li>';
$return .= '<li class="pasos active"><a href="#tab5" data-toggle="tab"><span class="badge badge-info">5</span> Imagenes</a></li>';
$return .= '</ul>';

//barra de progreso
$return .= '<div class="progress thin">';
$return .= '<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">';
$return .= '</div>';
$return .= '</div>';



//abre contenido
$return .= '<div class="tab-content">';

//pestaña del wizard 5
$return .= '<div class="tab-pane active" id="tab5">';
$return .= '<div class="row">';


//form que permite subir imagenes

$return .= $this->subidor_imagenes_anuncio($id_anuncio);

$return .= '<div id="response-update-img" class="col-md-12">';
//abro form, contendra ajax
$return .= '<form id="img_data" method="post">';
$return .= '<input type="hidden" name="ip_enc" value="'.md5($_SERVER['REMOTE_ADDR']).'"/>';
$return .= '<input type="hidden" name="aleatorio" value="'.$_SESSION['invisible']['token_key'].'"/>';
$return .= '<input type="hidden" name="form_to" value="lista_fotos"/>';

//mostramos todas las imagenes del anuncio
if(!empty($imagenes)){	
$return .= $this->mostrar_todas_imagenes($imagenes);
}
$return .= '</form>';
$return .= '</div>';

$return .= '</div>';  //row
$return .= '</div>';

//cierre contenido
$return .= '</div>';

//acciones
$return .= '<div class="actions">';
//$return .= '<input type="button" class="btn btn-success button-next" value="Siguiente" />';
$return .= '<a class="btn btn-primary" href="'.ANUNCIO_PASO_3.'&art='.$id_anuncio.'">Finalizar</a>';
$return .= '<a class="btn btn-warning" role="button" href="index.php?pag='.$id_anuncio.'">Visitar anuncio</a>';
//$return .= '<input type="submit" class="btn btn-primary button-finish" value="Guardar" style="display:none"/>';	
//$return .= '<input type="button" class="btn btn-default button-previous" value="Anterior" />';	
$return .= '</div>';


//cierres	
$return .= '</div>';
$return .= '</div>';	

		
//hasta aqui todo lo que va dentro del form
return $return;    		
				

}





}

?>