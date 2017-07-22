<?php

//configuracion 
include 'inc/vars.php';		//variables basicas del sitio
include 'inc/config.php';	//config

//seguridad de la aplicacion
include 'inc/clases/seg/securitylayer.class.php'; //capa de seguridad para filtrar post
include 'inc/clases/seg/seg_inquisitor.php';		  //filtra listas negras
include 'inc/funciones/fun_session.php';			    //funciones de inicio y cierre de session
include 'inc/funciones/fun_seg.php';				      //funciones de seguridad

//arreglando asi para MAMP
error_reporting(6143);
ini_set('display_errors', 'on');




//var_dump($_SESSION);

require 'inc/clases/mysqlidb.main.php';     //clase dios
require 'inc/clases/seg/seg_sesion.class.php';  //seg sessions class


//==========configuracion del sitio================
require 'inc/clases/config/show_config.class.php';    //modifica aspectos segun seccion de pagina
$Config = new builder_config();
// if (!empty($_SERVER['HTTP_REFERER'])) {
//   $Config->url = $_SERVER['HTTP_REFERER'];
// }
// var_dump($Config->idioma);
// var_dump($_SESSION['lg_cambio']);
require 'inc/control/session_control.php';    //sessiones, fltrar, dstroy, login_check, limpieza sessiones
include 'inc/funciones/fun_general.php';


//==================idioma=========================
//require 'inc/select_idioma.php';            //idioma se la aplicacion
//hay que verificar que el archivo este
require 'inc/lenguajes/lang_'.$_SESSION['lg'].'.php'; //include el archivo de idioma elegido

include 'inc/clases/paginate.php';  //paginacion para las busquedas
//var_dump($_SESSION);

//1- hay post
//==========================================================
if(!empty($_POST)) {
// var_dump($_POST);
// die();
  //todo lo relativo a anuncios y perfil de usuario
  if(!empty($_POST['form_to'])){    require 'inc/sql/sql_anuncios.php'; }
  //relativo a tienda
  if(!empty($_POST['tienda'])){   require 'process.php';  }
  //log, reg, lost y rew
  if(!empty($_POST['act'])){      require 'inc/sql/sql_acciones.php'; }
  //alertas y especializaciones
  if(!empty($_POST['alerta'])){   require 'inc/sql/sql_alert.php';  }
  //acciones de admin de la pagina
  if(!empty($_POST['mda_control'])){  require 'inc/sql/sql_mda.php';    }

}

//estos entran para perfil y pagina publica
//necesito una direccion a la que paypal me remita

//2-Perfil usuario
//==========================================================
if((!empty($_GET['perfil'])) || (!empty($_GET['perfil_mda'])) || (!empty($_GET['accion']))){

  require_once 'inc/clases/alertas/alertas_users.class.php';  //funciones alertas

  //si es perfil o perfil_mda y no existe session lo mando a loguearse
  if( ((!empty($_GET['perfil'])) || 
    (!empty($_GET['perfil_mda']))) && 
    (empty($_SESSION['user_id'])) ){

    //header('location: index.php?accion=log'); 
  }


  if( (!empty($_GET['perfil_mda'])) && (!empty($_SESSION['mda_id']))){
    require_once 'inc/clases/procesos/sql_operations.class.php'; 
    require_once 'inc/clases/procesos/master.class.php';
    require 'inc/control/mda_control.php';
  }

  if(!empty($_GET['accion'])){
    //require 'inc/control/accion_control.php'; //perfil acciones redirecciones(delete img, alertas)
  }

  if(!empty($_GET['perfil'])){
    require 'inc/control/perfil_control.php'; //perfil acciones redirecciones(delete img, alertas)
  }

  // if(!empty($_GET['payprocess'])){
  //  //este sera el retorno de paypal process
  //  require 'process.php';
  // }

  // if(!empty($_GET['paycancel'])){
  //  //este sera el retorno de paypal cancelado
  //  require 'process.php';
  // }  
      
}





//
//aqui tiene que saberlo ya todo
// debe conocer seccion y si dicha seccion dara resultado
// ejemplo anuncio numero 77
// sabe que seccion es anuncio pero anuncio 77 esta o sera 404?
//var_dump($_SESSION);
//var_dump($Config->seccion);



//integrando nuevo panel de admin y nuevo access

//en lugar de partir tres index para portal, landing y admin
//llegare hsta aqui y pues segun sea cargare uno u otro, 
//con su html entero , includes de headers css js



//sacara public, admin, access o landing
$Config->cargando_pagina();


?>
