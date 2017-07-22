<?php
$Alerta = new alertas();
$Master = new show_master();
?>

<div class="panel panel-default">
  <div class="panel-heading">
    <ul class="nav nav-tabs pull-left" id="tabs">
      <li class="active"><a href="#nu_alerts" data-toggle="tab">Nuevas alertas</a></li>
      <li><a href="#old_alerts" data-toggle="tab">Alertas antiguas</a></li>
    </ul>
  </div>
  <div class="panel-body">
    <div class="tab-content">

      <div class="tab-pane active" id="nu_alerts">
      </div>    
      <div class="tab-pane" id="old_alerts">    
        <table class="table table-hover table-bordered">
            <tr>
                <th>nombre</th><th>Municipio</th><th>Email</th><th>telefono</th>
                <th>inmueble</th><th>operacion</th><th>idiomas</th><th>fecha </th>
                <th>Ver </th><th>Pendiente </th><th>Quitar </th>
            </tr>

            <?php echo $Master->info_alertas($Alerta);?>
        </table>

      </div>
    </div>
  </div>
</div>