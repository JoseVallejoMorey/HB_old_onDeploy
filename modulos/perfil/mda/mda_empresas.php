<?php

//objetivo aqui saldra una tabla con las empresas que tienen un perfil valido no activo,
//tabla con perfil no valido//tabla con perfil valido
$Master = new show_master();
?>

<div class="panel panel-default">
  <div class="panel-heading">
    <ul class="nav nav-tabs pull-left" id="tabs">
      <li class="active"><a href="#mda-empresas-validadas" data-toggle="tab">Empresas Validadas</a></li>
      <li><a href="#mda-no-validadas" data-toggle="tab">Empresas no validadas</a></li>
      <li><a href="#mda-no-aptas" data-toggle="tab">No aptas</a></li>
    </ul>
  </div>
  <div class="panel-body">
    <div class="tab-content">

      <div class="tab-pane active" id="mda-empresas-validadas">
      <?php echo $Master->mda_empresas(1);  ?>
      </div>    
      <div class="tab-pane" id="mda-no-validadas">
      <?php echo $Master->mda_empresas(2);  ?>
      </div>
      <div class="tab-pane" id="mda-no-aptas">
      <?php echo $Master->mda_empresas(3);  ?>
      </div>

    </div>
  </div>
</div>