// JavaScript Document
	
$(document).ready(function(){


// $('.carousel-inner:first-child').addClass("active");
// $('.carousel-indicator:first-child').addClass("active");

	// //dando anchura a footer y nav
	// function dar_anchura(i){
	// 	var anchura = $(window).width();
	// 	$(i).css({'width':anchura});
	// }
	// function dar_altura(i){
	// 	var altura = $(window).height();
	// 	$(i).css({'height':altura});
	// 	//alert(altura);
	// }


	// dar_anchura('#footer');
	// dar_altura(document);

	
	// var altura = $(window).height();
	// var altura_cm = $('#contenedor_maximo').height();
	// var alt = $(document).height();


	//$(window).resize(function(){
		// var anchura = $(window).width();
		// if(anchura < 700){
		// 	var buscador = $('#buscador').clone();
		// 	$(document).remove('#buscador');
		// 	//extracto = document.getElementById(buscador);
		// 	alert(buscador);
		// }
		// // //alert(anchura);
		// dar_anchura('#footer');
		// dar_anchura('#nav_bar');
	
	// });
	

// $('#search-form .term').bind('input', function(){
//   console.log('this actually works');
// });



	function busquedas_ajax(){

		this.suj_input = $('input[name="sujerencia"]');
		this.suj_res   = $('.sujerencia-response');
		this.input_top = '';
		this.input_left = '';
	}




// busquedas_ajax.prototype.sujerir_lugar = function(elem){
// 		var actual = this.suj_input.val();
// 		var lugar = $(elem).html();
// 		this.suj_input.val('');
// 		this.suj_input.attr('placeholder',lugar);
// }
busquedas_ajax.prototype.fijar_lugar = function(elem){
		var actual = this.suj_input.val();
		var lugar = $(elem).html();
		//alert(lugar);
		this.suj_input.val(lugar);
		this.cerrar_sujerencias();
}
busquedas_ajax.prototype.limpiar_sujerencia = function(elem){
		//this.suj_input.val('');
		this.suj_input.attr('placeholder','Poblacion');
}

busquedas_ajax.prototype.mostrar_sujerencias = function(){
		this.suj_res.addClass('sujerencia-on').removeClass('sujerencia-off');
		this.suj_res.css({'top':this.input_top+'px','left':this.input_left+'px'});
}
busquedas_ajax.prototype.cerrar_sujerencias = function(){
		this.suj_res.addClass('sujerencia-off').removeClass('sujerencia-on');
}

busquedas_ajax.prototype.consultar_sujerencias = function(string){
		
		var caracteres = string.length;

		if(caracteres >1){
			//enviara ajax
			posicionReal = this.suj_input.offset();
			this.input_top = posicionReal.top;
			this.input_left = posicionReal.left;
			var elmnt = document.getElementById("sujerencia-input");
			var input_height = elmnt.offsetHeight;
			var aleatorio = hex_sha512(Math.round(Math.random()*999));
			var tok = '3_'+aleatorio;			//numero de campos totales mas aleatorio
			var token = hex_sha512(tok+string); 	//en el orden que se envian
			var object = this;
			this.input_top = parseInt(this.input_top) + parseInt(input_height);
			
			$.ajax({
				type:'POST',
				url:'inc/ajax/ajax_lugar_sujerido.php',
				data:({	tok 	 :tok,
						token 	 :token,
						sujerencia:string
						}),
				success: function(response){
					object.suj_res.html(response);
					object.mostrar_sujerencias();
				}
			});

		}else{
			//un caracter o nada
			this.suj_res.addClass('sujerencia-off').removeClass('sujerencia-on');
		}	
}



busquedas_ajax.prototype.consultar_poblacion = function(pro,suj){

		
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '4_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+pro+suj); 	//en el orden que se envian

		$.ajax({
			type:'POST',
			url:'inc/ajax/ajax_municipio.php',
			data:({	tok 	 :tok,
					token 	 :token,
					provincia:pro,
					poblacion:suj
					}),
			success: function(response){
				$('.municipios').html(response);
			}
		});

}

	var sujerencia = new busquedas_ajax();

	//al escribir muestro
	$('input[name="sujerencia"]').bind('input', function(){
		var string = sujerencia.suj_input.val();
		sujerencia.consultar_sujerencias(string);
	});
	$('input[name="sujerencia"]').on('click', function(){
		var string = sujerencia.suj_input.val();
		sujerencia.consultar_sujerencias(string);
	});	

	//cierro sujerencia al sacar el raton
	$('.sujerencia-response').on( 'mouseleave','ul',function(){
		var element = $(this)
		sujerencia.cerrar_sujerencias();
		sujerencia.limpiar_sujerencia();

	});

	//selecciono sujerencia
	$('.sujerencia-response').on( 'click','li',function(){
		var element = $(this);
		var pro = $(this).attr('rel');
		var suj = $(this).attr('rol');

		sujerencia.fijar_lugar(element);
		sujerencia.consultar_poblacion(pro,suj);
		//alert(lugar);
	});


	$('#buscador').on('submit', function(e){
		//alert(this);
		$(this.sujerencia).remove();

		//e.preventDefault();
	});


	//1.0 consultas ajax del formulario de busqueda y publicacion
	//1.1-al seleccionar una provincia se llamara por ajax a los municipios
	//funcional en busqueda y publicacion		
	$('select[name=provincia]').change(function(){
		var pro = $(this).val();
		sujerencia.consultar_poblacion(pro,0);
	});
	
	$('select[name=municipio]').change(function(){
		var pobl = $('select[name=municipio] :selected').html();
		sujerencia.suj_input.val(pobl);
	});




	//1.2-al cambiar cambiara los tipos de inmueble
	//funcional en busqueda y publicacion
	$('select[name=tipo_inmueble]').change(function(){
		//cojer idioma de campo lang_form
	   	var lang = $('input[name=lang_form]').val();
	   	var elegido = $(this).val();
	   	var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '4_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+elegido+lang); 	//en el orden que se envian

		$.ajax({
			type: "POST",
			url: "inc/ajax/tipos_inmuebles.php",
			data: ({ tok 	 :tok,
					 token   :token,
					 inmueble:elegido,
				     lg 	 :lang }),
			beforeSend: function(){ 
				$("#cargador").css('display', 'inline')
			},
			complete: function(){ 
				$("#cargador").css('display', 'none')
			},
			success: function(respuesta){
                //   alert(elegido);  
				$('.subtipo_inmueble').html(respuesta);
			}
		});
	});
	
	//1.3-cargara extras segun tipo inmueble
	//funcional solo en publicacion, pero porque yo quiero!	

	function post_toextras(){
		var tipo = $('select[name=tipo_inmueble]').val();
		var subtipo = $('select[name=subtipo_inmueble]').val();
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '4_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+tipo+subtipo); 	//en el orden que se envian

		$.ajax({
			type:'POST',
			url:'inc/ajax/ajax_extras.php',
			data:({	tok    :tok,
					token  :token,
					tipo   :tipo,
				   	subtipo:subtipo}),
			success: function(response){
				//alert(principio2);
				$('#extras').html(response);
			}
		});	
	}
	

	$('select[name=tipo_inmueble]').change(function(){
		post_toextras();
	});	
	$('select[name=subtipo_inmueble]').change(function(){
		post_toextras();
	});	


	$('#crear-anuncio select[name=tipo_inmueble]').change(function(){
		post_toextras();
	});	
	$('#crear-anuncio select[name=subtipo_inmueble]').change(function(){
		post_toextras();
	});	




	//precio-min precio-max coherencia entre ellos
	//==================================================
	$('select[name=precio_min]').change(function(){
		var precio = $(this).val();
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '3_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+precio); 	//en el orden que se envian

		$.ajax({
			type: "POST",
			url: "inc/ajax/ajax_precio_minmax.php",
			data: ({ tok 	 :tok,
					 token   :token,
					 precio_min:precio}),
			success: function(respuesta){
                   //alert(precio);  
				$('#precio_max').html(respuesta);	
			}
		});
	});	

	$('select[name=precio_min]').change(function(){});
	$('select[name=precio_max]').change(function(){});
	$('select[name=superf_min]').change(function(){});
	$('select[name=superf_max]').change(function(){});
	$('select[name=rooms]').change(function(){});

	
	//2.0 ajax para el panel de seleccion
	//===================================================================	   
	//2.1 funcion, tiene dos fases, una en la que se recojen datos y otra en que se envian por ajax
	function busca_pisos(destino){
			
		if(destino == 'consulta'){
			var destino = 'inc/ajax/ajax_consulta.php';
			var respuesta = '#consulta-ajax';
			var empresa = '';
		}else if(destino == 'inmobiliaria'){
			var destino = 'inc/ajax/ajax_inmobiliaria.php';
			var respuesta = '#inmv-ress';
			var empresa = $('input[name=empresa]').val();
		}
		
		var mod 	  = $('#mod_selector input:checked').attr('id');
		var ordenar   = $('select[name=ordenar]').val();
		var provincia = $('select[name=provincia]').val();   
		var municipio = $('select[name=municipio]').val();

		//si selecciona "todos" sera null, asi que lo arreglamos 
		if(municipio == null){municipio = '';}
			
		var tipo_inmueble = $('select[name=tipo_inmueble]').val();   
		var subtipo_inmueble = $('select[name=subtipo_inmueble]').val();

		//si selecciona "todos" sera null, asi que lo arreglamos 
		if(subtipo_inmueble == null){subtipo_inmueble = '';}
			
		var precio_min      = $('select[name="precio_min"]').val();
		var precio_max      = $('select[name="precio_max"]').val();
		var superf_min   	= $('select[name="superf_min"]').val();
		var superf_max   	= $('select[name="superf_max"]').val();
		var hab 			= $('select[name="rooms"]').val();
		
		filtro1=[];
		$("input[name='filtro_venta[]']:checked").each(function(){
			filtro1.push($(this).val());
		});
		
		filtro2=[];
		$("input[name='filtro_extras[]']:checked").each(function(){
			filtro2.push($(this).val());
		});	
		
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '14_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+provincia+municipio+tipo_inmueble+subtipo_inmueble+precio_min+precio_max+superf_min+superf_max+hab+empresa+mod+ordenar); 	//en el orden que se envian

		//alert('we are wathers');
		$.ajax({
			type:"POST",
			url:destino,
			data:({
				tok:tok,
				token:token,
				provincia:provincia,
				municipio: municipio,
				tipo_inmueble:tipo_inmueble,
				subtipo_inmueble: subtipo_inmueble, 
				precio_min:precio_min,
				precio_max:precio_max,
				superf_min:superf_min,
				superf_max:superf_max,
				rooms:hab,
				filtro1:filtro1,
				filtro2:filtro2,
				empresa:empresa,
				mod:mod,
				ordenar:ordenar
				}),
				//beforeSend:function(){ $('#consulta').fadeOut(); },
				//complete:function(){ $('#consulta').fadeIn(); },
				success:function(response){
					$(respuesta).html(response);
					}
			});
			
	}//fin de funcion busca_pisos



	//3.2 cada select que cambie
	$('#busqueda_main select').change(function(){
		busca_pisos('consulta');  
		});
	//3.3cada checkbox que cambie	
	$('#busqueda_main input[type="checkbox"]').change(function(){
		busca_pisos('consulta');
		});
	
	$('#ordenador-cont select').change(function(){
		busca_pisos('consulta');
	});

	$('#mod_selector input').change(function(){
		busca_pisos('consulta');
	});


	//3.2 cada select que cambie
	$('.form-inmv select').change(function(){
		busca_pisos('inmobiliaria');  
	});
	//3.3cada checkbox que cambie	
	$('.form-inmv input[type="checkbox"]').change(function(){
		busca_pisos('inmobiliaria');
	});

	$('.form-inmv input').change(function(){
		busca_pisos('inmobiliaria');
	});	
	
	
	
	
	//envio de busqueda de pisos
	//===================================================
	$('#inmv-form #buscador').submit(function(){
		busca_pisos('inmobiliaria');
		return false;
	});
	
	

	
	
	
	
	

	//centra el contenedor de formularios acction
	// var anchura = $(window).width();
	// var anchura_form = $('.user-access').outerWidth();
	// var resto =  anchura-anchura_form;
	// 	resto = resto / 2;
	// $('.user-access').css({position:'absolute',left:resto+'px'});
		

	
	

	//elimino clase error al volver a pinchar (para todos los form)
	//================================================
	$('input, select, textarea').focus(function(){
		$(this).parent().removeClass('has-error');
	});
	
	//formularios de alertas, validacion
	//=============================================================
	$('.family_alert').submit(function(e){

		var formulario = $(this).attr('id');
		var validacion = new family_form(formulario);
		validacion.family_alert(e);
		
	});
	
	
	
	
//fin	
});