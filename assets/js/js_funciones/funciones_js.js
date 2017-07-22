
//varias funciones utilizadas en todo el sitio:
//validaciones de formularios
//seleccion del get



//funcion que obtendra el valor de get mn_nav
//============================================================
function getURLvar(var_name){
	var re = new RegExp(var_name + "(?:=([^&]*))?", "i");
	var pm = re.exec(decodeURIComponent(location.search));
	if(pm === null) return undefined;
	return pm[1] || "";
}




//logica de la aplicacion
//al final se comprobara si fail y fail_key siguen siendo nulos, y
//si descartado es false (no deberia poder ser null)
//si se encontrara algun error, descartado seria true
//si todo esta ok convertimos success a true y entonces continua con el envio del form


//logica de la api
//=============================================================

//establece parametros para validacion de formularios
function family_form(formulario){
	this.formulario = '#'+formulario;
	this.descartado = null;
	this.success 	= false;
	this.fail_ 		= null;
	this.fail_key 	= null;

	//comprobamos checkeds
	this.checkeds();

}

//establece parametros modo fail
family_form.prototype.validation_failed = function(msj){
	this.descartado = true;
	this.fail_ 		= true;
	this.fail_key 	= msj;
} 

//establece parametros modo continue
family_form.prototype.eslabon_superado = function(){
	if(this.descartado != true){
		this.descartado = false;
	}
} 

//convertira succes a true si pasa la prueba
family_form.prototype.comprobacion = function(){
	//alert(this.descartado);
	if( (this.descartado == false) && 
		(this.fail_ == null) && 
		(this.fail_key == null) ){
		//alert('formulario valido');
			this.success = true;
	}else{
		alert('formulario no valido');
	}
}

//permitira o detendra el envio del formulario segun sea correcto
family_form.prototype.envio_rechazo = function(e,msj){

	if(this.success != true){
		alert(this.fail_key);
		e.preventDefault();			
	}else{
		//no hay errores, 
		if(msj == 1){  alert('Mensaje enviado con exito. Gracias');  }
		//e.preventDefault();
	}
}

//funciones de aspecto visual
//====================================================================

//pinta de rojo el padre contenedor del error
family_form.prototype.pintar_rojo = function(susodicho){

	if(typeof susodicho == 'object'){
		//alert('es objeto '+susodicho);
		//var nombre = $(susodicho).attr('name');
		//var nombre = susodicho.name;
		//alert(nombre);
	}
	
	$(susodicho).parent().addClass('has-error');
}



//funciones de requeridos
//====================================================================

//validando todos los campos requeridos
family_form.prototype.requireds_all = function(){
	//alert('requireds all');
	this.requireds_input();
	this.requireds_number();
	this.requireds_select();
	this.requireds_textarea();
}
//validando campos select requeridos
// family_form.prototype.requireds_select = function(){
// 	var susodicho = this.formulario+' select[req=required]';
// 	var objeto 	  = this;
// 	$(susodicho).each(function(){  objeto.validate_select(susodicho);	});
// }

//validando campos select requeridos 2.0
family_form.prototype.requireds_select = function(){
	var objeto 	  = this;
	$(this.formulario+' select[req=required]').each(function(){  
		objeto.validate_select(this);	
	});
}




//validando campos input requeridos 2.0
family_form.prototype.requireds_input = function(){
	var objeto 	  = this;
	$(this.formulario+' input[req=required]').each(function(){  
		objeto.validate_text(this,3);
	});
}

//validando campos input que deban ser numericos y sean cortos
family_form.prototype.requireds_number = function(){
	var objeto 	  = this;
	$(this.formulario+' input[req=num-required]').each(function(){  

		var valor = $(this).val();
		valor = valor.trim();

		if( (valor.length < 1) || (isNaN(valor)) ){
			objeto.pintar_rojo(this);
			objeto.validation_failed(' Numero muy corto.');
		}else{
			//alert('esta bien');
			objeto.eslabon_superado();
		}
	});
}

//validando campos trextarea requeridos
family_form.prototype.requireds_textarea = function(){
	var susodicho = this.formulario+' textarea[req=required]';
	var objeto 	  = this;
	$(susodicho).each(function(){  objeto.validate_text(susodicho,200);	});
}

//añadimos a todos el atributo req
family_form.prototype.todos_required = function(){
	//(no funcionara con los que vangan por ajax)
	$('select').each(function(){	
		$(this).not("[optional='true']").attr('req','required');	});
	$('input[type=text]').each(function(){ 	
		$(this).not("[optional='true']").not("[req='num-required']").attr('req','required');	});
}

//les quitamos a todos el atributo req
family_form.prototype.ningun_required = function(){
	$('select').each(function(){			$(this).attr('req','');	});
	$('input[type=text]').each(function(){ 	$(this).attr('req','');	});
	this.eslabon_superado();
}



//functiones de validacion
//====================================================================

//terminos condiciones privacidad (check obligado)
family_form.prototype.checkeds = function(){

	var privacidad 	= this.formulario+' input[name=privatepol]';
	var terminos 	= this.formulario+' input[name=termandcon]';
	var object 		= this;

	//si hay politica de privacidad debe aceptar
	$(privacidad).each(function(){
		if(!$(privacidad).is(":checked")){
			object.validation_failed('Debe aceptar la Politica de privacidad.');
		}else{
			object.eslabon_superado();
		}	
	});

	//si hay terminos y condiciones debe marcarlos
	$(terminos).each(function(){
		if(!$(terminos).is(":checked")){
			object.validation_failed('Debe aceptar los Terminos y condiciones.');
		}else{
			object.eslabon_superado();
		}	
	});

}



//validacion de email 
family_form.prototype.validate_email = function(valor){
	expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ( !expr.test(valor) ){	
    	var tratado = $('input[value="'+valor+'"]');
		this.pintar_rojo(tratado);	
		this.validation_failed('La dirección de correo es incorrecta.');
	}else{
		this.eslabon_superado();
	}
}

//validacion de telefono
family_form.prototype.validate_phone = function(valor){
	//phone se va a buscar al imput con su valor y lo colorea
	if( (valor.length < 9) || (isNaN(valor)) ) {
		var tratado = $('input[value="'+valor+'"]');
		this.pintar_rojo(tratado);			
		this.validation_failed('Telefono incorrecto.');
	
	}else{
		this.eslabon_superado();
	}
}

//valida texto !>3
family_form.prototype.validate_text = function(susodicho,num){
	var name = $(susodicho).attr('name');
	var valor = $(susodicho).val();
	// alert(name);
	// alert(valor);
	valor = valor.trim();
	if(valor.length < num){
		this.pintar_rojo(susodicho);
		this.validation_failed('Deben rellenarse todos los campos requeridos text.');
	}else{
		//alert('esta bien');
		this.eslabon_superado();
	}
}

//validando selects
family_form.prototype.validate_select = function(susodicho){
	var sel = $(susodicho).val();
	if (sel == '' || sel== 0){
		this.pintar_rojo(susodicho);
		alert(sel);
		this.validation_failed('Deben rellenarse todos los campos requeridos sel.');
	}else{
		this.eslabon_superado();
	}
}

//validando lo que diceser un password
family_form.prototype.validate_pass = function(valor){
	valor = valor.trim();
	if(valor.length < 5){
		var tratado = $('input[value="'+valor+'"]');
		this.pintar_rojo(tratado);	
		this.validation_failed('Minimo 8 caracteres en password.');
	}else{
		this.eslabon_superado();
	}
}

//validacion de nif
family_form.prototype.validate_nif = function (value){

	var validChars = 'TRWAGMYFPDXBNJZSQVHLCKET';
	var nifRexp = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKET]{1}$/i;
	var nieRexp = /^[XYZ]{1}[0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKET]{1}$/i;
	var str = value.toString().toUpperCase();

	if (!nifRexp.test(str) && !nieRexp.test(str)) return this.validation_failed('Dni falso.');

	var nie = str.replace(/^[X]/, '0').replace(/^[Y]/, '1').replace(/^[Z]/, '2');
	var letter = str.substr(-1);
	var charIndex = parseInt(nie.substr(0, 8)) % 23;

	if (validChars.charAt(charIndex) == letter) return this.eslabon_superado();

	return this.validation_failed('Dni incorrecto.');
}	

//le paso elemento de imagen
family_form.prototype.validate_img = function(imagen){

	var object = this;
	if(imagen.length == 0){
		object.validation_failed('Seleccione una imagen');
	}else{
		for(x=0; x < imagen.length; x++){
			if(imagen[x].type != "image/png" &&
			   imagen[x].type != "image/jpg" &&
			   imagen[x].type != "image/jpeg"){
				object.validation_failed('Tipo de archivo no permitido');
			}
			if(imagen[x].size > 3072*3072*1){
				object.validation_failed('Archivo supera maximo tamaño permitido');
		}	}	
		object.eslabon_superado();
}	}


//segun familia de formularios
//=============================================================================

//formularios de alertas
family_form.prototype.family_alert = function(e){
	//definicion de variables
	var email = $(this.formulario+' input[name=email]').val();
	var phone = $(this.formulario+' input[name=telefono]').val();
	//validacion de parametros
	this.validate_email(email);
	this.validate_phone(phone);
	this.validate_text(this.formulario+' input[name=nombre]',3);
	this.validate_text(this.formulario+' input[name=comentario]',20);
	//comprobacion
	this.comprobacion();
	this.envio_rechazo(e,1);
}


//formularios de registro, login, lost y rewrite
family_form.prototype.family_accion = function(){
	//definicion de variables
	var objeto 	= this;		//para funciones necesito distinguir bien el objeto
	var email 	= $(this.formulario+' input[name=email]').val();
	var phone 	= $(this.formulario+' input[name=telefono]').val();
	var e_phone = $(this.formulario+' input[name=empresa_telefono]').val();
	var nif 	= $(this.formulario+' input[name=nif]').val();

	//comprobamos requeridos
	this.requireds_all();

	//validara cada campo password 
	$(this.formulario+' input[type=password]').each(function(){
		var este = $(this).val();
		objeto.validate_pass(este);
	});

	//validacion de parametros
	if(email   	!= undefined){	this.validate_email(email);	  }
	if(phone 	!= undefined){	this.validate_phone(phone);	  }
	if(e_phone 	!= undefined){	this.validate_phone(e_phone); }
	if(nif 	   	!= undefined){	this.validate_nif(nif);		  }

	//comprobacion
	this.comprobacion();
	//alert(this.fail_key);

}


//creando anuncio, creando idioma
family_form.prototype.family_anuncio = function(e){

	//comprobamos requeridos
	this.requireds_all();
	//comprobacion
	this.comprobacion();
	//envio o no
	this.envio_rechazo(e,0);
	
}



family_form.prototype.family_wizard1 = function(id){
//le damos el id de dentro del formulario, todos los hijos del id nos referimos
//al terminar devolvera false si no sale bien

	var object = this;
	$(this.formulario+' #'+id+' input[req=required]').each(function(){  
		object.validate_text(this,3);
	});
	$(this.formulario+' #'+id+' select').each(function(){ 
		object.validate_select(this);	
	});

	//comprobacion
	this.comprobacion();
	//prueba final en llamada
}


family_form.prototype.family_wizard2 = function(id){
//le damos el id de dentro del formulario, todos los hijos del id nos referimos
//al terminar devolvera false si no sale bien

	var object = this;
	$('#'+id+' select').not("[optional='true']").each(function(){	
		//$(this).attr('req','required');
		//alert('select required');	
		object.validate_select(this);
	});

	$('#'+id+' input[type=text][req="required"]').each(function(){ 	
		//$(this).attr('req','required');	
		//alert('input required');
		object.validate_text(this,3);
	});
	//validara los numericos 
	this.requireds_number();
	//comprobacion
	this.comprobacion();


	//prueba final en llamada
}
























//perfil usuario y perfil empresa
family_form.prototype.family_perfil = function(e){
	var objeto	= this;		//para funciones necesito distinguir bien el objeto
	objeto.requireds_all();	//comprobamos requeridos

	//si hay imagen hay que validarla
	$('#imagenes').each(function(){
		var imagenes   = document.getElementById('imagenes').files;
		if(imagenes.length != 0){  objeto.validate_img(imagenes);	 }		
	});

	//valida emails que pueda haber(email valido, pasamos de los vacios)
	$(this.formulario+' input.email-val').each(function(){
		var valor = $(this).val();
		if(valor != ''){
			objeto.validate_email(valor);
		}
	});

	//valida telefonos que pueda haber
	$(this.formulario+' input.tel-val').each(function(){
		var valor = $(this).val();
		if(valor != ''){
			objeto.validate_phone(valor);
		}
	});

	//comprobacion de resultados
	objeto.comprobacion();
	//envio o no
	objeto.envio_rechazo(e,0);

}

//esta me servira para validar todos form de compra y renovacion
family_form.prototype.family_buy = function(e){
	var objeto = this;
	//si hay imagen hay que validarla
	$('#imagenes').each(function(){
		var imagenes   = document.getElementById('imagenes').files;
		//alert(imagenes);
		if(imagenes.length != 0){  objeto.validate_img(imagenes);	 }		
	});

			
	this.requireds_all();		//comprobamos requeridos
	this.comprobacion();		//comprobacion 
	//alert(this.fail_key);
	this.envio_rechazo(e,0);	//finalizacion





}

