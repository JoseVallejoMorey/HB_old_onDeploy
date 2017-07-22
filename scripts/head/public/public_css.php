<!--pasando a bootstrap 3.0-->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" media="screen">
<!-- estos iconos si que los quiero -->
<link href="assets/css/admin/font-awesome.min.css" rel="stylesheet">
<!-- css del portal -->
<link href="assets/css/new_style.css" rel="stylesheet">
<!-- responsive -->
<link rel="stylesheet" href="assets/css/style_responsive.css">


<?php

//style galeria en pag
if(!empty($_GET['pag'])){
	echo '<link rel="stylesheet" type="text/css" href="assets/css/gallery-style.css">';
}

//css para promo
$estado = $Config->show_section();
if($estado == 'promo'){
	echo '<link rel="stylesheet" type="text/css" href="assets/css/promo.css">';
}
?>