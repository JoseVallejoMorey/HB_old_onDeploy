
$(document).ready(function(){

	       
// 	function navDistance(id){
// 		var anchura = $(window).width();
// 		var anchunav = $(id).outerWidth();
// 		var distancia = (anchura - anchunav) / 2;
// 		$(id).css({'left':distancia+'px'});
				
// 		if(anchura<992){
// 			//alert('menor');
// 			$('.ini').css({'width':'100%'});
// 			$('#navbar').css({'top':'0'});
					
// 		}else{
// 			//alert('mayor');	
// 			$('.ini').css({'width':'80%'});
// 			$('#navbar').css({'top':'50px'});
// 		}
				
// 	}
		   
		  
// 	//aver carrusel
// 	$('.carousel').carousel();

		   
// 	//alert(distancia);
		   
// 	//var altura = '900px';
// 	var $body   = $(document.body);
// 	navDistance('#navbar');
// 	navDistance('#socialbar');
			
// 	//$('.section').css({'height':altura});
// 	$('#navbar a').bind('click',function(event){
//         var page = $(this).attr('href');
// 		var refPage = $(this).attr('href');
//         var posTopPage = $(refPage).offset().top;
                    
//         $('html, body').stop();
//         $('html, body').animate({scrollTop: posTopPage}, 1500,'easeInOutExpo');
       
//         event.preventDefault();
//     });
			
			
// 	$(window).resize(function(){	
// 		navDistance('#navbar');
// 		navDistance('#socialbar');		
// 	});
	       

// // var landing_move = function(dir){
// // 	alert(dir);
// // }
// //para sliders de landing

// var landing_anchura = $('.indicators').outerWidth();
// //alert(landing_anchura);
// var right_position = landing_anchura - 80;
// //alert(right_position);
// $('.arrow_right').css({'left':right_position+'px'});


// // $('.arrow_right').hover(landing_move('right'));
// // $('.arrow_left').hover(landing_move('left'));

// $('.arrow_right').on ('mouseenter',landing_move('right'));
// $('.arrow_left').on ('mouseenter',landing_move('left'));

// $('.arrow_right').mouseenter(function(){});
// $('.arrow_left').mouseenter(function(){});	       



//FONDO DE FORMULARIOS
$('#promo_form').backstretch([
  	"assets/img/lockscreen/1.jpg",
    "assets/img/lockscreen/2.jpg",
	"assets/img/lockscreen/3.jpg"
], {
    fade: 1000,
    duration: 9000
});


//slider de empresas
//$("#partners").owlCarousel();
  //Sort random function
  function random(owlSelector){
    owlSelector.children().sort(function(){
        return Math.round(Math.random()) - 0.5;
    }).each(function(){
      $(this).appendTo(owlSelector);
    });
  }
 
  $("#promo-slider").owlCarousel({
    navigation: true,
    scrollPerPage:true,
    slideSpeed:1600,
    paginationSpeed:2000,
    stopOnHover:true,
    rewindSpeed:3000,
      autoPlay: 5000, //Set AutoPlay to 3 seconds
      items : 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,3], 
    navigationText: [
      "<i class='icon-chevron-left icon-white'></i>",
      "<i class='icon-chevron-right icon-white'></i>"
      ],
    beforeInit : function(elem){
      //Parameter elem pointing to $("#owl-demo")
      random(elem);
    }
 
  });

  // $("#promo-slider").owlCarousel({
 
  //     autoPlay: 3000, //Set AutoPlay to 3 seconds
 	// 		pagination : true,
  //     items : 4,
  //     itemsDesktop : [1199,3],
  //     itemsDesktopSmall : [979,3]
 
  // });


});