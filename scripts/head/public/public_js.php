<!-- start: JavaScript-->

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!--[if !IE]>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
<![endif]-->

<!--[if IE]>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
	<script src="assets/js/admin/jquery-migrate-1.2.1.min.js"></script>
<![endif]-->



<!-- fijos -->
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/sha512.js"></script>
<script src="assets/js/js_funciones/funciones_js.js"></script>


<!-- new template -->
<script src="assets/js/imagesloaded.pkgd.min.js"></script>
<script src="assets/js/masonry.pkgd.min.js"></script>


<script src="assets/js/jQuery.resizeEnd.js"></script>

<script src="assets/js/core_public.js"></script>
<script src="assets/js/con_ajax.js"></script>


<?php

$estado = $Config->show_section();
if($estado == 'promo'){
  echo '<script src="assets/plugins/backstretch/jquery.backstretch.min.js"></script>';
  echo '<script src="assets/js/owl.carousel.min.js"></script>';
  echo '<script src="assets/js/promo.js"></script>';
}



if(!empty($_GET['pag'])){
	echo '<script type="text/javascript" src="assets/plugins/jssor/jssor.js"></script>';
	echo '<script type="text/javascript" src="assets/plugins/jssor/jssor.slider.min.js"></script>';
	echo '<script type="text/javascript" src="assets/plugins/jssor/gallery-jassor.js"></script>';

}	
?>