<div class="col-sm-6 col-sm-offset-3">
<div class="panel panel-default">
    <div class="panel-heading">
        Datos de Empresa
    </div>
    <div class="panel-body">
        <div class="tab-content">
<?php

$saltador = $Perfil->select_salt();
$saltador = 'data-modder="'.$saltador.'"';

if($salida = $Con->eligiendo_usuario2('perfiles_emp')){
?>

    <div class="col-lg-5 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-12 cute-box">

    <button id="perfil_empresa" rel="mod_perfil" type="button" class="btn btn-primary" 
            <?php echo $saltador; ?> data-toggle="button" aria-pressed="false" autocomplete="off">
            Modificar
    </button>

    <form id="datos_empresa" class="datos_perfil" method="post">
      <input type="hidden" name="ip_enc" value="<?php   echo md5($_SERVER['REMOTE_ADDR'])?>"/>
      <input type="hidden" name="aleatorio" value="<?php  echo $_SESSION['invisible']['token_key'] ?>"/>
      <input type="hidden" name="form_to" value="perfil_datos_empresa"/>
        
        <div id="perfil-response">

            <table class="table">
                <tr><td>Empresa</td>
                    <td><?php echo $salida['empresa']; ?></td>
                </tr>
                <tr><td>Direccion</td>
                    <td><?php echo $salida['direccion']; ?></td>
                </tr>
                <tr><td>Telefono de empresa</td>
                    <td><?php echo $salida['empresa_telefono']; ?></td>
                </tr>
                <tr><td>Telefono de empresa 2</td>
                    <td><?php echo $salida['empresa_telefono2']; ?></td>
                </tr>
                <tr><td>Telefono de empresa 3</td>
                    <td><?php echo $salida['empresa_telefono3']; ?></td>
                </tr>
                <tr><td>Movil de empresa</td>
                    <td><?php echo $salida['empresa_movil']; ?></td>
                </tr>
                <tr><td>Movil de empresa 2</td>
                    <td><?php echo $salida['empresa_movil2']; ?></td>
                </tr>
                <tr><td>Movil de empresa 3</td>
                    <td><?php echo $salida['empresa_movil3']; ?></td>
                </tr>
                <tr><td>Email de empresa</td>
                    <td><?php echo $salida['empresa_email']; ?></td>
                </tr>
                <tr><td>Email de empresa 2</td>
                    <td><?php echo $salida['empresa_email2']; ?></td>
                </tr>
                <tr><td>Email de empresa 3</td>
                    <td><?php echo $salida['empresa_email3']; ?></td>
                </tr>

                <tr><td>Pagina web</td>
                    <td><?php echo $salida['web']; ?></td>
                </tr>
                <tr><td>Descripcion</td>
                    <td><?php echo $salida['descripcion']; ?></td>
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