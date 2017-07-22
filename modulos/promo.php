<?php
//pagina de promo , algo serio por primera vez
include_once 'inc/clases/form_busqueda_builder.class.php';  //exclusiva para formularios ($Form)
include 'inc/clases/public/salida_anuncios.class.php';
require_once 'inc/clases/public/empresa.class.php';

//objetos



//require_once 'inc/clases/public/landing_promo.class.php';   //funciones de pagina anuncio(Modulo)

$Config = new show_config();
//$Anuncios = new landing_promo();//objeto de modulos (anuncios)
$Empresa = new Empresa();
$Con    = new salida_anuncios();
$Form   = new busqueda_builder(); 

//$Con = new Modulos();

//formulario de contacto
//tipo mas informacion
//siguenos en facebook

?>


<div id="promo_content" class="container-fluid">  


  <div id="promo_form" class="col-sm-12">
    <div class="container">
      <div id="promo_form_container" class="col-sm-10 col-sm-offset-1">

        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#promo_rapida" aria-controls="promo_rapida" role="tab" data-toggle="tab">Busqueda rapida</a></li>
          <li role="presentation"><a href="#promo_avanzada" aria-controls="promo_avanzada" role="tab" data-toggle="tab">Busqueda Avanzada</a></li>
          <li role="presentation"><a href="#promo_por_empresa" aria-controls="promo_por_empresa" role="tab" data-toggle="tab">Busqueda por empresa</a></li>
        </ul>

        <div class="tab-content" id="form-top">
          <div role="tabpanel" class="tab-pane active" id="promo_rapida">
            <?php  echo $Form->form_promo_simple(); ?>
          </div>
          <div role="tabpanel" class="tab-pane" id="promo_avanzada">
            <?php  echo $Form->form_promo_avanzada(); ?>
          </div>
          <div role="tabpanel" class="tab-pane" id="promo_por_empresa">
            <?php  echo $Form->form_promo_empresas(); ?>
          </div>
        </div>

      </div>     
    </div>
  </div>



  <div class="promo-tag"></div>





  <div id="map-canvas" class="col-sm-12"></div>

  <div class="col-sm-12 promo_central_banner bann1">
    <div class="container">
      <div class="col-sm-8 col-sm-offset-2">
      <?php echo $Con->show_banner('superior'); ?>
      </div>
    </div>
  </div>

  <div id="promo_anuncios" class="col-sm-12">
    <div class="container">
      <?php  echo $Con->show_index_results(); ?>
    </div>  
  </div>

  <div class="col-sm-12 promo_central_banner bann1">
    <div class="container">
      <div class="col-sm-8 col-sm-offset-2">
      <?php echo $Con->show_banner('superior'); ?>
      </div>
    </div>
  </div>


  <div id="promo_empresas" class="col-sm-12">
    <div class="container">

          <?php  echo $Empresa->show_promo_logos_slider() ?>
        
    </div>  
  </div>
<!--   <div class="col-sm-12 promo_central_banner"></div> -->









   

  
  </div>    <!-- container -->

      <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?sensor=false">
    </script>
    <script src="assets/js/maps/promo_map.js"></script>

