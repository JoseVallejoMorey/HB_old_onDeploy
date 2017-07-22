// JavaScript Document

$(document).ready(function(){


	//selects de stock
	$('select[name="stock_seccion"]').change(function(){
		var val = $(this).val();
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '3_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+val); 	//en el orden que se envian
		//alert(val);
		
		$.ajax({
			type: "POST",
			url: "inc/ajax/ajax_mda/ajax_mda_stock.php",
			data: ({tok:tok,
					token:token,
					stock: val }),
			success: function(respuesta){
				$('#stock-response').html(respuesta);	
			}
				
		});
	});


	//creamos links validos para menus y footer
	$('.linkeros select').change(function(){
		var cual = $(this).attr('rel');
		var response = '#'+cual+'_response';
		var val = $(this).val();
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '3_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+val); 	//en el orden que se envian
			
		$.ajax({
			type: "POST",
			url: "inc/ajax/ajax_mda/ajax_mda_links.php",
			data: ({tok:tok,
					token:token,
					que: val }),
			success: function(respuesta){
				$(response).html(respuesta);	
			}
					
		});
	});



	//pedidos de traducciones
	//=======================================================

	//cierre de panel
	var cierraPanel = function (){
		$('#tradd_response').html('');
		quitarCierres();
	}
	//agregar opcion de cierre
	var agregarCierre = function(tr){
		var este = 'tr[rel='+tr+']';
		$(este+' .td_option').html('<a>Cerrar</a>');
	}
	//quitar todos los cierres
	var quitarCierres = function(){
		$('.td_option').html('');
	}
	//envio de ajax	
	var abrePanel = function(){
		var pedido = $(this).parent().attr('rel');
		var tiket = $(this).parent().attr('data');
		quitarCierres();
		agregarCierre(pedido);
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '4_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+pedido+tiket); 	//en el orden que se envian
				
		$.ajax({
			type: "POST",
			url: "inc/ajax/ajax_mda/ajax_mda_pedidos.php",
			data: ({tok:tok,
					token:token,
					pedido:pedido,
					tiket:tiket }),
			success: function(respuesta){
				$('#tradd_response').html(respuesta);	
			}
		});
	}

	//ejecucion de abrir/cerrar panel (y opciones de cierre)
	$('.td_option').on("click", cierraPanel );
	$('#tradd_nav li').on("click", cierraPanel );
	$('#t_traducciones td:not(.td_option)').on("click", abrePanel );




	//activando y desactivando sessiones y directivas, confirmacion
	//=========================================
	$('.interruptor a').click(function(){
		var link = $(this).attr('link');
	    if(confirm("¿Quieres continuar?")) {
	        document.location.href=link;
	    }
	});



	//cambiando estado del portal (con confirmacion)
	//=============================================
	$('#masterman').submit(function(e){
	    if(!confirm("¿Seguro?")) {
	    	alert('no se cambiara nada');
	    	e.preventDefault();
	    }
	});



});