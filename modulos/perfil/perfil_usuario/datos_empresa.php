<?php

$saltador = $Perfil->select_salt();
$datos    = $Con->get_datos_for_empresa();

?>


<div id="perfil-empresa" class="row">


    <div class="col-sm-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2><i class="fa fa-user"></i>Perfil de Empresa</h2>

            </div>
            <div class="panel-body">

                <img src="<?php echo $datos['img']; ?>" />

            <form id="logo_empresa" method="post" enctype="multipart/form-data" class="">
            <input type="hidden" name="ip_enc" value="<?php   echo md5($_SERVER['REMOTE_ADDR'])?>"/>
            <input type="hidden" name="aleatorio" value="<?php  echo $_SESSION['invisible']['token_key'] ?>"/>
            <input type="hidden" name="form_to" value="logo_empresa"/>
                <div class="img-assist">
                    <label class="control-label" for="inputError">
                    <h4>Cambiar imagen</h4></label>
                    <input class="form-control" id="imagenes" type="file" name="img" />
                </div>
            <input type="submit" class="btn btn-success" value="Guardar" />    
            </form>

            </div>
        </div>
    </div>




    <div class="col-sm-8">
    <div class="panel panel-success">
        <div class="panel-heading">
            Datos de empresa
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="col-sm-8" style="margin-left:-15px;">
                <table class="table table-striped table-condensed">
                <tr>
                    <td>Descripcion corta</td>
                    <td>
                        <a class="perfil-empresa-response" data-pk="<?php echo $saltador; ?>" 
                            rel="descripcion"><?php echo $datos['descripcion'] ?></a>
                    </td>
                </tr>
                <tr>
                    <td>Descripcion detallada</td>
                    <td>
                        <a class="perfil-empresa-response" data-pk="<?php echo $saltador; ?>" 
                            rel="descripcion_larga" data-type="textarea"><?php echo $datos['descripcion_larga'] ?></a>
                    </td>
                </tr>
                <tr>
                    <td>Pagina Web</td>
                    <td>
                        <a class="perfil-empresa-response" data-pk="<?php echo $saltador; ?>" 
                            rel="web"><?php echo $datos['web'] ?></a>
                    </td>
                </tr>        
                </table>
                </div>
            </div>
        </div>
    </div>
    </div>




    <div class="col-sm-8">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Imagen de fondo
        </div>
        <div class="panel-body">
            <div class="tab-content">

                <img src="<?php echo $datos['img_fondo']; ?>" />

            <form id="empresa_fondo" method="post" enctype="multipart/form-data" class="">
            <input type="hidden" name="ip_enc" value="<?php   echo md5($_SERVER['REMOTE_ADDR'])?>"/>
            <input type="hidden" name="aleatorio" value="<?php  echo $_SESSION['invisible']['token_key'] ?>"/>
            <input type="hidden" name="form_to" value="empresa_fondo"/>
                <div class="img-assist">
                    <label class="control-label" for="inputError">
                    <h4>Elegir Fondo</h4></label>
                    <input class="form-control" id="imagenes" type="file" name="img_fondo" />
                </div>
            <input type="submit" class="btn btn-success" value="Guardar" />     
            </form>

            </div>
        </div>
    </div>
    </div>


</div>