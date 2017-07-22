<?php

//clase que contiene todo lo de la pagina(imagenes, caracteristicas)
//al construir el objeto anuncio ya deberia resolver el enigma y sino devolver 404
//ademas de obtener demas datos

include_once'inc/clases/public/modulos.class.php';

class Pagina_anuncio extends Modulos{

	public $correcto;


	public function __construct(){

		parent::__construct();

		if($id = $this->resolver_enigma($_GET['pag'])){
			$this->correcto = true;
			$Perfil  = new seg_sesion();
			$this->iniciar($id,$Perfil);
		}else{
			$this->correcto = false;
		}	

	}

	public function iniciar($id,$Perfil){
		//definire usuario propietario del anuncio
		$this->where('id',$id);
		$this->salida = $this->getOne('anuncios');
		$this->user = $this->salida['ussr'];
		//me entero de quien es ese usuario
		$Perfil->where('id',$this->user);
		$tip = $Perfil->getOne('usuarios','tipo_usuario');
		//defino tabla y carpeta segun tipo de usuario
		$this->salida['tipo_tabla'] = $this->define_tabla($tip['tipo_usuario']);
		$this->salida['tipo_carpeta'] = $this->define_carpeta($tip['tipo_usuario']);
		//y segun el idioma
		$this->salida['subtipo_inmueble'] = $this->show_subtipo_inmueble($this->salida['subtipo_inmueble']);
		$this->salida['municipio'] = $this->show_municipio($this->salida['municipio']);
	}

	//definir tabla segun tipo usuario
	//===================================
	public function define_tabla($supertipo){
		if ($supertipo == 'particular'){
			$tb = 'perfiles_par';
		}else{
			$tb = 'perfiles_emp';
			}
		return $tb;
	}	

	//definir carpeta de imagenes segun tipo usuario
	//===================================
	public function define_carpeta($supertipo){
		if ($supertipo == 'particular'){
			$carpeta = 'par';
		}else{
			$carpeta = $this->user;
		}
		return $carpeta;
	}



	//titulo de pagina del anuncio
	//=====================================================
	// public function header_anuncio(){	
		
	// 	$return = '<div id="pag-header" class="col-lg-12 col-sm-12 col-12">';

	// 	    $return .= '<h1>'.$this->salida['subtipo_inmueble'].' en 
	// 	    				'.$this->salida['tipo_venta'].' en 
	// 	         			'.$this->salida['municipio'].'</h1>';
					                                  
 //            $return .= '<div id="pag-bar" class="anuncio-top">';
                              
 //                $return .='<span class="left"><h3>'.$this->salida['precio'].' €</h3></span>
 //                     <span class="ico">
 //                        <p>'.$this->salida['habitaciones'].' <img src="assets/icons/rooms.jpg"></p>
 //                     </span>
 //                     <span class="ico">
 //                        <p> '.$this->salida['banos'].' <img src="assets/icons/shower.png"></p>
 //                     </span>
 //                     <span class="right"><h4>'.$this->salida['superficie'].' m2</h4></span>';
                
 //        	$return .= '</div>
 //        			</div>';

 //    	return $return; 
	// }

	public function features_anuncio(){
		if($this->salida['tipo_venta'] == 'venta'){
			$venta = 'En venta';
		}else{
			$venta = 'En alquiler';
		}

		$return = '<div class="property-features-single">
              <span class="status">'.$venta.'</span>
              <span><i class="fa fa-hdd-o"></i> '.$this->salida['habitaciones'].' Habitaciones</span>
              <span><i class="fa fa-male"></i> '.$this->salida['banos'].' Baños</span>
              <span><i class="fa fa-home"></i> '.$this->salida['superficie'].' m<sup>2</sup></span>
              <span class="propiety-price"> '.$this->salida['precio'].'</span>
            </div>';
        return $return;    
	}




	// public function nueva_galeria(){

	// 	$md5id = md5($this->salida['id']);
	// 	$this->reset();
	// 	$this->where('id_e',$md5id);
	// 	$imagenes = $this->get('anuncios_img');
	// 	$ruta = 'imagenes/anuncios_img/'.$this->salida['tipo_carpeta'];

	// 	$return  ='<div class="slider carousel slide" data-ride="carousel">';
	// 	$return .='<ol class="carousel-indicators">';
	// 	//miniaturas de imagenes
	// 	$i=1;
	// 	foreach ($imagenes as $value) {
	// 		$return .= '<li data-target=".slider" data-slide-to="'.$i.'" class="">';
	// 		$return .= '<img src="'.$ruta."/small/small_".$value['img'].'" alt=""></li>';
	// 		$i++;
	// 	}
	// 	$return .='</ol>';
        
 //        //imagenes grandes
 //        $return .='<div class="carousel-inner">';      
	// 	foreach ($imagenes as $value) {
	// 		$return .='<div class="item">'; 
	// 		$return .='<img src="'.$ruta.'/'.$value['img'].'" alt="">';
	// 		$return .='</div>'; 
	// 	}
	// 	$return .='</div>'; 

	// 	$return .='<a class="left carousel-control" href=".slider" data-slide="prev">'; 
	// 	$return .='<span class="glyphicon glyphicon-chevron-left"></span></a>'; 
	// 	$return .='<a class="right carousel-control" href=".slider" data-slide="next">'; 
	// 	$return .='<span class="glyphicon glyphicon-chevron-right"></span></a>'; 
	// 	$return .='</div>'; 

 //        return $return;

	// }

	public function nueva_galeria(){

		$md5id = md5($this->salida['id']);
		$this->reset();
		$this->where('id_e',$md5id);
		$imagenes = $this->get('anuncios_img');
		$ruta = 'imagenes/anuncios_img/'.$this->salida['tipo_carpeta'];


		$return ='<div id="slider1_container" class="slider">';
		//<!-- Loading Screen -->        
		$return .='<div u="loading" style="position: absolute; top: 0px; left: 0px;">
		            <div class="slider1"></div>
		            <div class="slider2"></div>
		          </div>';

		//<!-- Slides Container -->        
		$return .='<div u="slides" class="gallery">';
		foreach($imagenes as $value){
			$return .='<div>
				        <img u="image" src="'.$ruta.'/'.$value['img'].'" />
				        <img u="thumb" src="'.$ruta."/small/small_".$value['img'].'" />
				       </div>';
		}

		$return .='</div>';

		//<!-- Arrow Left -->        
		$return .='<span u="arrowleft" class="jssora05l flechito" style="left: 8px;">
		        </span>';
		//<!-- Arrow Right -->
		$return .='<span u="arrowright" class="jssora05r flechito" style="right: 8px;">
		        </span>';

		        
		//<!-- Thumbnail Navigator Skin Begin -->        
		$return .='<div u="thumbnavigator" class="jssort01 navigator">
		            <div u="slides" style="cursor: move;">
		                <div u="prototype" class="p prototype">
		                    <div class=w>
		                    	<div u="thumbnailtemplate" class="thumb"></div>
		                    </div>
		                    <div class=c>
		                    </div>
		                </div>
		            </div>
		            <!-- Thumbnail Item Skin End -->
		        </div>';
		//<!-- Thumbnail Navigator Skin End -->  
		//$return .='<a style="display: none" href="http://www.jssor.com">Bootstrap Crousel</a>';
		$return .='</div>';

		return $return;

	}


	// //opciones que mostramos en anuncio
	// public function mostrar_opciones_anuncio(){
	// 	$return = '<ul id="options" class="hidden-sm">
	// 		          <li><a href="">Inmprimir</a></li>
	// 		          <li><a href="">Guardar</a></li>
	// 		          <li><a href="">Enviar a amigo</a></li>
	// 		          <li><a href="">recibir alertas</a></li>
	// 		          <li><a href="">Solicitar Informacion</a></li>
	// 		          <li><a href=""></a></li>
	// 		     </ul>';
	// 	return $return;
	// }

	//opciones que mostramos en anuncio
	// public function anuncio_datos_empresa(){

	// 	if($this->salida['tipo_tabla'] == 'perfiles_emp'){
	// 		$this->Perfil->where('id',$this->user);
	// 		$contacto = $this->Perfil->getOne($this->salida['tipo_tabla']);
	// 		$return = '<a href="index.php?inmv='.$contacto['nik_empresa'].'">
	// 					<img class="" src="imagenes/logo/'.$contacto['img'].'">
	// 				  </a>';
 //            $return .= '<div class="col-lg-12 col-sm-12 col-12 empresa-cont">
	//                        <h5>'.$contacto['empresa'].'</h5>
	//                        <p>'.$contacto['direccion'].'</p>
	//                        <a href="index.php?inmv='.$contacto['nik_empresa'].'">Visitar anunciante</a>
	//                   </div>';
	//         return $return;
	// 	}
	// }	


	//barra central de pagina anuncio
	//===================================================

	// public function anuncio_central_info_opciones(){

	// 	$return = $this->mostrar_opciones_anuncio();
	// 	$return .= '<div id="empresa">';

	// 		$return .= $this->anuncio_datos_empresa();
	// 		$return .= '<div class="col-lg-12 col-sm-12 col-12 empresa-foot">';

	// 			//boton y modal enviar email a anunciante		   
	// 			$return .= $this->btn_contacto();   
	// 			//boton y modal de crear alertas		  
	// 			$return .= $this->btn_crear_alerta('normal');

	//     	$return .='</div>';         
	// 	$return .= '</div>';

	// 	return $return;
	// }


	//mostrando caracteristicas del anuncio
	//=================================================
	public function anuncio_caracteristicas(){
		//obtengo los extras del anuncio
		$extras = explode(',',$this->salida['extras']);

		$return  = '<h2 class="">Caracteristicas</h2>';
		$return .=   '<div class="">';
		$return .=  '<table class="table table-striped table-condensed sumario-table">
		               	<tr><td>Precio</td>
			                <td>'.$this->salida['precio'].' €</td></tr>    
			            <tr><td>Superficie</td>
			                <td>'.$this->salida['superficie'].'</td></tr> 
			            <tr><td>Habitaciones</td>
			                <td>'.$this->salida['habitaciones'].'</td></tr> 
			            <tr><td>Baños</td>
			                <td>'.$this->salida['banos'].'</td></tr>
		             </table>';
		$return .=  '<table class="table table-striped table-condensed sumario-table">
		               	<tr><td>Precio</td>
			                <td>'.$this->salida['precio'].' €</td></tr>    
			            <tr><td>Superficie</td>
			                <td>'.$this->salida['superficie'].'</td></tr> 
			            <tr><td>Habitaciones</td>
			                <td>'.$this->salida['habitaciones'].'</td></tr> 
			            <tr><td>Baños</td>
			                <td>'.$this->salida['banos'].'</td></tr>
		             </table>';		             
		$return .=   '</div>';	
		$return .=   '<div class="">';
		//mostrando todos los extras
		$return .=  	'<div class="extras-container">';		             		
		$return .=  	$this->extraigamos_extras($extras);
		$return .=  	'</div>';		
		$return .=   '</div>';
		return $return;            
	}


	//por cada idioma que exista habra una pestañita y saldran todos los datos en ella
	//=======================================================
	public function anuncio_caracteristicas_cada_idioma($__idiomas){

		//busco anuncio en tabla idiomas
		$this->where('anuncio',$this->salida['id']);
		$langs = $this->get('anuncios_idiomas');

		//primero creamos las pestañas correspondientes a cada anuncio
		$return = '<ul class="nav nav-tabs">';
		foreach($langs as $value){
		    if(isset($__idiomas[$value['idioma']])){
		   		if($_SESSION['lg']==$value['idioma']){	$act = 'active';
		    	}else{									$act = '';		}
		        $return .= '<li class="'.$act.'"><a href="#'.$value['idioma'].'" data-toggle="tab">'.$__idiomas[$value['idioma']].'</a></li>';
		    }
		}
        $return .= '</ul>';

        //contenido de cada pestaña(cada idioma)
        $return .= '<div class="tab-content">';
		foreach($langs as $value){
			if($_SESSION['lg']==$value['idioma']){	$act = 'active';
		    }else{									$act = '';		}
			$return .= '<div class="tab-pane '.$act.'" id="'.$value['idioma'].'"><p>'.$value['descripcion'].'</p></div>';
		}

        $return .= '</div>';


        return $return;

	}




	//panel de caracteristicas del anuncio, contiene todo 
	//(descripcion en vadios idiomas, mapas y vendedor)
	//====================================================
	public function anuncio_main_panel($__idiomas){

		//saco informacion del anuncio
		//$fecha = $this->fecha_db($this->salida['fecha_publicacion']);

		//pestannas de las principales opciones (descripcion, mapas, vendedor)
		$return = '<div>';
		$return .= '<ul class="nav nav-tabs">
				      <li class="active"><a href="#pag-info" data-toggle="tab">Informacion</a></li>
				      <li><a href="#pag-loc" data-toggle="tab">Localizacion</a></li>
				      <li><a href="#pag-anu" data-toggle="tab">Anunciante</a></li>

				   </ul>';

    	$return .= '<div class="">';
    	//caracteristicas y descripcion en verios idiomas
    	$return .= 		'<div class="tab-pane active" id="pag-info">';
	    $return .= 			$this->anuncio_caracteristicas_cada_idioma($__idiomas);
    	$return .= 			$this->anuncio_caracteristicas();
		$return .= 		'</div>';
      	//mapa direccion si estan esos datos
    	$return .=		'<div class="tab-pane" id="pag-loc">Mapa</div>';
    	//Datos del anunciante
    	$return .=		'<div class="tab-pane" id="pag-anu">Anunciante</div>';

    	$return .= '</div>';
    	$return .= '</div>';


    	return $return;

	}








	public function new_anuncio_main_panel($__idiomas){

		//saco informacion del anuncio
		//$fecha = $this->fecha_db($this->salida['fecha_publicacion']);

		//pestannas de las principales opciones (descripcion, mapas, vendedor)
		$return = '<div id="anuncio-features">';
		$return .= 			$this->anuncio_caracteristicas();
    	$return .= '<h2>Idiomas del anuncio</h2>';
    	//caracteristicas y descripcion en verios idiomas
    	$return .= 	'<div class="tab-pane active" id="pag-info">';
	    $return .= 		$this->anuncio_caracteristicas_cada_idioma($__idiomas);
		$return .= 	'</div>';
    	$return .= '</div>';
      	//mapa direccion si estan esos datos    	
    	$return .= $this->show_map_anuncio();

    	return $return;

	}


	public function show_map_anuncio(){
		$return  = '<h2>Ubicacion</h2>';		
		$return .= '<div class="col-sm-12 anuncio-map">';
		$return .= '';
		$return .= '</div>';
		return $return;
	}



}//fin de class Pagina()



?>