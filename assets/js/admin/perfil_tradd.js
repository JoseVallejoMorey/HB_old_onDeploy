
//llamado para traduccion de anuncios (compra)

$(document).ready(function(){


var traddAjax = function(){

		var cantidad  = $( "input:checked" ).length;
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok   = '3_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+cantidad); 	//en el orden que se envian

		$.ajax({
			type:"POST",
			url:"inc/ajax/ajax_tienda/ajax_traduccion.php",
			data:({
				tok:tok,
				token:token,
				cantidad:cantidad
				}),
				beforeSend: function(){ 
				$("#renew-response").html('<img class="loading" src="assets/img/loading.gif" />');
			},
			complete: function(){ 
				$("#renew-response").html()
			}, 
				success:function(response){
					$("#renew-response").html(response);
				}
		});
}



$('input[type=checkbox]').on( "click", traddAjax );


});