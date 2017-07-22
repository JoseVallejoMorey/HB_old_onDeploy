//este se cargara para los formularios de anuncios (anuncio, img, idiomas)
//formulario new anuncio

$(document).ready(function(){

//=================================================================	
//DATOS DEL ANUNCIO
//=================================================================

//asistente para creacion de anuncios
$('#wizard1 li a').on('click',function(){
	var este = $(this).attr('href');
	//alert(este);
	$('li a[href="'+este+'"] span').removeClass('badge-danger').addClass('badge-info');
});


//index indica la pestaña actual

	$('#wizard1').bootstrapWizard({
		'nextSelector': '.button-next',
		'previousSelector': '.button-previous', 
		onNext: function(tab, navigation, index) {

			//inicio el validador
			var formulario = 'publicar_1';
			var validacion = new family_form(formulario);	
		
			if($('#borrador').is(':checked')){
			}else{

				if(index==1) {
					var bugs = 0;
					validacion.family_wizard1('tab1');
					if(validacion.success != true){
						var pestanna = 'li a[href="#tab1"]';
						$(pestanna+' span').removeClass('badge-info').addClass('badge-danger');
						bugs = 1;
					}
					if( bugs == 1) {return false;	}
				}

				if(index==2) {	
					var bugs = 0;			
					validacion.family_wizard2('tab2');
					if(validacion.success != true){
						var pestanna = 'li a[href="#tab2"]';
						$(pestanna+' span').removeClass('badge-info').addClass('badge-danger');						
						//alert(validacion.fail_key);
						bugs = 1;
					}			
					if( bugs == 1) {return false;	}
				}
			}

	}, onTabShow: function(tab, navigation, index) {
		var $total = navigation.find('li.pasos').length;
		var $current = index+1;
		var $percent = ($current/$total) * 100;
		$('#wizard1').find('.progress-bar').css({width:$percent+'%'});
		
		$('#wizard1 > .steps li').each( function (index) {
			$(this).removeClass('complete');
		  	index += 1;
		  	if(index < $current) {
		    	$(this).addClass('complete');
		  	}
		 });
		
		if($current >= $total) {
			$('#wizard1').find('.button-next').hide();
			$('#wizard1').find('.button-finish').show();
		} else {
			$('#wizard1').find('.button-next').show();
			$('#wizard1').find('.button-finish').hide();
		}	
	}});












	//direccion y permisos
	//==========================================================
	$('select[name="direccion_permisos"]').change(function(){
		var tipo 	  = $(this).val();
		var direccion = 'input[name="direccion"]';		
		//bloqueo o desbloqueo el select de tipo_nuevo
		if(tipo != '4'){
			//activo input
			$(direccion).removeAttr('disabled');
			$(direccion).attr("optional","false");
			$(direccion).attr('req','required');		
		}else{
			//deshabilito input
			$(direccion).attr('disabled','');
			$(direccion).val('');
			$(direccion).attr("optional","true");
			$(direccion).attr('req','');			
		}
	});
	

	//form precio
	$('#mpa').click(function(){
		//bloquea o desbloquea campos, añade optional true 
		//si esta desabilitado y elimina valor		
		if($(this).is(':checked')) {  
            $('#mpa-group fieldset').removeAttr('disabled');
			$('input[name="precio_antiguo"]').removeAttr("optional");
			$('input[name="precio_antiguo"]').attr("optional","false");
        } else {  
            $('#mpa-group fieldset').attr('disabled','');
			$('input[name="precio_antiguo"]').val('');
			$('input[name="precio_antiguo"]').attr("optional","true");
        }  
	});
	
	
//superficies exteriores
	$('select[name="suelo[]"]').change(function(){
		var rel = $(this).attr('rel');
		var val = $(this).val();
		var input = 'input[name="metros[]"][rel="'+rel+'"]';
		//alert(input);

		if(val != 0){
			//habilitar inputs
			$(input).removeAttr('disabled');
			$(input).removeAttr("optional");
			$(input).attr('req','num-required');
		}else{
			//deshabilitar
            $(input).attr('disabled','');
			$(input).val('');
			$(input).attr("optional","true");
			$(input).removeAttr("req");
		}
	});


	
	//cargara caracteristicas segun tipo inmueble
	//============================================
	$('select[name=tipo_inmueble]').change(function(){

		var inmueble = $(this).val();
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '3_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+inmueble); 	//en el orden que se envian
		$.ajax({
			type:'POST',
			url:'inc/ajax/ajax_perfil/ajax_caracteristicas.php',
			data:({	tok  	:tok,
					token	:token,
					inmueble:inmueble}),
			success: function(response){
				//alert(principio2);
				$('#carac_ajax').html(response);
				//alert(response);
			}
		});	
	});

	


	//1.3-cargara extras segun tipo/subtipo inmueble 
	//mostrara seleccionados en anuncio
	function anuncio_toextras(){
		var anuncio = $('input[name=art]').val();
		var tipo = $('select[name=tipo_inmueble]').val();
		var subtipo = $('select[name=subtipo_inmueble]').val();
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '5_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+tipo+subtipo+anuncio); 	//en el orden que se envian

		$.ajax({
			type:'POST',
			url:'inc/ajax/ajax_perfil/ajax_extras_anuncio.php',
			data:({	tok    :tok,
					token  :token,
					tipo   :tipo,
				   	subtipo:subtipo,
				   	anuncio:anuncio}),
			success: function(response){
				//alert(principio2);
				$('#extras').html(response);
			}
		});	
	}

	$('#crear-anuncio select[name=tipo_inmueble]').change(function(){
		anuncio_toextras();
	});	
	$('#crear-anuncio select[name=subtipo_inmueble]').change(function(){
		anuncio_toextras();
	});	




//=================================================================	
//IMAGENES Y RELACIONADOS (titulo, comentario, preferida)
//=================================================================

//X-editable para titulo de imagen
$('.title-response').editable({
    type: 'text',
    url: 'inc/ajax/ajax_perfil/ajax_img_title.php',
    name: 'titulo',
    //multiples datos
    params: function(params) {
    var data = {};
    var aleatorio = hex_sha512(Math.round(Math.random()*999));   
    
    data['elegido'] = params.pk;
    data[params.name] = params.value;
	data['tok'] = '4_'+aleatorio;				//numero de campos totales mas aleatorio
	data['token'] = hex_sha512(data['tok']+params.pk+params.value); //en el orden que se envian

    return data;
  	}
});

//X-editable para descripcion de imagen
$('.descr-response').editable({
    type: 'text',
    url: 'inc/ajax/ajax_perfil/ajax_img_descr.php',
    name: 'descripcion',
    //multiples datos
    params: function(params) {
    var data = {};
    var aleatorio = hex_sha512(Math.round(Math.random()*999));   
    
    data['elegido'] = params.pk;
    data[params.name] = params.value;
	data['tok'] = '4_'+aleatorio;				//numero de campos totales mas aleatorio
	data['token'] = hex_sha512(data['tok']+params.pk+params.value); //en el orden que se envian

    return data;
  	}
});


	
	//confirmacion para eliminar imagen de anuncio
	//===================================================
	$('.deleteador a').click(function(){

		var link = $(this).attr('link');
	    if(confirm("¿Seguro que desea eliminar la imagen?")) {
	        document.location.href=link;
	    }

	});



//=================================================================	
//IDIOMAS (TITULO Y DESCRIPCION)
//=================================================================

	//añadir descripciones en diferentes idiomas
	//======================================================
	$('select[name="idiomas_extra"]').change(function(){
	   	var lang = $(this).val();
		var art  = getURLvar('art');
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '4_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+lang+art); 	//en el orden que se envian

		//alert(lang);
		$.ajax({
			type: "POST",
			url: "inc/ajax/ajax_perfil/ajax_lang_description.php",
			data: ({tok:tok,
					token:token,
					idioma_extra:lang,
					art:art}),
			success: function(respuesta){
				$('#lang-response').html(respuesta);	
			}
		});
	});
	
	



//=================================================================	
//VALIDACIONES
//=================================================================

	//verificacion preenvio de formulario de publicacion anuncio
	//=======================================================
	$('#publicar_1').submit(function(e){
		
		var formulario = $(this).attr('id');
		var validacion = new family_form(formulario);
		
		//si es borrador se permiten campos vacios
		if($('#borrador').is(':checked')){
			$(formulario).append('<input type="hidden" name="borrador" value="true"/>');
			validacion.ningun_required();	//no se realizan comprobaciones
		}else{
			validacion.todos_required();	//alert('no es borrador y se comprueba todo');
		}

		validacion.family_anuncio(e);		//ejecucion y finalizacion
	
	}); //fin de verificacion

	//validacion de imagenes que se suben (tamaño y formato)
	//=================================================================
	$('#publicar_2').submit(function(e){

		var imagenes   = document.getElementById('imagenes').files;
		var validacion = new family_form('publicar_2');

		validacion.validate_img(imagenes);	//inicio de las pesquisas
		validacion.comprobacion();			//comprobacion 
		validacion.envio_rechazo(e,0);		//finalizacion

	});	//fin de verificacion

	//validacion de idioma (titulo y descripcion)
	//=================================================================

	$('#publicar_3').submit(function(e){
		var validacion = new family_form('publicar_3');
		validacion.family_anuncio(e);
	});	//fin de verificacion


});