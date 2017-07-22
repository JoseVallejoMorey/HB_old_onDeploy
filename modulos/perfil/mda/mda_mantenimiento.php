<?php 
$Master = new show_master();
//array con ultima revision (fecha y dias)
$datos = $Master->ultima_reserva();
?>

<div class="panel panel-default">
  <div class="panel-heading">
    <ul class="nav nav-tabs pull-left" id="tabs">
      <li class="active"><a href="#mant-reservas" data-toggle="tab">Mantenimiento de reservas</a></li>
      <li><a href="#mant-paquetes-caducados" data-toggle="tab">Mantenimiento de paquetes</a></li>
    </ul>
  </div>
  <div class="panel-body">
    <div class="tab-content">
      <div class="tab-pane active" id="mant-reservas">    
          <table class="table table striped">
            <tr><th></th><th>Ultima fecha</th><th>Ultima revision</th><th>Accion</th></tr>
            <tr><td>Reservas y Promos</td>
              <td><?php echo $datos['ultima_reserva']; ?></td>
              <td><?php echo $datos['cuando']; ?></td>
              <td><a class="btn btn-info" 
                  href="index.php?perfil_mda=8&mantenimiento=reservas">Iniciar Mantenimiento</a></td>
            </tr>
            <tr><td>Raquetes caducados</td>
              <td></td>
              <td></td>
              <td><a class="btn btn-info" 
                  href="index.php?perfil_mda=8&mantenimiento=paquetes">Revisar caducados</a></td>
            </tr>
          </table>
      </div>
      <!--alertas vistas-->
      <div class="tab-pane" id="mant-paquetes-caducados">
        <?php echo $Master->paquetes_caducados(); ?>    
      </div>
    </div>
  </div>
</div>