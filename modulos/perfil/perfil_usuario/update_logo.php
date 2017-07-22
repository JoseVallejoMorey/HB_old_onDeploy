<div class="col-sm-6 col-sm-offset-3">
<div class="panel panel-default">
  <div class="panel-heading">
    Logo de Empresa
  </div>
  <div class="panel-body">
    <div class="tab-content">
      
<?php 
//actualizar logo de la empresa
$saltador = $Perfil->select_salt();
$saltador = 'data-modder="'.$saltador.'"';

if($salida = $Con->eligiendo_usuario2('perfiles_emp')){
?>

<div class="col-lg-5 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-12 cute-box">

<button id="perfil_logo" rel="mod_perfil" type="button" class="btn btn-primary" <?php echo $saltador; ?>
        data-toggle="button" aria-pressed="false" autocomplete="off">
  Modificar
</button>

  <form id="logo_empresa" method="post" enctype="multipart/form-data" class="datos_perfil">
    <input type="hidden" name="ip_enc" value="<?php   echo md5($_SERVER['REMOTE_ADDR'])?>"/>
    <input type="hidden" name="aleatorio" value="<?php  echo $_SESSION['invisible']['token_key'] ?>"/>
    <input type="hidden" name="form_to" value="perfil_datos_empresa"/>

    <div id="perfil-response">
      <img src="imagenes/logo/<?php echo $salida['img']; ?>" />
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