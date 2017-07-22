<?php
//funciones de creacion y destruccion de session
//===============================================

//sessiones mas seguras, iniciar funcon en cada pagina donde utilice sessiones
//=============================================================================
function sec_session_start() {
        $session_name = 'sec_session_id'; //Configura un nombre de sesión personalizado
        $secure = false; //Configura en verdadero (true) si utilizas https
        $httponly = true; //Esto impide a javascript ser capaz de accesar la identificación de la sesión.
        ini_set('session.use_only_cookies', 1); //Forza a las sesiones a sólo utilizar cookies.
        ini_set('session.use_strict_mode', 1);
        $cookieParams = session_get_cookie_params(); //Obtén params de cookies actuales.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
        session_name($session_name); //Configura el nombre de sesión a el configurado arriba.
        session_start(); //Inicia la sesión php
        session_regenerate_id(false); //Regenera la sesión, (false) para borrar la previa.

}

//funcion creada de cerrar session
//===============================================
function cerrar_sesion(){
	//Desconfigura todos los valores de sesión
	$_SESSION = array();
	//Obtén parámetros de sesión
	$params = session_get_cookie_params();
	//Borra la cookie actual
	setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	//Destruye sesión
	session_destroy();
	header('Location: index.php');
}

//inicio de session
//===============================================
sec_session_start();

?>