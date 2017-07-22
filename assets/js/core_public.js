/****
* MAIN NAVIGATION
*/

$(document).ready(function($){

// Formulario de busqueda principal cambiando entre top y sidebar
// ==============================================================

	// llevara el form a sidebar
	form_to_sidebar = function(){
		var formulario = $('#form-viajante').clone(true);
		if(typeof formulario == 'object'){
			//alert('voy a sidebar');
			$('#form-viajante').remove();
			$('#form-sidebar').html(formulario);
			$('#form-viajante').attr('rel','on-sidebar');
			$('#form-sidebar .show-less').css({'display':'none'});
			$('#form-sidebar #show-less').css({'display':'none'});
			$('#form-sidebar #show-more').css({'display':'none'});
		}
	} 
	//sacara el form de sidebar y lo mete en pagina
	form_to_top = function(){
		var formulario = $('#form-viajante').clone(true);
		if(typeof formulario == 'object'){
			//alert('voy a top');
			$('#form-viajante').remove();
			$('#form-top').html(formulario);
			$('#form-viajante').attr('rel','on-top');
			$('#form-top #show-more').css({'display':'block'});
		}	
	}

	//movera el form de busqueda donde corresponda
	moviendo_form_busqueda = function(win_Width_now){
		var actual = $('#form-viajante').attr('rel');
		if(actual == 'on-top'){
			if (win_Width_now < 768) {
				form_to_sidebar();
			}
		}
		if(actual == 'on-sidebar'){
			if (win_Width_now > 768) {
				form_to_top();
			}
		}
	}

	restaurar_sidebar = function(){
		$('.sidebar').removeClass('sidebar-active').addClass('sidebar-hidden');
		$('.navbar').css({'left':'0px'});
	}

	mostrar_sidebar = function(){
		$('.sidebar').removeClass('sidebar-hidden').addClass('sidebar-active');
		$('.sidebar').animate({left:"0px" },500);
		$('.navbar').animate({left:"250px"},500);
	}

	ocultar_sidebar = function(){
		$('.sidebar').animate({left:"-250px"},500, function(){
			$('.sidebar').addClass('sidebar-hidden').removeClass('sidebar-active');
		});
		$('.navbar').animate({left:"0px"},500);
	}




	//ladrilleria de los anuncios	
	/* masonry layout */
    var $container = $('.container-property');
    $container.imagesLoaded( function(){

        $container.masonry();
    });


	//si cargo pagina xs caambio el form de lugar
	var win_Width = $(window).width();


	if (win_Width < 1200) {
		$('#public_content').addClass('container-fluid').removeClass('container');
	}

	if (win_Width < 768) {
		form_to_sidebar();
	}



	$(window).resizeEnd(function() {
	    // run this code only once after the user finished resizing
		var win_Width_now = $(window).width();


		//cambio container segun convenga
	if (win_Width_now < 1200) {
		$('#public_content').addClass('container-fluid').removeClass('container');
		$container.masonry();
	}else{
		$('#public_content').addClass('container').removeClass('container-fluid');
		$container.masonry();
	}



		moviendo_form_busqueda(win_Width_now);

		//si sidebar tiene la clas active la quito
		if($('.sidebar').hasClass('sidebar-active')){
			restaurar_sidebar();
		}

	});










	$('#form-extras li').bind('click', function (e) { e.stopPropagation() })

	//mostrar mas parametros
	$('#show-more').on('click',function(){
		$('#form-top #show-more').css({'display':'none'});
		$('#form-top #show-less').css({'display':'block'});
		$('#form-top .show-less').css({'display':'block'});
	});
	//mostrar menos parametros
	$('#show-less').on('click',function(){
		$('#form-top #show-less').css({'display':'none'});
		$('#form-top #show-more').css({'display':'block'});
		$('#form-top .show-less').css({'display':'none'});
	});



	//abriendo y cerrando sidebar para usuarios moviles
	$('#sidebar-menu').click(function(){
		if($('.sidebar').hasClass('sidebar-hidden')){
			mostrar_sidebar();
		}else{
			ocultar_sidebar();
		}				
	});







	
	// if($('body').hasClass('rtl')) {
	// 	loadCSS('assets/css/bootstrap-rtl.min.css', loadCSS('assets/css/style.rtl.min.css',1,0))
	// }
	





	//actives de la barra de navegacion
	//============================================================
	//aqui si es venta o alquiler buscamos los check y los marcamos(detallito clave)
	//con select comercial funciona pero no la respuesta ajax
	
	var menu_actual = getURLvar('mn_nav');
	var archivo = getURLvar('archivo');
	
		  if (menu_actual == 'venta'){
			  $('#main_nav li[rel=2]').addClass('activo').css({'border-bottom':'none'});
			  $(':checkbox').each(function(){
			  	var este = $(this).val();
			  	if(este=='venta'){	$(this).attr("checked", "checked");	}
			  });
	}else if (menu_actual == 'alquiler'){
			  $('#main_nav li[rel=3]').addClass('activo').css({'border-bottom':'none'});
			  $(':checkbox').each(function(){
			  	var este = $(this).val();
			  	if(este=='alquiler'){	$(this).attr("checked", "checked");	}
			  });
	}else if (menu_actual == 'comercial'){
			  $('#main_nav li[rel=4]').addClass('activo').css({'border-bottom':'none'});
			  $('select[name="tipo_inmueble"] option[value="2"]').attr("selected","selected");
	}else if (archivo == 'inmobiliarias'){
			  $('#main_nav li[rel=5]').addClass('activo').css({'border-bottom':'none'});		  
	}else if (menu_actual == ''){$('#main_nav li[rel=1]').addClass('activo');
		
		}
	








	
	//escondiendo aside segun seccion
	//========================================
	// if(getURLvar('pag')){
	// 	$('body').addClass("sidebar-hidden");
	// }
	// if(getURLvar('pagg')){
	// 	$('body').addClass("sidebar-hidden");
	// }
	// if(getURLvar('inmv')){
	// 	$('body').addClass("sidebar-hidden");
	// }

	/* ---------- Main Menu Open/Close, Min/Full ---------- */		
	// $('#main-menu-toggle').click(function(){
		
	// 	if($('body').hasClass('sidebar-hidden')){
									
	// 		$('body').removeClass('sidebar-hidden');
	// 		//$('#main-con').removeClass('center-main');
	// 		$container.masonry();
			
	// 	} else {
						
	// 		$('body').addClass('sidebar-hidden');
	// 		$container.masonry();
	// 		//$('#main-con').removeClass('center-main');
			
	// 	}				
		
	// });
	

		
	// $('#sidebar-minify').click(function(){
		
	// 	// if($('body').hasClass('sidebar-minified')){
						
	// 	// 	$('body').removeClass('sidebar-minified');
	// 	// 	//$('#sidebar-minify i').removeClass('fa-list').addClass('fa-ellipsis-v');
						
	// 	// } else {
						
	// 	// 	$('body').addClass('sidebar-minified');
	// 	// 	//$('#sidebar-minify i').removeClass('fa-ellipsis-v').addClass('fa-list');
	// 	// }
	// 	// //$('.sidebar').addClass('sidebar-active').removeClass('sidebar-hidden');












	// });
	
	// widthFunctions();
	// dropSidebarShadow();
	// init();
	
	// $(".sidebar").mmenu();
	
	/* ---------- Disable moving to top ---------- */
	$('a[href="#"][data-top!=true]').click(function(e){
		e.preventDefault();
	});
		










		
});








// todo lo que necesito ahora

// sidebar existe pero esta oculta
// al pasar a vista de moviles podra mostrarse
// entonces aparecera desde la derecha, por encima
// del contenido
// al clicar se escondera
//y si puedo utilizar las clases que existen ya pues mejor
// fin

//asi que de entrada segun ancho de window le meto una u otra clase

























/****
* PANELS ACTIONS
*/

// $(document).on('click', '.panel-actions a', function(e){
// 	e.preventDefault();
	
// 	if ($(this).hasClass('btn-close')) {
// 		$(this).parent().parent().parent().fadeOut();
// 	} else if ($(this).hasClass('btn-minimize')) {
// 		var $target = $(this).parent().parent().next('.panel-body');
// 		if($target.is(':visible')) $('i',$(this)).removeClass($.panelIconOpened).addClass($.panelIconClosed);
// 		else 					   $('i',$(this)).removeClass($.panelIconClosed).addClass($.panelIconOpened);
// 		$target.slideToggle('slow', function() {
// 		    widthFunctions();
// 		});
// 	} else if ($(this).hasClass('btn-setting')) {
// 		$('#myModal').modal('show');
// 	}
	
// });

// function init() {
	
// 	/* ---------- Minimized panel ---------- */
// 	$('.panel-minimized').find('.panel-actions i.' + $.panelIconOpened).removeClass($.panelIconOpened).addClass($.panelIconClosed);
	
// 	 ---------- Tooltip ---------- 
// 	$('[rel="tooltip"],[data-rel="tooltip"]').tooltip({"placement":"bottom",delay: { show: 400, hide: 200 }});

// 	/* ---------- Popover ---------- */
// 	$('[rel="popover"],[data-rel="popover"],[data-toggle="popover"]').popover();
	
// }

// $('.sidebar-menu').scroll(function() {
//    dropSidebarShadow();
// });

// function dropSidebarShadow() {
	
// 	if ($('.nav-sidebar').length) {
// 		var topPosition = $('.nav-sidebar').offset().top - $('.sidebar').offset().top;	
// 	}
		
// 	if (topPosition < 60) {
// 		$('.sidebar-header').addClass('drop-shadow');
// 	} else {
// 		$('.sidebar-header').removeClass('drop-shadow');
// 	}
	
// 	var bottomPosition = $(window).height() - $('.nav-sidebar').outerHeight() - topPosition;
	
// 	if (bottomPosition < 130) {
// 		$('.sidebar-footer').addClass('drop-shadow');
// 	} else {
// 		$('.sidebar-footer').removeClass('drop-shadow');
// 	}
// }

/****
* CHECK BROWSER VERSION
*/
// function browser() {
	
// 	var isOpera = !!(window.opera && window.opera.version);  // Opera 8.0+
// 	var isFirefox = testCSS('MozBoxSizing');                 // FF 0.8+
// 	var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
// 	    // At least Safari 3+: "[object HTMLElementConstructor]"
// 	var isChrome = !isSafari && testCSS('WebkitTransform');  // Chrome 1+
// 	//var isIE = /*@cc_on!@*/false || testCSS('msTransform');  // At least IE6

// 	function testCSS(prop) {
// 	    return prop in document.documentElement.style;
// 	}
	
// 	if (isOpera) {	
// 		return false;
// 	}else if (isSafari || isChrome) {
// 		return true;
// 	} else {
// 		return false;
// 	}
// }

           
/****
* SMART RESIZE
*/
// $(window).bind("resize", widthFunctions);

// function widthFunctions(e) {
	
// 	var headerHeight = $('.navbar').outerHeight();
// 	var footerHeight = $('footer').outerHeight();
	
//     var winHeight = $(window).height();
//     var winWidth = $(window).width();

// 	if(!$('body').hasClass('static-sidebar')) {
// 		var sidebarHeaderHeight = $('.sidebar-header').outerHeight();
// 		var sidebarFooterHeight = $('.sidebar-footer').outerHeight();
		
// 		if (winWidth < 992) {
// 			var otherHeight = sidebarHeaderHeight + sidebarFooterHeight;
// 		} else {
// 			var otherHeight = headerHeight + sidebarHeaderHeight + sidebarFooterHeight;
// 		}
// 		$('.sidebar-menu').css('height', winHeight - otherHeight);		
// 	}
	
// 	if (winWidth < 992) {	
// 		if ( $('body').hasClass('sidebar-hidden') ) {
// 			$('body').removeClass('sidebar-hidden').addClass('sidebar-hidden-disabled');
// 		}
		
// 		if ( $('body').hasClass('sidebar-minified') ) {
// 			$('body').removeClass('sidebar-minified').addClass('sidebar-minified-disabled');
// 		}
// 		$('#sidebar-minify i').removeClass('fa-list').addClass('fa-ellipsis-v');
// 	} else {
// 		if ( $('body').hasClass('sidebar-hidden-disabled') ) {
// 			$('body').removeClass('sidebar-hidden-disabled').addClass('sidebar-hidden');
// 		}
		
// 		if ( $('body').hasClass('sidebar-minified-disabled') ) {
// 			$('body').removeClass('sidebar-minified-disabled').addClass('sidebar-minified');
// 		}
// 	}

// 	if (winWidth > 768) {
// 		$('.main').css('min-height',winHeight-footerHeight);		
// 	}






// }