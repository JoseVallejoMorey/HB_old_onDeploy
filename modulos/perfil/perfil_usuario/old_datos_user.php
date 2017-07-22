<div class="col-sm-6 col-sm-offset-3">
<div class="panel panel-default">
  <div class="panel-heading">
    Datos de Usuario
  </div>
  <div class="panel-body"> 
    <div class="tab-content">

<?php
$saltador = $Perfil->select_salt();
$saltador = 'data-modder="'.$saltador.'"';
$elegido  = $Con->eligiendo_usuario1();
if($salida = $Con->eligiendo_usuario2($elegido['tabla'])){
//si es un particular solo ira a datos de usuario, hay que estraer telefono y particular
?>

<div class="col-lg-5 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-12 cute-box">
<button id="perfil_info" rel="mod_perfil" type="button" class="btn btn-primary" <?php echo $saltador; ?>
        data-toggle="button" aria-pressed="false" autocomplete="off">Modificar</button>

<form id="datos_user" class="datos_perfil" method="post">
  <input type="hidden" name="ip_enc" value="<?php   echo md5($_SERVER['REMOTE_ADDR'])?>"/>
  <input type="hidden" name="aleatorio" value="<?php  echo $_SESSION['invisible']['token_key'] ?>"/>
  <input type="hidden" name="form_to" value="<?php    echo $elegido['form_to']; ?>"/>

    <div id="perfil-response">
      <table class="table">
        <tr><td>Telefono de usuario</td>
            <td><?php echo $salida['user_telefono']; ?></td>
        </tr>
        <tr><td>Email de usuario</td>
            <td><?php echo $salida['user_email']; ?></td>
        </tr>
      </table>
    </div>

</form>
</div>

<?php
}
?>

    </div>
  </div>
</div>
</div> 