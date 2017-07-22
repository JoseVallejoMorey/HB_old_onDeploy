//referente a validacion de formularios de registro, login, lost, rewrite

//inicia parametros de conversion de pass
function formhash2(formulario){
	this.formul = document.getElementById(formulario);

	//alert(form);
	this.password 	   = this.formul.password;
	this.password2 	   = this.formul.password2;
	this.old_password  = this.formul.old_password;
	this.old_password2 = this.formul.old_password2;
}


//cada uno de los pass a modificar
formhash2.prototype.passmutation = function(mutar){
	var newimput  = document.createElement("input");
	var v_mutante = hex_sha512(mutar.value);
	var n_mutante = mutar.name;

	this.formul.appendChild(newimput);
	newimput.name  = n_mutante;
	newimput.type  = "hidden";
	newimput.value = v_mutante;

}


//llamada general a modificar todos los pass allados
formhash2.prototype.globalize = function(){
	//alert("je sui N'espetus");

	if(typeof this.password == 'object'){	   this.passmutation(this.password);		}
	if(typeof this.password2 == 'object'){	   this.passmutation(this.password2);		}
	if(typeof this.old_password == 'object'){  this.passmutation(this.old_password);	}
	if(typeof this.old_password2 == 'object'){ this.passmutation(this.old_password2);	}


	return true;
}



$(document).ready(function(){
	
	//cambios al mostrar  reg
	//===============================================================
	var accion = getURLvar('accion');
	if(accion == 'reg'){
		$('.login-box-locked').css({'margin-top':'150px'});
	}


	//llamada a ajax para formulario de registro
	$('form[name=form_reg] select[name=tipo_usuario]').change(function(){

		var tipo = $(this).val();
		var aleatorio = hex_sha512(Math.round(Math.random()*999));
		var tok = '3_'+aleatorio;			//numero de campos totales mas aleatorio
		var token = hex_sha512(tok+tipo); 	//en el orden que se envian

		if(tipo == 'empresa'){
			$.ajax({
				type:"POST",
				url:'inc/ajax/ajax_perfil/ajax_reg.php',
				data:({	tok:tok,
						token:token,
						tipo:tipo}),
				success: function(response){

					$('#res-response').animate({ 
						'min-height': "150px" }, {
							duration: 700,
							complete: function(){$('#res-response').html(response)}
						});
					}
			});
			
		}else{
			
			$('#res-response').html('');
			$('#res-response').css({'min-height':'0px'});
		}
		
	});
	

//'form_reg, form_login, form_lost, form_log2, form_rewrite'
//funciona, habra que validar telefonos, campos vacios, nif
//verificacion preenvio de formulario de registro, login, lost, rewrite
//=======================================================
	$('form[rel=form_act]').submit(function(e){

		var formulario = $(this).attr('id');
		var validacion = new family_form(formulario);
		var form_hash  = new formhash2(formulario);

		validacion.family_accion();
			
		if(validacion.success != true){
			//corto envio de formulario y devuelvo error
			e.preventDefault();
			//alert('el formulario no se va a enviar');			
			var error_tag = 'class="error-report alert alert-danger" role="alert"';
			$('#error-container div.alert').remove();
			$('#error-container').append('<div '+error_tag+'>'+validacion.fail_key+'</div>');
		}else{
			//alert('se enviara formulario');
			form_hash.globalize();
			//e.preventDefault();
		}
			
	}); //fin de verificacion




});