<?php

require_once'inc/clases/perfil/usuarios.class.php';

class catalogo_anuncios extends usuarios{

	//constructor
	public function __construct(){
		parent::__construct();	
		//var_dump($this);
	}	

	//devuenve en que idiomas esta ese anuncio
	//=======================================================
	private function idiomas_del_anuncio($id){
		$return = '';
		$this->where('anuncio',$id);
		if($salida_idiomas = $this->get('anuncios_idiomas', NULL, 'idioma')){
			foreach ($salida_idiomas as $value) {

				$return .= $value['idioma'].' ';
			}
		}
		return $return;
	}

	//numero de imagenes en anuncio, si 0 actualiza anuncio a no apto
	//===============================================
	private function num_imagenes_anuncio($id){
		$anuncio_md5 = md5($id);
		$this->where('id_e',$anuncio_md5);
		if($salida_imagenes = $this->get('anuncios_img')){
			$count = count($salida_imagenes);
			return $count;
		}else{
			
			$campos = array('apto' => '0');
			$this->where('id',$id);
			$this->update('anuncios',$campos);
			return '0';
		}
	}





	//dropdown que nos da las opciones para los anuncios(ver, editar, imagenes, idiomas, eliminar)
	private function dropdown_tabla_anuncios($id_arr){

		$salt = $this->Perfil->select_salt();
		$controlator = '&controlator='.$salt;

		return '<div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                Opciones
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                <li role="presentation">
					<a role="menuitem" tabindex="-1" href="index.php?pag='.$id_arr.'">Ver anuncio</a>
				</li>
				<li role="presentation">
					<a role="menuitem" tabindex="-1" href="index.php?perfil=2&art='.$id_arr.'">Editar anuncio</a>
				</li>
                <li role="presentation">
					<a role="menuitem" tabindex="-1" href="index.php?perfil=3&art='.$id_arr.'">Editar imagenes</a>
				</li>
                <li role="presentation">
					<a role="menuitem" tabindex="-1" href="index.php?perfil=4&art='.$id_arr.'">Idiomas del anuncio</a>
				</li>
				<li role="presentation">
					<a class="desactivador" role="menuitem" tabindex="-1" rel="'.$id_arr.'" 
					   data-wey="'.$salt.'">Activar/Desactivar</a>
				</li>
                <li role="presentation" class="divider"></li>
                <li role="presentation">
					<a role="menuitem" tabindex="-1" href="index.php?perfil=1&art='.$id_arr.$controlator.'">Eliminar anuncio</a>
				</li>
            </ul>
            </div>';

	}

	//dropdown opciones para borradores de anuncio
	//==================================================
	private function dropdown_tabla_borradores($id_arr){
		//podriamos obtener el salt del user y pasarlo por url, 
		//asi al leerlo puedo comprobar que sea el propietario
		$salt = $this->Perfil->select_salt();
		$controlator = '&controlator='.$salt;

		return '<div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                Opciones
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				<li role="presentation">
					<a role="menuitem" tabindex="-1" 
					href="index.php?perfil=2&borr='.$id_arr.'">Editar borrador</a>
				</li>
                <li role="presentation" class="divider"></li>
                <li role="presentation">
					<a role="menuitem" tabindex="-1" 
					href="index.php?perfil=1&borr='.$id_arr.$controlator.'">Eliminar borrador</a>
				</li>
            </ul>
            </div>';
	}

	//mostrando borradores del usuario
	//===================================================
	public function mostrar_tabla_borradores(){
		$return = array( 
			'pestanna_borradores' => '',
			'contenido_borradores' => '');

		if($this->tipo_usuario == 'empresa'){
			$return['pestanna_borradores'] = '<li class=""><a href="#pestanna_borradores" data-toggle="tab">Borradores</a></li>';
		}

		$cols = array('id', 'clave_interna', 'provincia', 'municipio', 'tipo_venta', 'fecha_publicacion');
		$this->where('ussr',$this->user);

		if($salida = $this->get('anuncios_borradores', NULL, $cols)){

			$return['contenido_borradores'] .= '<table class="table table-bordered table-hover">';
			$return['contenido_borradores'] .='<tr>
					<th>clave_interna</th><th>provincia</th>
			        <th>municipio</th><th>tipo_venta</th>
			        <th>fecha_publicacion</th>
			        <th>Opciones</th>
			    </tr>';

			foreach ($salida as $key => $value) {
				$return['contenido_borradores'] .='<tr>
			        <td>'.$value['clave_interna'].'</td>
			        <td>'.$value['provincia'].'</td>
			        <td>'.$value['municipio'].'</td>
			        <td>'.$value['tipo_venta'].'</td>
			        <td>'.$value['fecha_publicacion'].'</td>

			        <td>'.$this->dropdown_tabla_borradores($value['id']).'</td>

			    </tr>';

			}

			$return['contenido_borradores'] .= '</table>';

		}
		return $return;
	}






	//mostrar tabla de anuncios (incluye imagen, idiomas, dropdown) en una tablita muy maja
	public function mostrar_tabla_anuncios(){

		$return = '';
		$mensaje = array('0' => 'Desactivado', '1' => 'Activo');
		$cols = array('id', 'clave_interna', 'provincia', 'municipio', 'tipo_venta', 'anuncio_promocionado',
					  'anuncio_e','paquete','activo','apto');
		$this->where('ussr',$this->user);

		if($salida = $this->get('anuncios', NULL, $cols)){

			$return .= '<table class="table table-bordered table-hover">';

			$return .= '<tr>
					        <th>Imagen</th>
					        <th>Clave interna</th>
					        <th>Paquete</th>
					        <th>Provincia</th>
					        <th>Operacion</th>
					        <th>Nº imagenes</th>
					        <th>Idiomas</th>
					        <th>Contratado</th>
					        <th>Anuncio apto</th>
					        <th>Anuncio activo</th>
					        <th>Opciones</th>
					    </tr>';
			//por cada anuncio varias cosas habra que hacer
			$i=1;		    
			foreach ($salida as $key => $value) {
		
				$num = $i++;
				$id_arr = md5($value['id']);
				$imagen = $this->sacar_foto($id_arr, $this->user);

				$return .='<tr>
					    	<td>'.$imagen.'</td>
					        <td>'.$value['clave_interna'].'</td>
					        <td>'.$value['paquete'].'</td>
					        <td>'.$value['provincia'].'<br />
					        '.$this->show_municipio($value['municipio']).'</td>
					        <td>'.$value['tipo_venta'].'</td>';



				//mostrara numero de imagenes en el anuncio
				$return .='<td>';
				$return .= $this->num_imagenes_anuncio($value['id']);
				$return .='</td>';


				//mostrando idiomas en los que esta el anuncio
				$return .='<td>';
				$return .= $this->idiomas_del_anuncio($value['id']);
				$return .='</td>';

				//estrella o promocionado
				$return .='<td>';
				if(!empty($value['anuncio_promocionado']) && ($value['anuncio_promocionado'] == 1)){
					$return .= 'Anuncio promocionado<br />';	}
				if($value['anuncio_e'] == 1){
					$return .= 'Anuncio estrella';			}	
					
			    $return .= '</td>';
			    $return .= '<td>'.$value['apto'].'</td>';
			    $return .= '<td id="act-'.$id_arr.'">'.$mensaje[$value['activo']].'</td>';
			    //dropdown de opciones
			    $return .= '<td>'.$this->dropdown_tabla_anuncios($id_arr).'</td>';
		
				$return .= '</tr>';

			}//fin del foreach

			$return .= '</table>';


		}else{
			$return .= '<h5 id="sec1">No hay anuncios 1 </h5>';
		}

		return $return;
	}


}



?>