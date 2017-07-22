<?php


class UpImg{

	public $user;						//id de usuario
	public $tipo_usuario;				//particular o empresa
	//para creacion de banners 
	public $empresa;					//id de usuario (uso distinto al de $user)
	public $empresa_name;				//nombre empresa(sino id de usuario)
	public $tipo_banner;				//(superior, central, lateral)
	//Edicion de imagen
	private $img_virgenes = array();	//imagenes subidas virgenes
	private $img_procesadas = array();	//imagenes subidas ya procesadas
	private $directorio;				//carpeta de destino
	private $directorio_small;			//carpeta de destino small_img
	private $altura;					//altura de img
	private $ancho;						//ancho de img
	private $small = false;				//crear o no img_small(false por defecto)
	private $tabla;						//tabla a donde guardar datos
	private $error;						//algun error en el proceso
	private $max_sice = 5000000;		//5mb de peso maximo



	public function __construct(){
		
		if(!empty($_SESSION['user_id'])){	$this->user = $_SESSION['user_id'];			}
		if(!empty($_SESSION['tipo'])){		$this->tipo_usuario = $_SESSION['tipo'];	}
		
	}	

	//comprueba la respuesta de if files y devuelve imagen si es correcta
	//================================================================
	public function check_if_files($tabla){
		$this->tabla = $tabla;
		$fotito = $this->if_files();
		if(!is_numeric($fotito)){
			if(!empty($fotito)) {

				if(count($fotito) == 1){
					foreach ($fotito as $key => $value) {
						$_POST[$key] = $value; //le paso solo el valor
						break;
					}
				}else{
					$_POST['img'] = $fotito;	//le paso el array tal cual	
				}



				// if(count($fotito) == 1){
				// 	$_POST['img'] = $fotito['imagen'];	//le paso solo el valor
				// }else{
				// 	$_POST['img'] = $fotito;	//le paso el array tal cual
				// }
			}
		}else{
			unset($_FILES);
		}
		return $fotito;
	}

	//abre un array con imagenes(o imagen sola)
	//==============================================	
	private function desvirgar_imagenes(){
		foreach($_FILES as $value){
			foreach ($value as $key => $value2) {
				if(is_array($value2)){
					$i=0;
					foreach ($value2 as $value3) {
						$this->img_virgenes[$i++][$key]=$value3;
					}						
				}else{
					$this->img_virgenes = $_FILES;
					break;
	}	}	}	}

	//segun si es empresa o particular los destinos son unos u otros
	//par sera la carpeta donde vayan las img de los anuncios particulares
	//=====================================================================	
	private function distinguir_propietario(){
		if($this->tipo_usuario == 'empresa'){
			$this->directorio = "imagenes/anuncios_img/".$this->user;
			$this->directorio_small = "imagenes/anuncios_img/".$this->user."/small"; 
		}else{							   
			$this->directorio = "imagenes/anuncios_img/par";
			$this->directorio_small = "imagenes/anuncios_img/par/small";
		}		
	}

	//crear directorio si no existe
	//==============================================	
	private function crear_directorio($small = null){
		if (!file_exists($this->directorio)) {
			mkdir($this->directorio, 0700);
			if($small == true){
				mkdir($this->directorio_small, 0700);				
			}
		}
	}

	//obtiene tamann de imagen original
	//==============================================	
	private function respetar_tamanno($value){
		$t = getimagesize($value['tmp_name']);
		$this->altura = $t[0];
		$this->ancho  = $t[1];
	}


	//ya segun tabla define los detalles
	//==============================================
	public function ultima_llamada($key,$value){
		//en estas tablas se respetara el tamaño original
		$full_size = array('banners_catalogo','banners_pedido','empresa_fondo');

		if(in_array($this->tabla, $full_size)){
		//imagen en pedido de banner o banner completo
			$this->respetar_tamanno($value);
			//imagen fondo de empresa
			if($this->tabla == 'empresa_fondo'){$this->directorio = 'imagenes/fondos';
			//nuevo pedido
			}else if($this->tabla == 'banners_pedido'){ $this->directorio = 'imagenes/banners/pedidos';
			//asignando banner terminado
			}else{						   		  $this->directorio = 'imagenes/banners';}

			$this->img_procesadas[$key] = $this->HTMLUploadImage();

		}else if($this->tabla == 'perfiles_emp'){
		//Logo de empresa mas pequeño y sin copia mini
			$this->altura 	  = 290;
			$this->ancho  	  = 220;
			$this->directorio ='imagenes/logo'; 
			$this->img_procesadas[$key] = $this->HTMLUploadImage();
		}else if($this->tabla == 'empresa_agentes'){
		//Imagen de un agente inmobiliario
			$this->altura 	  = 290;
			$this->ancho  	  = 220;
			$this->directorio = "imagenes/agentes"; 
			$this->img_procesadas[$key] = $this->HTMLUploadImage();
		}else if($this->tabla == 'empresa_oficinas'){
		//Imagen de una sucursal de empresa
			$this->altura 	  = 290;
			$this->ancho  	  = 220;
			$this->directorio = "imagenes/oficinas"; 
			$this->img_procesadas[$key] = $this->HTMLUploadImage();

		}else{
			//debe de ser una imagen de anuncio
			$this->altura = 650;
			$this->ancho  = 500;		
			$this->small = true;
			$this->distinguir_propietario();	//destino segun user o particular
			$this->crear_directorio(true);			//crea directorios si no existian
			// //experimento de convertir _FILE en array actual
			$_FILES['imagen'] = $value;
			//imagen de anuncio, mas grande y con copia mini
			$this->img_procesadas[$key] = $this->HTMLUploadImage();
		}
	}


	//Comprobara formato de imagen y si hay errores
	//===============================================================
	public function if_files(){
		//die(var_dump('pasa por aqui porque hay imagen'));
		$fotito = array();
		$permitidos = array('image/jpeg', 'image/gif', 'image/png');
		$finfo = new finfo(FILEINFO_MIME_TYPE); // Devuelve el tipo mime del tipo extensión
		
		//preparado para recibir un array de imagenes
		$this->desvirgar_imagenes();

		//this->img_virgenes es el array que contiene cada dato de la imagen
		foreach($this->img_virgenes as $key => $value){
			if( (!empty($value['tmp_name'])) && ($value['error'] == 0)) {
				$tmp = $finfo->file($value['tmp_name']);
				//se verifica que el formato de imagen sea permitido
				if(in_array($tmp,$permitidos)){
					$this->ultima_llamada($key,$value);
				}else{
					//el formato no es el esperado (puede que ni sea una imagen)
					$this->img_procesadas[$key] = $value['error'];
				}
			}else{
				//esta vacio o hay algun error
				$this->img_procesadas[$key] = $value['error'];
			}
		}

		//return imagen si es correcto o un numero con el error
		return $this->img_procesadas;
	}

	//imagen de salida tendra el mismo formato que la imagen que entro
	//=========================================================
	private function create_img($ext,$imagefile){
		$error1 = "Error abriendo $imagefile!";
		$error2 = "Formato de imágen no soportada";
	    if(strtolower($ext) == "gif") {
	        if(!$imagen = imagecreatefromgif($imagefile)){
	        	$this->error = $error1; 
	        	return false;
	        }
	    }else if(strtolower($ext) == "jpg" || strtolower($ext) == "jpeg") {
	        if(!$imagen = imagecreatefromjpeg($imagefile)){
	        	$this->error = $error1;
	        	return false;
	        }
	    }else if(strtolower($ext) == "png") {
	        if(!$imagen = imagecreatefrompng($imagefile)){
	        	$this->error = $error1; 
	        	return false;
	        }
	        
	    }else{
	        $this->error = $error2; 
	        return false;
	    }
	return $imagen;
	}


	// //adaptare el tamaño de imagen (funcion 2)
	// //==============================================
	public function redimensionar($imagefile, $nueva_anchura, $nueva_altura){

	    /* Obtener extensión del archivo */
	    $dot = (strlen($imagefile) - strrpos($imagefile, ".")-1)*(-1);
	    $ext = substr($imagefile, $dot);
	    $ext = strtolower($ext);    

	    
	 	/* Chequear que las imágenes sean de alguno de los formatos soportados, 
	 	pasamos la extensión a minúsculas */

		if(!$imagen = $this->create_img($ext,$imagefile)){
			echo $this->error;
		    exit;
		}

		unlink($imagefile); // BORRAMOS el archivo original
		
	    $w = imagesx($imagen);
	    $h = imagesy($imagen);
		
		if(($nueva_altura !== 000) && ($nueva_anchura !== 000)){	
			
			if(($w >= $nueva_anchura) || ($h >= $nueva_altura)){	
				if($w > $h) {
				// horizontal
				// como es horizontal se respeta el máximo de anchura pero se lleva 
				// la altura proporcionalmente ahora bien, la altura, aún proporcionalmente
				// puede ser mayor a la $nueva_titleura así que:
				$limite_altura = $nueva_altura;
				$nueva_altura = ($nueva_anchura * $h) / $w ;
					if($nueva_altura > $limite_altura){
					$nueva_anchura = ($limite_altura * $w) / $h ;
					$nueva_altura = ($nueva_anchura * $h) / $w ;
					}
				}else{
				// vertical
				$limite_anchura = $nueva_anchura;
				$nueva_anchura = ($nueva_altura * $w) / $h ;
					if($nueva_anchura > $limite_anchura){
						$nueva_altura = ($limite_anchura * $h) / $w ;
						$nueva_anchura = ($nueva_altura * $w) / $h ;		
					}	
				}
			}
		}else{
			$nueva_anchura = $w;
			$nueva_altura = $h;
		}

	 
	    if(function_exists("imagecreatetruecolor")) {
	        $calidad = imagecreatetruecolor($nueva_anchura, $nueva_altura);
			imagealphablending($calidad, false);
			imagesavealpha($calidad, true); 
		}else{
			$calidad = imagecreate($nueva_anchura, $nueva_altura);
		}


	    imagecopyresampled($calidad, $imagen, 0, 0, 0, 0, $nueva_anchura, $nueva_altura, $w, $h);

		if(strtolower($ext) == "gif") {
			imagegif($calidad, $imagefile, 90);
		}else if(strtolower($ext) == "jpg" || strtolower($ext) == "jpeg") {
			imagejpeg($calidad, $imagefile, 90);
	    }else if(strtolower($ext) == "png") {
	    	imagepng($calidad, $imagefile, 9);// cambiar a 9 en php5
	    }
	    imagedestroy($imagen);
	    return true;
	// Forma de uso:
	// redimencionar(/ruta/archivo.jpg)

	}












	// //adaptara el tamaño de imagen (funcion 1)
	// //==============================================	
	// public function old_HTMLUploadImage(){
		
	// 	$laFoto 	= '';	
	//     $nombreFoto = '';
	//     $aleatorio  = rand(1, 9999);
	// 	//var_dump($_FILES);
	// 	//calcula peso del archivo
	//     if ($_FILES['imagen']['tmp_name'] != '') {

	//     	//tamaño maximo de imagen 5 mb
	//         if ($_FILES['imagen']['size'] > $this->max_sice) {
	//             echo "<script> document.getElementById('state_bar').innerHTML = 
	//             '<center><strong>La imagen es muy grande</strong></center>'; </script>";
	//         }else{
				
	// 			$raiz = $this->directorio."/";

	//             if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
						
	//                 if ($_FILES['imagen']['type'] == "image/jpeg") {
	//                     $laFoto = time() .$aleatorio. '.jpg';
	//                 } elseif ($_FILES['imagen']['type'] == "image/jpg") {
	//                     $laFoto = time() .$aleatorio. '.jpg';
	//                 } elseif ($_FILES['imagen']['type'] == "image/pjpeg") {
	//                     $laFoto = time() .$aleatorio. '.jpg';
	//                 } elseif ($_FILES['imagen']['type'] == "image/gif") {
	//                     $laFoto = time() .$aleatorio. '.gif';
	//                 } elseif ($_FILES['imagen']['type'] == "image/png") {
	//                     $laFoto = time() .$aleatorio. '.png';
	//                 } elseif ($_FILES['imagen']['type'] == "image/x-png") {
	//                     $laFoto = time() .$aleatorio. '.png';
	//                 } else {
	//                     $laFoto = "";
	//                     echo "<script> document.getElementById('state_bar').innerHTML = 
	//                     '<center><strong>La imagen tiene un formato inv&aacute;lido 
	//                     (jpg,pjpeg,png,gif,x-png)</strong></center>'; </script>";
	//                 }

	//                 //si es un pedido añadiremos el user al nombre
	// 				if($this->directorio == 'imagenes/banners/pedidos'){
	// 					$laFoto = $this->user.'_'.$laFoto;
	// 				}
	// 				//si es un banner completo cambiaremos totalmente el nombre a empresa-tipo
	// 				if($this->directorio == 'imagenes/banners'){
	// 					//aqui añadir carpeta dentro de banners "$this->empresa" (156)
	// 					//asi banners de cada empresa se guardaran en su carpeta
	// 					$dir = $this->directorio.'/'.$this->empresa;
	// 					if(!file_exists($dir)){
	// 						mkdir($dir, 0700);
	// 					}
	// 					$raiz = $dir.'/';
	// 					$laFoto = $this->empresa_name.'_'.$this->tipo_banner.'_'.$laFoto;
	// 				}


	//                 $nombreFoto  = $raiz.$laFoto; // Conserva su nombre original, se guarda en una carpeta
	// 				$nombreSmall = $raiz.'small/small_'.$laFoto; //ruta de small img

	//                 if (move_uploaded_file($_FILES['imagen']['tmp_name'], $nombreFoto) or die('no 1')) {
	// 				//respetará el ancho o alto...dependiendo si la foto es horizontal o vertical
	//                     $this->redimensionar($nombreFoto, $this->ancho, $this->altura); 

	//                 }
					
	// 				//creara una imagen en miniatura para una carga mas agil
	// 				if($this->small == true){
	// 					//reducida a un 40%

	// 					$nuevoAncho = number_format($this->ancho*40/100 ,0); 
	// 					$nuevoAlto  = number_format($this->altura*40/100 ,0);
						
	// 					if (!copy($nombreFoto, $nombreSmall)) {
	// 						echo "Error al copiar $archivo...\n";
	// 					}
	// 					$this->redimensionar($nombreSmall, $nuevoAncho, $nuevoAlto);
	// 				}	
	//             }
	//         }
	//     }
	//     //var_dump($laFoto);
	//     //die;
	//     return $laFoto;
	// }









	//adaptara el tamaño de imagen (funcion 1)
	//==============================================	
	public function HTMLUploadImage(){
		
		$laFoto 	= '';	
	    $nombreFoto = '';
	    $aleatorio  = rand(1, 9999);
		//var_dump($_FILES);
		//calcula peso del archivo


		foreach ($_FILES as $key => $value) {
			
		    if ($value['tmp_name'] != '') {

		    	//tamaño maximo de imagen 5 mb
		        if ($value['size'] > $this->max_sice) {
		            echo "<script> document.getElementById('state_bar').innerHTML = 
		            '<center><strong>La imagen es muy grande</strong></center>'; </script>";
		        }else{
					
					$raiz = $this->directorio."/";

		            if (is_uploaded_file($value['tmp_name'])) {
							
		                if ($value['type'] == "image/jpeg") {
		                    $laFoto = time() .$aleatorio. '.jpg';
		                } elseif ($value['type'] == "image/jpg") {
		                    $laFoto = time() .$aleatorio. '.jpg';
		                } elseif ($value['type'] == "image/pjpeg") {
		                    $laFoto = time() .$aleatorio. '.jpg';
		                } elseif ($value['type'] == "image/gif") {
		                    $laFoto = time() .$aleatorio. '.gif';
		                } elseif ($value['type'] == "image/png") {
		                    $laFoto = time() .$aleatorio. '.png';
		                } elseif ($value['type'] == "image/x-png") {
		                    $laFoto = time() .$aleatorio. '.png';
		                } else {
		                    $laFoto = "";
		                    echo "<script> document.getElementById('state_bar').innerHTML = 
		                    '<center><strong>La imagen tiene un formato inv&aacute;lido 
		                    (jpg,pjpeg,png,gif,x-png)</strong></center>'; </script>";
		                }

		                //si es un pedido añadiremos el user al nombre
						if($this->directorio == 'imagenes/banners/pedidos'){
							$laFoto = $this->user.'_'.$laFoto;
						}
						//si es un banner completo cambiaremos totalmente el nombre a empresa-tipo
						if($this->directorio == 'imagenes/banners'){
							//aqui añadir carpeta dentro de banners "$this->empresa" (156)
							//asi banners de cada empresa se guardaran en su carpeta
							$dir = $this->directorio.'/'.$this->empresa;
							if(!file_exists($dir)){
								mkdir($dir, 0700);
							}
							$raiz = $dir.'/';
							$laFoto = $this->empresa_name.'_'.$this->tipo_banner.'_'.$laFoto;
						}


		                $nombreFoto  = $raiz.$laFoto; // Conserva su nombre original, se guarda en una carpeta
						$nombreSmall = $raiz.'small/small_'.$laFoto; //ruta de small img

		                if (move_uploaded_file($value['tmp_name'], $nombreFoto) or die('no 1')) {
						//respetará el ancho o alto...dependiendo si la foto es horizontal o vertical
		                    $this->redimensionar($nombreFoto, $this->ancho, $this->altura); 

		                }
						
						//creara una imagen en miniatura para una carga mas agil
						if($this->small == true){
							//reducida a un 40%

							$nuevoAncho = number_format($this->ancho*40/100 ,0); 
							$nuevoAlto  = number_format($this->altura*40/100 ,0);
							
							if (!copy($nombreFoto, $nombreSmall)) {
								echo "Error al copiar $archivo...\n";
							}
							$this->redimensionar($nombreSmall, $nuevoAncho, $nuevoAlto);
						}	
		            }
		        }
		    }

		}


	    //var_dump($laFoto);
	    //die;
	    return $laFoto;
	}






}

?>