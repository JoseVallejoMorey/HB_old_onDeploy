<header>






<div class="container">

<div id="xs-menu" class=" navbar col-xs-12 visible-xs">
<span class="col-xs-1"><a href="#" id="sidebar-menu"><i class="fa fa-search"></i></a></span>
<span class="col-xs-10"><h2>HabitaMallorca</h2></span>
<span class="col-xs-1"><a href="#" id="sidebar-menu"><i class="fa fa-bars"></i></a></span>

</div>


    <div class="navbar navbar-default hidden-xs" role="navigation">
        <div class="navbar-header  visible-md visible-lg">

          <a class="btn btn-navbar btn-default navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="nb_left pull-left"> 
                <span class="fa fa-reorder"></span>
              </span> <span class="nb_right pull-right">menu</span> 
          </a>
        </div>
         <div class="collapse navbar-collapse">
              <ul id="main_nav" class="nav pull-right navbar-nav">

<?php
echo '<li class="" rel="1"><a href="index.php">inicio</a></li>';
echo '<li class="" rel="2"><a href="index.php?busqueda=1&operacion=venta">'.MNVENTA.'</a></li>';
echo '<li class="" rel="3"><a href="index.php?busqueda=1&operacion=alquiler">'.MNALQUILER.'</a></li>';
echo '<li class="" rel="4"><a href="index.php?busqueda=1&tipo_inmueble=2">'.MNCOMERCIALES.'</a></li>';
echo '<li class="" rel="5"><a href="index.php?inmv_lista=">'.MNEMPRESAS.'</a></li>';
?>


<!--    <li class="dropdown"> <a aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle" href="#">Pages<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                             <li><a href="register.html">Register or Sign in</a></li>
                             <li><a href="elements.html">Design Elements</a></li>
                             <li><a href="documentation/index.html">Documentation <span class="label label-danger">new</span></a></li>
                        </ul>
                   </li>
              </ul> -->
         </div><span id="transmark"></span>
    </div>
    
      <!-- <i class="fa fa-user"></i> -->

      <ul id="sign" class="nav navbar-nav navbar-right hidden-xs">


      
        <li class="dropdown visible-md visible-lg">
            <a href="index.html#" class="dropdown-toggle" data-toggle="dropdown"><img src="assets/ico/flags/Spain.png" style="height:18px; margin-top:-4px;"></a>
            <ul class="dropdown-menu">
            
            <li><a href="index.php?lg=ger"><img src="assets/ico/flags/Spain.png" style="height:18px; margin-top:-2px;"> Español</a></li>
            <li><a href="index.php?lg=ger"><img src="assets/ico/flags/Germany.png" style="height:18px; margin-top:-2px;"> German</a></li>
            <li><a href="index.php?lg=ger"><img src="assets/ico/flags/England.png" style="height:18px; margin-top:-2px;"> English</a></li>  
            </ul>
          </li>

<?php
if(!empty($_SESSION['user_id'])){      
  echo'<li class="estable">
      <a href="index.php?destroy=1" class="btn btn-danger">desconectar</a>
    </li>';
  echo'<li class="estable">
      <a href="index.php?perfil=1" class="btn btn-success">Perfil</a>
    </li>';
}else{
  echo'<li class="estable"><a href="index.php?accion=log" class="btn btn-default">Acceder</a></li>';  
  echo'<li class="estable"><a href="index.php?accion=reg" class="btn btn-default">Registrarse</a></li>';         
}

?>

      </ul>

</div>




</header>