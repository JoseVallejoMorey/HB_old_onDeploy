//este se cargara solamente en perfil/promocionar
//funciones promocioar anuncios y llamada

$(document).ready(function(){


	function promo_anuncios(){
		this.cantidad = 0;
		this.marcados = 0;
		this.periodo  = 0;
		this.ajax_response = '#renew-response';
	}


	promo_anuncios.prototype.sumar_marcado = function(){
		this.marcados = this.marcados+1;
		if(this.marcados == this.cantidad){
			this.deshabilitar_resto();
		}
	}

	promo_anuncios.prototype.restar_marcado = function(){
		this.marcados = this.marcados-1;
		if(this.marcados < this.cantidad){
			this.habilitar_todos();
		}
	}

	promo_anuncios.prototype.resetear_marcas = function(){
		this.marcados = 0;
		$('#form-pro input:checkbox').each(function(){
			$(this).attr('checked',false);
		});
		this.habilitar_todos();
		$(this.ajax_response).html('');
	}

	promo_anuncios.prototype.deshabilitar_resto = function(){
		$('#form-pro input:checkbox').not(':checked').each(function(){
			$(this).attr('disabled','disabled');
		});
	}
	promo_anuncios.prototype.habilitar_todos = function(){
		$('#form-pro input:checkbox').each(function(){
			$(this).removeAttr('disabled');
		});
	}


	//inventamos funcion para ajax
	promo_anuncios.prototype.preguntar_precio =	function (){
		objeto = this;
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '4_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+objeto.cantidad+objeto.periodo); 	//en el orden que se envian

		$.ajax({
			type:"POST",
			url:"inc/ajax/ajax_tienda/ajax_promocionar.php",
			data:({
				tok:tok,
				token:token,
				cantidad:objeto.cantidad,
				periodo:objeto.periodo
				}),
				beforeSend: function(){ 
				$(objeto.ajax_response).html('<img class="loading" src="assets/img/loading.gif" />');
			},
			complete: function(){ 
				$(objeto.ajax_response).html();
			}, 
				success:function(response){
					$(objeto.ajax_response).html(response);
				}
		});
	}


	//cantidad correcta consulta ajax, sino borro
	promo_anuncios.prototype.comprobar_ajax = function(){

		if( (this.cantidad != 0) && (this.cantidad == this.marcados) ){
			//alert('envio ajaz');
			this.preguntar_precio();
		}else{
			//alert('borro ajax');
			$(this.ajax_response).html('');
		}

	}


	//llamada a la clase
	var promo = new promo_anuncios();

	$('select[name="cantidad"]').change(function(){
		promo.cantidad = $(this).val();
		promo.resetear_marcas();
	});

	$('select[name="periodo"]').change(function(){
		promo.periodo = $(this).val();
		promo.resetear_marcas();
	});

	$('#form-pro input:checkbox').change(function(){
		
		if($(this).is(':checked')){
			promo.sumar_marcado();
		}else{
			promo.restar_marcado();
		}

		promo.comprobar_ajax();
	});



//the end	
});