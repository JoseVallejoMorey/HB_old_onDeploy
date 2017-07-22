<?php
//objetos
$Pedidos = new Pedidos();
?>


<div class="col-md-12">

  <div class="panel panel-default">
    <div class="panel-heading">
      <ul class="nav nav-tabs pull-left" id="tradd_nav">
        <li class="active"><a href="#r_totales" data-toggle="tab">Todas las reservas</a></li>
        <li><a href="#r_star" data-toggle="tab">Star area</a></li>
        <li><a href="#r_special" data-toggle="tab">Special area</a></li>
        <li><a href="#r_banners" data-toggle="tab">Banners</a></li>
        <li><a href="#r_promocionados" data-toggle="tab">Promocionados</a></li>
        <li><a href="#r_traducciones" data-toggle="tab">Traducciones</a></li>
        <li><a href="#r_paquetes" data-toggle="tab">Paquetes</a></li>        
      </ul>
    </div>

    



    <div class="panel-body">
      <div class="tab-content">
        <div class="tab-pane active" id="r_totales">
          <?php echo $Pedidos->todas_reservas(); ?>
        </div>
        <div class="tab-pane" id="r_star">
          <?php echo $Pedidos->mostrar_reservas('star_area'); ?>
        </div>
        <div class="tab-pane" id="r_special">
          <?php echo $Pedidos->mostrar_reservas('special'); ?>
        </div>
        <div class="tab-pane" id="r_banners">
          <?php echo $Pedidos->mostrar_reservas('banner'); ?>
        </div>
        <div class="tab-pane" id="r_promocionados"></div>
        <div class="tab-pane" id="r_traducciones"></div>
        <div class="tab-pane" id="r_paquetes"></div>        
      </div>
    </div>

  </div> 
</div>






