

//pestañas
<div class="panel panel-default">
	<div class="panel-heading">
		<ul class="nav nav-tabs pull-left" id="tabs">
		  	<li class=""><a href="page-profile.html#activity">Activity</a></li>
		  	<li class=""><a href="page-profile.html#week">week</a></li>
		  	<li class="active"><a href="page-profile.html#month">month</a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="tab-content">

		</div>
	</div>
</div>











//wizard

<div class="row">
	
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading"></div>
			<div class="panel-body">
				
				<div id="wizard1" class="wizard-type1">
					<ul class="steps">
					  	<li><a href="form-wizard.html#tab11" data-toggle="tab"><span class="badge badge-info">1</span> First</a></li>
						<li><a href="form-wizard.html#tab12" data-toggle="tab"><span class="badge badge-info">2</span> Second</a></li>
						<li><a href="form-wizard.html#tab13" data-toggle="tab"><span class="badge badge-info">3</span> Third</a></li>
						<li><a href="form-wizard.html#tab14" data-toggle="tab"><span class="badge badge-info">4</span> Forth</a></li>
					</ul>
					<div class="progress thin">
						<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
						</div>
					</div>
					
					<h3>Twitter Bootstrap Wizard Plugin <small>with validation</small></h3>
					<p><small>This twitter bootstrap plugin builds a wizard out of a formatter tabbable structure. It allows to build a wizard functionality using buttons to go through the different wizard steps and using events allows to hook into each step individually.</small></p>
					
					<div class="tab-content">
					    <div class="tab-pane" id="tab11">
							<form class="form-horizontal" role="form">
								<div class="form-group">
				                    <label class="col-md-3 control-label" for="email-w1">Email</label>
				                    <div class="col-md-9">
				                        <input type="email" id="email-w1" name="email-w1" class="form-control" placeholder="Enter Email..">
				                        <span class="help-block">Please enter your email</span>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label class="col-md-3 control-label" for="password-w1">Password</label>
				                    <div class="col-md-9">
				                        <input type="password" id="password-w1" name="password-w1" class="form-control" placeholder="Enter Password..">
				                        <span class="help-block">Please enter your password</span>
				                    </div>
				                </div>
					    	</form>	
					    </div>
					    <div class="tab-pane" id="tab12">
					 		<div class="row">

								<div class="col-sm-12">

									<div class="form-group has-feedback">
								    	<label for="name-w1">Name</label>
								    	<input type="text" class="form-control" id="name-w1" placeholder="Enter your name">
										<span class="fa fa-asterisk form-control-feedback"></span>
								  	</div>

								</div>

							</div><!--/row-->

							<div class="row">

								<div class="col-sm-12">

									<div class="form-group has-feedback">
								    	<label for="ccnumber-w1">Credit Card Number</label>
								    	<input type="text" class="form-control" id="ccnumber-w1" placeholder="0000 0000 0000 0000">
										<span class="fa fa-asterisk form-control-feedback"></span>
								  	</div>

								</div>

							</div><!--/row-->

							<div class="row">

						  		<div class="form-group col-sm-4">
						    		<label for="ccmonth-w1">Month</label>
						    		<select class="form-control" id="ccmonth-w1">
									  	<option>1</option>
									  	<option>2</option>
									  	<option>3</option>
									  	<option>4</option>
									  	<option>5</option>
										<option>6</option>
									  	<option>7</option>
									  	<option>8</option>
									  	<option>9</option>
									  	<option>10</option>
										<option>11</option>
										<option>12</option>
									</select>
						  		</div>

								<div class="form-group col-sm-4">
						    		<label for="ccyear-w1">Year</label>
						    		<select class="form-control" id="ccyear-w1">
									  	<option>2014</option>
									  	<option>2015</option>
									  	<option>2016</option>
									  	<option>2017</option>
									  	<option>2018</option>
										<option>2019</option>
									  	<option>2020</option>
									  	<option>2021</option>
									  	<option>2022</option>
									  	<option>2023</option>
										<option>2024</option>
										<option>2025</option>
									</select>
						  		</div>

								<div class="col-sm-4">

									<div class="form-group has-feedback">
							    		<label for="cvv-w1">CVV/CVC</label>
							    		<input type="text" class="form-control" id="cvv-w1" placeholder="123">
										<span class="fa fa-asterisk form-control-feedback"></span>
							  		</div>

								</div>

							</div><!--/row-->
					    </div>
						<div class="tab-pane" id="tab13">
							<div class="form-group">
						    	<label for="company-w1">Company</label>
						    	<input type="text" class="form-control" id="company-w1" placeholder="Enter your company name">
						  	</div>

							<div class="form-group">
						    	<label for="vat-w1">VAT</label>
						    	<input type="text" class="form-control" id="vat-w1" placeholder="PL1234567890">
						  	</div>

							<div class="form-group">
						    	<label for="street-w1">Street</label>
						    	<input type="text" class="form-control" id="street-w1" placeholder="Enter street name">
						  	</div>

							<div class="row">

						  		<div class="form-group col-sm-8">
							    	<label for="city-w1">City</label>
							    	<input type="text" class="form-control" id="city-w1" placeholder="Enter your city">
							  	</div>

								<div class="form-group col-sm-4">
							    	<label for="postal-code-w1">Postal Code</label>
							    	<input type="text" class="form-control" id="postal-code-w1" placeholder="Postal Code">
							  	</div>

							</div><!--/row-->

							<div class="form-group">
						    	<label for="country-w1">Country</label>
						    	<input type="text" class="form-control" id="country-w1" placeholder="Country name">
						  	</div>
					    </div>
						<div class="tab-pane" id="tab14">
							<h2>Title</h2>
							<p>
								Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
							</p>
							<h2>Title</h2>
							<p>
								Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
							</p>
							<h2>Title</h2>
							<p>
								Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
							</p>
							<h2>Title</h2>
							<p>
								Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
							</p>
							
							<div class="form-group">
								<div class="checkbox">
		                            <label for="checkbox1-w1">
		                            <input type="checkbox" id="checkbox1-w1" name="checkbox1-w1" value="option1"> I agree with <a href="form-wizard.html#">Terms of Service</a>
		                            </label>
		                        </div>
							</div>
					    </div>
			
					</div>
					
					<div class="actions">
					
						<input type="button" class="btn btn-default button-previous" name="prev" value="Prev" />
						<input type="button" class="btn btn-success button-next" name="next" value="Next" />
						<input type="button" class="btn btn-primary button-finish" name="finish" value="Finish" style="display:none"/>
					</div>
						
				</div>

			</div>

		</div>
		
	</div><!--/col-->
	

</div><!--/row-->











					<option value="1027">31 DE DICIEMBRE</option>
						
					<option value="28">AMANECER</option>
						
					<option value="181">ANDREA DORIA</option>
						
					<option value="1065">ARABELLA</option>
						
					<option value="4">ARAGON</option>
						
					<option value="1236">ARAGON - CORTE INGLES</option>
						
					<option value="1238">ARAGON - ES RAFAL</option>
						
					<option value="1237">ARAGON - GUELL</option>
						
					<option value="14">ARCH. LUIS SALVADOR</option>
						
					<option value="2231">ARENAL</option>
						
					<option value="95">AUSIAS MARCH</option>
						
					<option value="1363">AVD. ARGENTINA</option>
						
					<option value="96">AVENIDAS</option>
						
					<option value="1201">BALMES</option>
						
					<option value="6">BLANQUERNA</option>
						
					<option value="1061">BORN</option>
						
					<option value="227">CA´N BARBARA</option>
						
					<option value="99">CA´N CAPAS</option>
						
					<option value="24">CA´N PASTILLA</option>
						
					<option value="591">CALA ESTANCIA</option>
						
					<option value="1224">CALA GAMBA</option>
						
					<option value="44">CALA MAYOR</option>
						
					<option value="120">CALLE MANACOR</option>
						
					<option value="927">CAM. JESUS</option>
						
					<option value="13">CAMP REDO</option>
						
					<option value="2207">CAPITAN SALOM</option>
						
					<option value="31">CARREFOUR</option>
						
					<option value="1228">CAS CAPISCOL</option>
						
					<option value="179">CASABLANCA</option>
						
					<option value="999">CASCO ANTIGUO</option>
						
					<option value="98">CASTELL BELLVER</option>
						
					<option value="1">CENTRO</option>
						
					<option value="22">CIUDAD JARDIN</option>
						
					<option value="1062">COLEGIOS</option>
						
					<option value="23">COLL DEN RABASSA</option>
						
					<option value="995">CONSERVATORIO</option>
						
					<option value="10370">COTLLIURE</option>
						
					<option value="10299">CRTA. PUIGPUNYENT</option>
						
					<option value="183">CRTA. VALLDEMOSA</option>
						
					<option value="100">CRUZ ROJA</option>
						
					<option value="39">EL TERRENO</option>
						
					<option value="7">ES FORTI</option>
						
					<option value="53">ES PIL.LARI</option>
						
					<option value="553">ES PUNTIRO</option>
						
					<option value="27">ES VIVERO</option>
						
					<option value="47">ESTABLIMENTS</option>
						
					<option value="210">ESTACIONES</option>
						
					<option value="996">ESTADIO BALEAR</option>
						
					<option value="92">EUSEBIO ESTADA</option>
						
					<option value="926">GENERAL RIERA</option>
						
					<option value="40">GENOVA</option>
						
					<option value="2">HONDEROS</option>
						
					<option value="16">HOSTALETS</option>
						
					<option value="2226">INDUSTRIA</option>
						
					<option value="102">INSTITUTOS</option>
						
					<option value="10429">JACINTO VERDAGUER</option>
						
					<option value="103">JAUME III</option>
						
					<option value="104">JOAN ALCOVER</option>
						
					<option value="41">LA BONANOVA</option>
						
					<option value="592">LA GRUTA</option>
						
					<option value="1227">LA RAMBLA</option>
						
					<option value="10381">LA RIBERA</option>
						
					<option value="19">LA SOLEDAD</option>
						
					<option value="35">LA VILETA</option>
						
					<option value="10383">LAS MARAVILLAS</option>
						
					<option value="2217">LONJA</option>
						
					<option value="105">LOS MOLINOS</option>
						
					<option value="2218">LUIS VIVES</option>
						
					<option value="596">MARIVENT</option>
						
					<option value="21">MOLINAR</option>
						
					<option value="2392">OCIMAX</option>
						
					<option value="1023">OLMOS</option>
						
					<option value="1239">PARC SES FONTS</option>
						
					<option value="1199">PARQUE DE LAS ESTACIONES</option>
						
					<option value="1021">PARQUE DEL MAR</option>
						
					<option value="928">PARQUE ESTADA</option>
						
					<option value="1072">PASEO MALLORCA</option>
						
					<option value="42">PASEO MARITIMO</option>
						
					<option value="51">PLAYA DE PALMA</option>
						
					<option value="1200">POLICLINICA</option>
						
					<option value="32">POLIG. CA´N VALERO</option>
						
					<option value="109">POLIG. LEVANTE</option>
						
					<option value="29">POLIG. SON CASTELLO</option>
						
					<option value="10376">POLIG. SON VALENTI</option>
						
					<option value="20">PORTITXOL</option>
						
					<option value="43">PORTO PI</option>
						
					<option value="37">PUEBLO ESPAÑOL</option>
						
					<option value="5">PZA. CARDENAL REIG</option>
						
					<option value="106">PZA. COLUMNAS</option>
						
					<option value="97">PZA. ESPAÑA</option>
						
					<option value="1392">PZA. MADRID</option>
						
					<option value="1060">PZA. MAYOR</option>
						
					<option value="94">PZA. OLIVAR</option>
						
					<option value="2198">PZA. PARIS</option>
						
					<option value="3">PZA. PEDRO GARAU</option>
						
					<option value="108">PZA. PROGRESO</option>
						
					<option value="2229">PZA. SANT COSME</option>
						
					<option value="90">PZA. TOROS</option>
						
					<option value="26">RAFAL NOU</option>
						
					<option value="91">RAFAL VELL</option>
						
					<option value="17">REYES CATOLICOS</option>
						
					<option value="997">RICARDO ORTEGA</option>
						
					<option value="121">ROSALES</option>
						
					<option value="52">S´ARANJASSA</option>
						
					<option value="107">S´ESCORXADOR</option>
						
					<option value="696">S´HOSTALOT</option>
						
					<option value="50">S´INDIOTERIA</option>
						
					<option value="10379">SA GARRIGA</option>
						
					<option value="1226">SA TEULERA</option>
						
					<option value="45">SAN AGUSTIN</option>
						
					<option value="998">SAN FERNANDO</option>
						
					<option value="2225">SAN MIGUEL</option>
						
					<option value="10310">SAN VICENTE DE PAUL</option>
						
					<option value="112">SANT JORDI</option>
						
					<option value="8">SANTA CATALINA</option>
						
					<option value="48">SECAR DE LA REAL</option>
						
					<option value="10382">SEMINARIO</option>
						
					<option value="993">SOMETIMES</option>
						
					<option value="202">SON ANGLADA</option>
						
					<option value="38">SON ARMADANS</option>
						
					<option value="1073">SON BUIT</option>
						
					<option value="113">SON CLADERA</option>
						
					<option value="12">SON COTONER</option>
						
					<option value="11">SON DAMETO</option>
						
					<option value="93">SON DURETA</option>
						
					<option value="49">SON ESPAÑOL</option>
						
					<option value="10">SON ESPAÑOLET</option>
						
					<option value="25">SON FERRIOL</option>
						
					<option value="114">SON FORTEZA</option>
						
					<option value="115">SON FORTEZA NOU</option>
						
					<option value="118">SON FUSTER</option>
						
					<option value="18">SON GOTLEU</option>
						
					<option value="119">SON MASIA</option>
						
					<option value="182">SON MOIX</option>
						
					<option value="15">SON OLIVA</option>
						
					<option value="184">SON PERETO</option>
						
					<option value="36">SON RAPINYA</option>
						
					<option value="33">SON ROCA</option>
						
					<option value="116">SON ROQUETA</option>
						
					<option value="117">SON ROQUETA NOU</option>
						
					<option value="8937">SON ROSSINYOL</option>
						
					<option value="2232">SON RULLAN</option>
						
					<option value="977">SON SANT JOAN</option>
						
					<option value="30">SON SARDINA</option>
						
					<option value="34">SON SERRA</option>
						
					<option value="46">SON VIDA</option>
						
					<option value="1066">SON XIGALA</option>
						
					<option value="9">TENIS</option>
						
					<option value="209">TRAFICO</option>
						
					<option value="1291">VIRGEN DE LLUC</option>









<!-- mapa baleares -->
<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d789679.6862473629!2d2.890970665205733!3d39.363340582005755!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2ses!4v1435416760002" width="1900" height="700" frameborder="0" style="border:0" allowfullscreen></iframe>

