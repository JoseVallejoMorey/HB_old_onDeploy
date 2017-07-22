
<?php
$Config = new show_config();
?>


<!-- start: Main Menu -->
<div class="sidebar">

	<div class="sidebar-collapse">

		<?php echo $Config->menu_usuario_foto();?>

		<div class="sidebar-menu">	
			<?php echo $Config->menu_perfil(); ?>
		</div>					
	</div>
	<div class="sidebar-footer">
		<ul class="sidebar-actions">
			<li class="action">
				<div class="btn-group dropup">
				  	<button type="button" class="dropdown-toggle" data-toggle="dropdown">
				    	<i class="icon-speedometer"></i><span>Infrastructure</span>
				    	<span class="sr-only">Toggle Dropdown</span>
				  	</button>
				  	<ul class="dropdown-menu" role="menu">
						<li class="header">Infrastructure <i class="icon-settings"></i></li>
				    	<li>
							<div class="title">Memory<span>2GB of 8GB</span></div>
							<div class="progress">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%"></div>
							</div>			
						</li>
						<li>
							<div class="title">HDD<span>750GB of 1TB</span></div>
							<div class="progress">
							  	<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
							</div>
						</li>
						<li>
							<div class="title">SSD<span>300GB of 1TB</span></div>
							<div class="progress">
						  		<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%"></div>
							</div>			
						</li>
						<li>
							<div class="title">Bandwidth<span>50TB of 50TB</span></div>
							<div class="progress">
						  		<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
							</div>
						</li>
					</ul>
				</div>
			</li>
			<li class="action">
				<div class="btn-group dropup">
				  	<button type="button" class="dropdown-toggle" data-toggle="dropdown">
				    	<i class="icon-list"></i><span>Menu</span>
				    	<span class="sr-only">Toggle Dropdown</span>
				  	</button>
				  	<ul class="dropdown-menu" role="menu">
				    	<li><a href="index.html#">Action</a></li>
			          	<li><a href="index.html#">Another action</a></li>
			          	<li><a href="index.html#">Something else here</a></li>
			          	<li class="divider"></li>
			          	<li><a href="index.html#">Separated link</a></li>
				  	</ul>
				</div>
			</li>
			<li class="action">
				<div class="btn-group dropup">
				  	<button type="button" class="dropdown-toggle" data-toggle="dropdown">
				    	<i class="icon-users"></i><span>Contacts</span>
				    	<span class="sr-only">Toggle Dropdown</span>
				  	</button>
				  	<ul class="dropdown-menu" role="menu">
				    	<li class="header">Contacts <i class="icon-settings"></i></li>
			          	<li><a href="index.html#"><span class="status status-success"></span> Anton Phunihel</a></li>
			          	<li><a href="index.html#"><span class="status status-success"></span> Alphonse Ivo</a></li>
			          	<li><a href="index.html#"><span class="status status-success"></span> Thancmar Theophanes</a></li>
						<li><a href="index.html#"><span class="status status-warning"></span> Walerian Khwaja</a></li>
						<li><a href="index.html#"><span class="status status-warning"></span> Clemens Janko</a></li>
						<li><a href="index.html#"><span class="status status-warning"></span> Chidubem Gottlob</a></li>
						<li><a href="index.html#"><span class="status status-danger"></span> Hristofor Sergio</a></li>
						<li><a href="index.html#"><span class="status status-danger"></span> Tadhg Griogair</a></li>
						<li><a href="index.html#"><span class="status status-danger"></span> Pollux Beaumont</a></li>
						<li><a href="index.html#"><span class="status status-danger"></span> Adam Alister</a></li>
						<li><a href="index.html#"><span class="status status-danger"></span> Carlito Roffe</a></li>
				  	</ul>
				</div>
			</li>
		</ul>

		<ul class="sidebar-terms">
			<li><a href="index.html#">Terms</a></li>
			<li><a href="index.html#">Privacy</a></li>
			<li><a href="index.html#">Help</a></li>
			<li><a href="index.html#">About</a></li>
		</ul>	

	</div>	
</div>
<!-- end: Main Menu -->