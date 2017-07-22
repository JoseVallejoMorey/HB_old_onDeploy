<?php  
//utilizado para modificar info de empresa
include '../ajax_friend.php';

if( (requested() == true) && (tok_y_token() == true) ){


  includes_perfil_info(); //traigo includes
  $Perfil = new seg_builder();  
  $user   = $Perfil->id_from_salt($_POST['saltador']);


  if($_POST['indicador'] == 'agente'){$tabla = 'empresa_agentes';}
  if($_POST['indicador'] == 'oficina'){$tabla = 'empresa_oficinas';}


  //pasa todos a 0 y el electo a 1
  if($_POST['accion'] == 'central'){
      $Perfil->where('empresa',$user);
      $campos = array('sede_central' => 0);
      $Perfil->update($tabla,$campos);

      $Perfil->where('id',$_POST['cual']);
      $campos = array('sede_central' => 1);
      $Perfil->update($tabla,$campos);
  }else{

    //criterios comunes
    $Perfil->where('empresa',$user);
    $Perfil->where('id',$_POST['cual']);

    if($_POST['accion'] == 'eliminar'){
      //elimina de tabla
      $Perfil->delete($tabla);
    }else if($_POST['accion'] == 'ocultar'){
      //marca como oculto
      $campos = array('activo' => 0);
      $Perfil->update($tabla,$campos);
    }else if($_POST['accion'] == 'mostrar'){
      //marca como visible
      $campos = array('activo' => 1);
      $Perfil->update($tabla,$campos);
    } 



  }



  


}else{
  echo'no token';
}

?>