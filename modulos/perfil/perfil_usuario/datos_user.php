<?php

$saltador = $Perfil->select_salt();
$datos    = $Con->get_datos_for_user();
?>

<div id="perfil-empresa" class="row">
             
  <div class="col-sm-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h2><i class="fa fa-user"></i>Perfil de Usuario</h2>
      </div>
      <div class="panel-body">
        <table class="table">
          <tr><td>Telefono de usuario</td>
              <td>
                <a class="perfil-usuario-response" data-pk="<?php echo $saltador; ?>" 
                  rel="user_telefono"><?php echo $datos['user_telefono'] ?></a>
              </td>
          </tr>
          <tr><td>Email de usuario</td>
              <td>
                <a class="perfil-usuario-response" data-pk="<?php echo $saltador; ?>" 
                  rel="user_email"><?php echo $datos['user_email'] ?></a>
              </td>
          </tr>
        </table>

      </div>
    </div>
  </div>

</div>