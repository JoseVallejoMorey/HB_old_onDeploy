<!-- start: Header -->
<div class="navbar" role="navigation">
	<div class="navbar-header">
		<a class="navbar-brand" href="index.php?perfil=1"><i class="icon-rocket"></i> <span>Perfil de usuario</span></a>
	</div>
	<ul class="nav navbar-nav navbar-actions navbar-left">
		<li class="visible-md visible-lg"><a href="#" id="main-menu-toggle"><i class="fa fa-bars"></i></a></li>
		<li class="visible-xs visible-sm"><a href="#" id="sidebar-menu"><i class="fa fa-bars"></i></a></li>
	</ul>
	<form class="navbar-form navbar-left">
		<i class="fa fa-search"></i>
		<input type="text" class="form-control" placeholder="Are you looking for something ?">
	</form>
    <ul class="nav navbar-nav navbar-right visible-md visible-lg">

		<li class="estable"><a href="index.php" class="btn btn-default">Ir a pagina</a></li>
		<li class="estable"><a href="index.php?destroy=1" class="btn btn-danger">Desconectar</a></li>
		<li><span class="timer"><i class="icon-clock"></i> <span id="clock"><!-- JavaScript clock will be displayed here, if you want to remove clock delete parent <li> --></span></span></li>
		
		<li class="dropdown visible-md visible-lg">
    		<a href="index.html#" class="dropdown-toggle" data-toggle="dropdown"><img src="assets/ico/flags/USA.png" style="height:18px; margin-top:-4px;"></a>
    		<ul class="dropdown-menu">
				<li><a href="index.html#"><img src="assets/ico/flags/USA.png" style="height:18px; margin-top:-2px;"> US</a></li>
				<li><a href="index.html#"><img src="assets/ico/flags/Spain.png" style="height:18px; margin-top:-2px;"> Spanish</a></li>
				<li><a href="index.html#"><img src="assets/ico/flags/Germany.png" style="height:18px; margin-top:-2px;"> German</a></li>
				<li><a href="index.html#"><img src="assets/ico/flags/Poland.png" style="height:18px; margin-top:-2px;"> Polish</a></li>	
    		</ul>
  		</li>

		<li><a href="index.html#"><i class="icon-speech"></i></a></li>
		<li class="dropdown visible-md visible-lg">
    		<a href="index.html#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-settings"></i><span class="badge">!</span></a>
    		<ul class="dropdown-menu">
				<li class="dropdown-menu-header text-center">
					<strong>Account</strong>
				</li>
				<li><a href="index.html#"><i class="fa fa-bell-o"></i> Updates <span class="label label-info">42</span></a></li>
				<li><a href="index.html#"><i class="fa fa-envelope-o"></i> Messages <span class="label label-success">42</span></a></li>
				<li><a href="index.html#"><i class="fa fa-tasks"></i> Tasks <span class="label label-danger">42</span></a></li>
				<li><a href="index.html#"><i class="fa fa-comments"></i> Comments <span class="label label-warning">42</span></a></li>
				<li class="dropdown-menu-header text-center">
					<strong>Settings</strong>
				</li>
				<li><a href="index.html#"><i class="fa fa-user"></i> Profile</a></li>
				<li><a href="index.html#"><i class="fa fa-wrench"></i> Settings</a></li>
				<li><a href="index.html#"><i class="fa fa-usd"></i> Payments <span class="label label-default">42</span></a></li>
				<li><a href="index.html#"><i class="fa fa-file"></i> Projects <span class="label label-primary">42</span></a></li>
				<li class="divider"></li>
				<li><a href="index.html#"><i class="fa fa-shield"></i> Lock Profile</a></li>
				<li><a href="index.html#"><i class="fa fa-lock"></i> Logout</a></li>	
    		</ul>
  		</li>
  		<?php 

  		if($Config->tipo_usuario != 'mda'){
 	  		echo $Con->show_info_user();  			
  		}


  		?>
	</ul>
</div>
<!-- end: Header -->