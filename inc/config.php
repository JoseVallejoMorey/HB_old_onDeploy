<?php

//archivo de configuracion inicial

// @package    EuropioEngine
// @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
// @author     Eugenia Bahit <ebahit@member.fsf.org>
// @link       http://www.europio.org
// @version    3.4.24



//[PATHS]
//SERVER_URI = http:${SERVER_NAME}       ; //hostname incluyendo protocolo
//APP_DIR = ${DOCUMENT_ROOT}/            ; //ruta física de la app
//STATIC_DIR = ${DOCUMENT_ROOT}/static/  ; //ruta física para archivos estáticos
//WRITABLE_DIR =                     ; //directorio con permisos de escritura
//WEB_DIR = ${PWD}                   ; //ruta relativa del directorio Web


//[APPLICATION]
//PRODUCTION = false                 ; //en producción establecer en true
//USE_PCRE = false                   ; //para evitar el uso de preg_match establecer en false
//API_ENABLED = false                ; //true para habilitar la API, false para deshabilitar
//DEFAULT_VIEW = /users/user/login   ; //ruta Web relativa de la vista por defecto
//AUTOLOAD_MODULE = true             ; //carga automática de módulos mediante el archivo __init__.php


//[GUI]
//CUSTOM_TEMPLATE =        ; ruta física del template HTML (nulo para el template por defecto)
//CUSTOM_LOGIN_TEMPLATE =  ; ruta física del login HTML (nulo para el template por defecto)


//[ERROR PAGES]
//HTTP_ERROR_403 =      ; //ruta física absoluta del archivo HTML para error 403
//HTTP_ERROR_403_API =  ; //ruta física absoluta del archivo HTML para error API no habilitada
//HTTP_ERROR_404 =      ; //ruta física absoluta del archivo HTML para error 404


//[SESSIONS]
//SESSION_LIFE_TIME = 1200      ; //tiempo máximo de inactividad (en segundos)
//SESSION_STRICT_LEVEL = false  ; //false para utilizar privilegios escalados

// $_POST['codestring'] = "ABC <script>bcd; <b>bb";
//         $_POST['codestring2strict'] = "ABC <script>bcd;</script> <b>bb";
//         $_POST['email'] = "eugenia@mimail.net";
//         $_POST['mail'] = "euge,nia<b>@mi'mail.net";
//         $_POST['float'] = "1278.45";
//         $_POST['floatthousand'] = "7,461.98";
//         $_POST['floatcolon'] = "989,33";
//         $_POST['integer'] = 123;
//         $_POST['opciones'] = array(15, 76, 191, 218);
//         $_POST['opciones_con_error'] = array('5manzanas', 'string', '28');
//         $_POST['nonascii'] = 'Ñandú';
//         $_POST['nonascii2'] = "Ñ'an<b>d\"ú</b>";
//         $_POST['telefono'] = "54365487698062";
//         $_POST['password'] = "Ja:C1n;T>0";



//pues listas blancas iran aqui
//este se quedo amedias


//'perfil_datos_empresa','perfil_datos_usuario',
//  'perfil_datos_particular',

//diccionario con datos validos provenientes de $_POST[form_to]
$permitidos['form_to'] = array('perfil_cambiar_contrasena','new_anuncio',
	'lista_fotos','idiomas_anuncio','anuncio_multi','nuevo_agente','nueva_oficina',
	'logo_empresa','empresa_fondo');

//(NO BORRAR)
//====================================================
//lista blanca para get
//para links : subtipo, poblacion // land_sub, land_cat
//para master: prince, validation, 
//edicion de links validos : link_operator, linkdel
//control de portal : secciones, directivas, sujeto
//control de landing : landing_esp, landing_sec
//validacion de mpresa : mpresa
//mantenimiento de paquetes : mantenimiento, paquete
//eliminando anuncios : controlator,
//paypal :payprocess, paycancel,
//paypal return : 'token','PayerID'

// $white_get = array('lg','pag','pagg','inmv','archivo','mn_nav','perfil','perfil_mda',
// 'pagg_inmo','accion','pagg_empresas','art','destroy','delimg','prince','validation','mpresa',
// 'subtipo','poblacion','land_sub','land_cat','link_operator','linkdel','secciones','directivas',
// 'sujeto','mantenimiento','paquete','controlator','payprocess','paycancel','token','PayerID',
// 'landing_sec','landing_esp');
//====================================================
//(NO BORRAR)


//[SECURITY LAYER]
define('SECURITY_LAYER_ENGINE' , 'On'); 	 			//off para desactivar la capa de seguridad
define('SECURITY_LAYER_STRICT_MODE' , true); 	 		//true para filtrar formularios con htmlentities y strip_tags
define('SECURITY_LAYER_SANITIZE_ARRAY' , false); 	 	//true para convertir a enteros los campos de selección múltiple
define('SECURITY_LAYER_ENCRYPT_PASSWORD' , false); 		//false para no encriptar campos de contraseña ni filtrarlos
define('SECURITY_LAYER_ENCRYPT_PASSWORD_HASH' , 'md5'); //Algoritmo de cifrado a utilizar solo si ENCRYPT_PASSWORD es true



$enabled_apps = array('securitylayer');
//[PLUGINS]
//collectorviewer  = true  ; common/plugins/collectorviewver
//securitylayer    = true  ; common/plugins/securitylayer
//webform          = true  ; common/plugins/webform



define('USER_MDA','105');
//links para anuncios
define('ANUNCIO_PASO_1' , '');
define('ANUNCIO_PASO_2' , 'index.php?perfil=4');
define('ANUNCIO_PASO_3' , 'index.php?perfil=3');
define('ANUNCIO_PASO_4' , 'index.php?perfil=1');


//PAGINACION
define('ANUNCIOS_POR_PAGINA' , 3);
define('ANUNCIOS_POR_PAGINA_EMPRESA' , 3);

//cada cuantos anuncios saldra banner central, //modulos 334

?>