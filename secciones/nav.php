<!-- start: Header -->
<div class="navbar" role="navigation">
	<div class="navbar-header">
		<a class="navbar-brand" href="index.php"><span>PisosMallorca</span></a>
	</div>




	<ul class="nav navbar-nav navbar-actions navbar-left">




		<li class="visible-md visible-lg"><a href="#" id="main-menu-toggle"><i class="fa fa-bars"></i></a></li>
		<li class="visible-xs visible-sm"><a href="#" id="sidebar-menu"><i class="fa fa-bars"></i></a></li>
	</ul>
		<form id="busqueda_detallada" class="navbar-form navbar-left" method="post" action="index.php?pagg=1">
			<i class="fa fa-search"></i>
<?php
	echo   '<input type="hidden" name="ip_enc" value="'. md5($_SERVER['REMOTE_ADDR']).'"/>';		
	echo   '<input type="hidden" name="aleatorio" value="'. $_SESSION['invisible']['token_key'].'"/>';		 
?>					
			<input type="text" class="form-control" name="busqueda" placeholder="Buscar..." />
		</form>


    <ul class="nav navbar-nav navbar-right visible-md visible-lg">

		<li class="dropdown visible-md visible-lg">
    		<a href="index.html#" class="dropdown-toggle" data-toggle="dropdown"><img src="assets/ico/flags/Spain.png" style="height:18px; margin-top:-4px;"></a>
    		<ul class="dropdown-menu">
				
				<li><a href="index.php?lg=esp"><img src="assets/ico/flags/Spain.png" style="height:18px; margin-top:-2px;"> Español</a></li>
				<li><a href="index.php?lg=ger"><img src="assets/ico/flags/Germany.png" style="height:18px; margin-top:-2px;"> German</a></li>
				<li><a href="index.php?lg=eng"><img src="assets/ico/flags/England.png" style="height:18px; margin-top:-2px;"> English</a></li>	
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
<!-- end: Header -->



