<?php  
//utilizado para modificar info de empresa
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){


	includes_perfil_info();	//traigo includes
	$Perfil = new seg_builder();	



	//obtenemos usuario 
	$user = $Perfil->id_from_salt($_POST['saltador']);
	$tabla = 'perfiles_emp';
	//asignamos tabla



	//se obtiene informacion 
	$Perfil->where('id',$user);
	$campos = array('empresa','direccion','empresa_telefono','empresa_telefono2','empresa_telefono3',
		'empresa_email','empresa_email2','empresa_email3','empresa_movil','empresa_movil2',
		'empresa_movil3','web','descripcion');
	if($salida = $Perfil->getOne($tabla,$campos)){


		if($_POST['action'] == 0){
			//pongo una tabla normal
			echo '<table class="table">
		            <tr><td>Empresa</td>
	                    <td>'.$salida['empresa'].'</td>
	                </tr>
	                <tr><td>Direccion</td>
	                    <td>'.$salida['direccion'].'</td>
	                </tr>
	                <tr><td>Telefono de empresa</td>
	                    <td>'.$salida['empresa_telefono'].'</td>
	                </tr>
	                <tr><td>Telefono de empresa 2</td>
	                    <td>'.$salida['empresa_telefono2'].'</td>
	                </tr>
	                <tr><td>Telefono de empresa 3</td>
	                    <td>'.$salida['empresa_telefono3'].'</td>
	                </tr>

	                <tr><td>Movil de empresa</td>
	                    <td>'.$salida['empresa_movil'].'</td>
	                </tr>
	                <tr><td>Movil de empresa 2</td>
	                    <td>'.$salida['empresa_movil2'].'</td>
	                </tr>
	                <tr><td>Movil de empresa 3</td>
	                    <td>'.$salida['empresa_movil3'].'</td>
	                </tr>	


	                <tr><td>Email de empresa</td>
	                    <td>'.$salida['empresa_email'].'</td>
	                </tr>
	                <tr><td>Email de empresa 2</td>
	                    <td>'.$salida['empresa_email2'].'</td>
	                </tr>
	                <tr><td>Email de empresa 3</td>
	                    <td>'.$salida['empresa_email3'].'</td>
	                </tr>	                	                

	                <tr><td>Pagina web</td>
	                    <td>'.$salida['web'].'</td>
	                </tr>
	                <tr><td>Descripcion</td>
	                    <td>'.$salida['descripcion'].'</td>
	                </tr>
	              </table>';

		}else{
			//pongo una tabla con inputs
			echo '<table class="table">

			        <tr><td>nombre empresa</td>
			            <td><input type="text" name="empresa" req="required" 
			            		   value="'.$salida['empresa'].'" /></td>
			        </tr>
			        <tr><td>direccion</td>
			            <td><input type="text" name="direccion" req="required"
			            		   value="'.$salida['direccion'].'" /></td>
			        </tr>
			        <tr><td>Telefono de empresa</td>
			            <td><input type="text" name="empresa_telefono" req="required"
			            		   value="'.$salida['empresa_telefono'].'" class="tel-val"/></td>
			        </tr>
			        <tr><td>Telefono de empresa 2</td>
			            <td><input type="text" name="empresa_telefono2" req="required"
			            		   value="'.$salida['empresa_telefono2'].'" class="tel-val"/></td>
			        </tr>
			        <tr><td>Telefono de empresa 3</td>
			            <td><input type="text" name="empresa_telefono3" req="required"
			            		   value="'.$salida['empresa_telefono3'].'" class="tel-val"/></td>
			        </tr>
			        <tr><td>Movil de empresa</td>
			            <td><input type="text" name="empresa_movil" req="required"
			            		   value="'.$salida['empresa_movil'].'" class="tel-val"/></td>
			        </tr>
			        <tr><td>Movil de empresa</td>
			            <td><input type="text" name="empresa_movil2" req="required"
			            		   value="'.$salida['empresa_movil2'].'" class="tel-val"/></td>
			        </tr>
			        <tr><td>Movil de empresa</td>
			            <td><input type="text" name="empresa_movil3" req="required"
			            		   value="'.$salida['empresa_movil3'].'" class="tel-val"/></td>
			        </tr>			        			        
			        <tr><td>Email de empresa</td>
			            <td><input type="text" name="empresa_email" req="required"
			            		   value="'.$salida['empresa_email'].'" class="email-val"/></td>
			        </tr>
			        <tr><td>Email de empresa</td>
			            <td><input type="text" name="empresa_email2" req="required"
			            		   value="'.$salida['empresa_email2'].'" class="email-val"/></td>
			        </tr>
			        <tr><td>Email de empresa</td>
			            <td><input type="text" name="empresa_email3" req="required"
			            		   value="'.$salida['empresa_email3'].'" class="email-val"/></td>
			        </tr>

			        <tr><td>pagina web</td>
			            <td><input type="text" name="web" value="'.$salida['web'].'" /></td>
			        </tr>
			        <tr><td>Descripcion</td>
			            <td><input type="text" name="descripcion" value="'.$salida['descripcion'].'" /></td>
			        </tr>
			      </table> ';

			echo '<div class="foot-btn">
			        <input class="btn btn-default" type="submit" value="Guardar" />   
			      </div> ';
		}



	}	

}

?>