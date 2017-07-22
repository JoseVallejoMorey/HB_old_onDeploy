// JavaScript Document

//entra en juego en perfil de usuario, contiene las funciones necesarios para compras de productos
	
$(document).ready(function(){


	//animating menus on hover
	//============================================================
	// $('ul#user_options li:not(.nav-header)').hover(function(){
	// 	$(this).animate({'margin-left':'+=5'},300);
	// },
	// function(){
	// 	$(this).animate({'margin-left':'-=5'},300);
	// });
	
	

	//alla donde haya una tienda, al cambiar de pestanna borrado del precio
	var borradoPrecio = function(){
		$('#renew-response').html('');
	}
	$('ul.tienda-helper').on("click", borradoPrecio);
	
	//funcion y llamamiento special_area	
	//==============================================================
	function check_special(){
		
		var saltador  = $('input[name=saltador]').val();
		var seccion   = $('select[name=seccion]').val();
		var periodo   = $('select[name=periodo]').val();
		
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '5_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+seccion+periodo+saltador); 	//en el orden que se envian

		$.ajax({
			type:"POST",
			url:"inc/ajax/ajax_tienda/ajax_special.php",
			data:({	tok:tok,
					token:token,
					seccion: seccion,
					periodo: periodo,
					saltador:saltador
				}),
				beforeSend: function(){ 
				$("#renew-response").html('<img class="loading" src="assets/img/loading.gif" />')
			},
			complete: function(){ 
				$("#renew-response").html()
			},
				success:function(response){
					$("#renew-response").html(response);
					//alert(response);
					}
			});

	}//fin de ver disponibilidad

	
	//accion para special area
	//===========================================================
	$('#disp_answer select').change(function(){
		check_special();
	});
	
	//accion para star area 30-06-14  
	//===========================================================
	$('#star_monstruario .anuncio_pro').click(function(){
		var elegido = $(this).attr('data-ref');
		var periodo = $('#periodo_star select').val();
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '4_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+elegido+periodo); 	//en el orden que se envian
		
		$.ajax({
			type: "POST",
			url: "inc/ajax/ajax_tienda/ajax_star.php",
			data:({	tok:tok,
					token:token,
					elegido:elegido,
					periodo:periodo}),
			success: function(respuesta){
				$('#renew-response').html(respuesta);	
			}
			});
	});
	$('#periodo_star select').change(function(){
		$('#renew-response').html('');
	});
	
	
	
	//compra de banners 31-01-14
	//======================================================================

	$('select[name="tipo_bann"]').change(function(){
		
		var saltador = $('input[name="saltador"]').val();
		var tipo = $(this).val();
		//bloqueo o desbloqueo el select de tipo_nuevo
		if(tipo=='nuevo'){
			$('#perfil_banner_form fieldset').removeAttr('disabled');
		}else{
			$('#perfil_banner_form fieldset').attr('disabled','');
		}
		
		
		
		//limpio la respuesta anterior de ajax
		$('#bann_response2').html('');
			var aleatorio = hex_sha512(Math.round(Math.random()*999));
			var tok = '4_'+aleatorio;			//numero de campos totales mas aleatorio
			var token = hex_sha512(tok+saltador+tipo); 	//en el orden que se envian

		$.ajax({
			type:"POST",
			url:"inc/ajax/ajax_tienda/ajax_banners_select.php",
			data:({	tok:tok,
					token:token,
					saltador:saltador,
					tipo_bann:tipo
				}),
				beforeSend: function(){ 
				$("#bann_response1").html('<img class="loading" src="assets/img/loading.gif" />')
			},
			complete: function(){ 
				$("#bann_response1").html()
			}, 
				success:function(response){
					$("#bann_response1").html(response);
					$("#radios").css({'display':'block'})
					}
			});
	});





	
	$('select.launcher').change(function(){

		//var provincia = $('select[name="provincia"]').val();
		var saltador = $('input[name="saltador"]').val();
		var periodo = $('select[name="periodo"]').val();
		var tipo_bann = $('select[name="tipo_bann"]').val();	
		var tipo_nuevo = $('select[name="tipo_bann_nuevo"]').val();	
		
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '6_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+tipo_nuevo+tipo_bann+saltador+periodo); 	//en el orden que se envian

		$.ajax({
			type:"POST",
			url:"inc/ajax/ajax_tienda/ajax_banners.php",
			data:({	tok:tok,
					token:token,
					tipo_nuevo:tipo_nuevo,
					tipo_bann:tipo_bann,
					saltador:saltador,
					periodo:periodo
				}),
				beforeSend: function(){ 
				$("#renew-response").html('<img class="loading" src="assets/img/loading.gif" />')
			},
			complete: function(){ 
				$("#renew-response").html()
			},
			success:function(response){
				$("#renew-response").html(response);
			}
		});
		
	});
	
	

	//desactivando anuncio
	//================================================
	$('.desactivador').click(function(){

		var electo = $(this).attr('rel');
		var wey = $(this).attr('data-wey');
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '4_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+electo+wey); 	//en el orden que se envian

		$.ajax({
			type: "POST",
			url: "inc/ajax/ajax_perfil/ajax_desactivar_anuncio.php",
			data:({	tok:tok,
					token:token,
					electo:electo,
					wey:wey}),
			success: function(respuesta){
				$('#act-'+electo).html(respuesta);	
			}
		});

	});


	//modificando info de usuario,empresa,logo (funciona para los 3)
	//=============================================
	// $('button[rel="mod_perfil"]').click(function(){
	// 	var archivo = $(this).attr('id');
	// 	var destino = 'ajax_'+archivo;
	// 	var saltador = $(this).attr('data-modder');
	// 	if( $(this).hasClass('active')){var action = '0';
	// 	}else{							var action = '1';	}

	// 	var aleatorio = hex_sha512(Math.round(Math.random()*999));
	// 	var tok = '4_'+aleatorio;			//numero de campos totales mas aleatorio
	// 	var token = hex_sha512(tok+action+saltador); 	//en el orden que se envian

	// 	$.ajax({
	// 		type: "POST",
	// 		url: "inc/ajax/ajax_perfil/"+destino+".php",
	// 		data:({	tok:tok,
	// 				token:token,
	// 				action:action,
	// 				saltador:saltador}),
	// 		success: function(respuesta){
	// 			$('#perfil-response').html(respuesta);	
	// 		}
	// 	});
	// });




//cambios en perfil empresa
$('.perfil-empresa-response').editable({
  type: 'text',
  url: 'inc/ajax/ajax_perfil/ajax_perfil_empresa.php',
  name: 'titulo',
  //multiples datos
  params: function(params) {
	  var data = {};
	  var aleatorio = hex_sha512(Math.round(Math.random()*999));   

	  data['campo'] = $(this).attr('rel');
	  data['elegido'] = params.pk;
	  data[params.name] = params.value;
		data['tok'] = '5_'+aleatorio;				//numero de campos totales mas aleatorio
		data['token'] = hex_sha512(data['tok']+data['campo']+params.pk+params.value); //en el orden que se envian

	  return data;
  	}
});


//cambios en perfil usuario
$('.perfil-usuario-response').editable({
  type: 'text',
  url: 'inc/ajax/ajax_perfil/ajax_perfil_usuario.php',
  name: 'titulo',
  //multiples datos
  params: function(params) {
	  var data = {};
	  var aleatorio = hex_sha512(Math.round(Math.random()*999));   

	  data['campo'] = $(this).attr('rel');
	  data['elegido'] = params.pk;
	  data[params.name] = params.value;
		data['tok'] = '5_'+aleatorio;				//numero de campos totales mas aleatorio
		data['token'] = hex_sha512(data['tok']+data['campo']+params.pk+params.value); //en el orden que se envian

    return data;
  	}
});


//eliminar u ocultar, agente u oficina
//============================================
function cambios_ajax(){

this.cual = '';
this.accion = '';
this.saltador = '';
}



var cambio = new cambios_ajax();

	$('.options-oficina a').click(function(){

		cambio.cual = $(this).attr('data-subject');
		cambio.accion = $(this).attr('data-action');
		cambio.saltador = $(this).attr('saltador');
		cambio.indicador = 'oficina';

	    if(confirm("¿Quieres continuar?")){
	        //ajax send
	        cambio.ajax_options();
	    }
	});


	$('.options-agente a').click(function(){

	  cambio.cual = $(this).attr('data-subject');
		cambio.accion = $(this).attr('data-action');
		cambio.saltador = $(this).attr('saltador');
		cambio.indicador = 'agente';

	    if(confirm("¿Quieres continuar?")){
	        //ajax send
	        cambio.ajax_options();
	    }
	});



cambios_ajax.prototype.ajax_options = function(){

		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '6_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+this.cual+this.accion+this.saltador+this.indicador); 	//en el orden que se envian

		$.ajax({
			type: "POST",
			url: "inc/ajax/ajax_perfil/ajax_perfil_opciones.php",
			data:({	tok:tok,
					token:token,
					cual:this.cual,
					accion:this.accion,
					saltador:this.saltador,
					indicador:this.indicador}),
			success: function(respuesta){
				location.reload();
			}
		});
}
//ajax sennd









	//aqui hay que currarse una validacion para los tres formularios
	//==============================================================

	//form  de subida de logo de empresa
	// $('#logo_empresa').submit(function(e){

	// 	var imagenes   = document.getElementById('imagenes').files;
	// 	var validacion = new family_form('logo_empresa');

	// 	validacion.validate_img(imagenes);	//inicio de las pesquisas
	// 	validacion.comprobacion();			//comprobacion 
	// 	validacion.envio_rechazo(e,0);		//finalizacion

	// });


	//forms de logo de empresa, datos de usuario o datos de empresa
	$('.datos_perfil').submit(function(e){

		//vigilar que vengan los requeridos
		var formulario = $(this).attr('id');
		var validacion = new family_form(formulario);
		validacion.family_perfil(e);	

	});

	//compra o renovacion de cualquier servicio
	$('.form_buy').submit(function(e){
		//todos estos tendran una clase comun y un id unico

		var formulario = $(this).attr('id');
		var validacion = new family_form(formulario);
		validacion.family_buy(e);

		//e.preventDefault(e);	
	});











	// //renovando servicios y paquetes
	// //=====================================
	// $('a[data-rel="renovator"],a[data-rel="recomprator"]').click(function(){

	// 	var destino 	= '';
	// 	var electo 		= $(this).attr('rel');
	// 	var rel 		= $(this).attr('data-rel');
	// 	var tipo   		= $(this).attr('tipo');
	// 	var action 		= $(this).attr('accion');
	// 	var saltador 	= $(this).attr('data-modder');
	// 	var togleador   = '#renew-response'; //new
	// 	// if(rel =='recomprator'){	var togleador = $('#renew-response-'+rel);
	// 	// }else{						var togleador = $('#renew-response-'+tipo);	}

	// 	//var tog_estado 	= togleador.attr('accion');
	// 	var aleatorio 	= hex_sha512(Math.round(Math.random()*999));
	// 	var tok 		= '4_'+aleatorio;			//numero de campos totales mas aleatorio
	// 	var token 		= hex_sha512(tok+electo+saltador); 	//en el orden que se envian

	// 	if(tipo == 1){destino = 'paquetes_'}else{destino = ''}
	// 	//2-toggle abro y cierro
	// 	// if(action == 'open'){						//hay que abrir
	// 	// 	if(tog_estado == 'closed'){				//esta cerrado, abre

	// 	// 		$(togleador).toggle('slow');
	// 	// 		$(togleador).attr('accion','open');
	// 	// 	}
	// 	// 	//si paso uno a close todos los demas pasan a open
	// 	// 	$('a[data-rel="renovator"]').attr('accion','open');
	// 	// 	$('a[data-rel="recomprator"]').attr('accion','open');
	// 	// 	$(this).attr('accion','close');
			
	// 	// }else{										//hay que cerrar
	// 	// 	$(togleador).toggle('slow');			//cierra
	// 	// 	$(this).attr('accion','open');
	// 	// 	$(togleador).attr('accion','closed');
	// 	// }
	// 	//3-ajax
	// 	$.ajax({
	// 		type: "POST",
	// 		url: 'inc/ajax/ajax_tienda/ajax_'+destino+'renew.php',
	// 		data:({	tok:tok,
	// 				token:token,
	// 				electo:electo,
	// 				saltador:saltador}),
	// 		success: function(respuesta){
	// 			$(togleador).html(respuesta);	
	// 		}
	// 	});
	// 	//fin
	// });



	//renovando servicios y paquetes
	//=====================================
	$('a.ajax-renew').click(function(){

		var destino 	= '';
		var electo 		= $(this).attr('rel');
		var tipo   		= $(this).attr('tipo');
		var saltador 	= $(this).attr('data-modder');

		var aleatorio 	= hex_sha512(Math.round(Math.random()*999));
		var tok 		= '4_'+aleatorio;			//numero de campos totales mas aleatorio
		var token 		= hex_sha512(tok+electo+saltador); 	//en el orden que se envian

		if(tipo == 1){destino = 'paquetes_'}else{destino = ''}

		//3-ajax
		$.ajax({
			type: "POST",
			url: 'inc/ajax/ajax_tienda/ajax_'+destino+'renew.php',
			data:({	tok:tok,
					token:token,
					electo:electo,
					saltador:saltador}),
			success: function(respuesta){
				$('#renew-response').html(respuesta);	
			}
		});
		//fin
	});











	//compra de paquetes
	$('#paquetes_tabla select').change(function(){
		var anuncios = $('select[name="paquete"]').val();
		var duracion = $('select[name="periodo"]').val();
		// var preciador = '#preciador_paquetes';

		// //si ninguno de los dos es null activaremos boton de compra
		// if( (anuncios == 'null') || (duracion == 'null') ){
		// 	$(preciador).html('');
		// }else{
		// 	$(preciador).html('<input type="submit" value="comprar" />');
		// }
		
		var aleatorio 	= hex_sha512(Math.round(Math.random()*999));
		var tok 		= '4_'+aleatorio;			//numero de campos totales mas aleatorio
		var token 		= hex_sha512(tok+anuncios+duracion); 	//en el orden que se envian

		$.ajax({
			type: "POST",
			url: 'inc/ajax/ajax_tienda/ajax_paquetes_buy.php',
			data:({	tok:tok,
					token:token,
					anuncios:anuncios,
					duracion:duracion}),
			success: function(respuesta){
				$('#renew-response').html(respuesta);	
			}
		});
	});


	//cliente quiere darse de baja
	$('#confirm1').click(function(){

		var tipouser = $(this).attr('rel');

		//alert(tipouser);
		$('#confirm_response').toggle('slow');

		var aleatorio 	= hex_sha512(Math.round(Math.random()*999));
		var tok 		= '3_'+aleatorio;			//numero de campos totales mas aleatorio
		var token 		= hex_sha512(tok+tipouser); 	//en el orden que se envian

		$.ajax({
			type: "POST",
			url: 'inc/ajax/ajax_perfil/ajax_baja.php',
			data:({	tok:tok,
					token:token,
					tipouser:tipouser}),
			success: function(respuesta){
				$('#confirm_response').html(respuesta);	
			}
		});

	});
	
//fin
});