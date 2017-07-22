<?php
//objetos
$Pedidos = new Pedidos();
?>
<form id="traddorbann" class="" method="post" enctype="multipart/form-data">

<div class="col-md-12">

  <div class="panel panel-default">
    <div class="panel-heading">
      <ul class="nav nav-tabs pull-left" id="tradd_nav">
        <li class="active"><a href="#traduccion_pendiente" data-toggle="tab">Traducciones Pendientes </a></li>
        <li><a href="#traduccion_realizada" data-toggle="tab">Traducciones Realizadas</a></li>
        <li><a href="#pedidos_banners" data-toggle="tab">Pedidos Banners</a></li>
      </ul>
    </div>

    
  <?php
  $inv = $_SESSION['invisible']['token_key'];
  echo '<input type="hidden" name="ip_enc" value="'.md5($_SERVER['REMOTE_ADDR']).'"/>';
  echo '<input type="hidden" name="aleatorio" value="'.$inv.'"/>';
  ?>


    <div class="panel-body">
      <div class="tab-content">
        <div class="tab-pane active" id="traduccion_pendiente">
            <?php  echo $Pedidos->show_pedidos_traduccion();  ?>
        </div>
        <div class="tab-pane" id="traduccion_realizada">
            <?php  echo $Pedidos->show_traducidos();  ?>
        </div>
        <div class="tab-pane" id="pedidos_banners">
            <?php  echo $Pedidos->show_pedidos_banners(); ?>
        </div>
      </div>
    </div>

  </div> 
</div>

<!-- Respuesta de ajax -->
<div class="col-md-8">

  <div class="panel panel-default">
    <div class="panel-heading">
      <ul class="nav nav-tabs pull-left" id="tabs">
          Respuesta
      </ul>
    </div>
    <div class="panel-body">
      <div class="tab-content">
        <div id="tradd_response"></div>
      </div>
    </div>
  </div>

</div>








 

</form>