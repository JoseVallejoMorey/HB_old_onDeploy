<?php
//version final 19/10/14

//var_dump(scandir('inc/clases'));
//include_once 'inc/clases/builders.class.php';			//clase builder
include_once 'inc/clases/procesos/fechas.class.php';	//clase fechas
//include_once 'inc/clases/uploadimg.class.php';

class Tienda extends reserva_builder{

	public $last_id;
	public $seccion;
	public $periodo;
	public $fecha_inicio;
	public $fecha_final;
	public $paquete_tipo;
	public $renew;
	public $rebuy;
	public $anuncio;
	public $provincia;
	public $tipo_bann;
	public $num_pedido;
	public $datos_pedido;
	public $tipo_bann_nuevo;
	public $nuevo_bann_data = array();
	public $traduccion;
	// de paypal para todos
	private $paytransid;
	private $payerid;
	private $paytoken;
	// objetos

	public $Fechas;		//fechas
	public $Perfil;		//objeto seg_builder (para estadisticas)
	public $Usuario;	//objeto builder()


	public function __construct(){

		$this->user = $_SESSION['user_id'];

		parent::__construct();					//parent constructor
		$this->iniciando_vars_special();		//vars de special_area
		$this->iniciando_vars_star();			//vars de star_area
		$this->iniciando_vars_paqueteria();		//vars de paqueteria
		$this->iniciando_vars_promo();			//vars de promo
		$this->iniciando_vars_banners();		//vars de banners
		$this->iniciando_vars_traducciones();	//vars de traducciones
		$this->iniciando_vars_paypal();			//vars de paypal

		$this->Fechas  = new Fechas();			//objeto Fechas
		$this->Perfil  = new seg_builder();		//objeto Seg_builder
		$this->Usuario = new builders();		//objeto builder
	}



//inicia vars de special area ===============
	private function iniciando_vars_special(){
		if(!empty($_SESSION['datapay']['seccion'])){		
			$this->seccion 		= $_SESSION['datapay']['seccion'];  	}
		if(!empty($_SESSION['datapay']['periodo'])){		
			$this->periodo  	= $_SESSION['datapay']['periodo'];  	}
		if(!empty($_SESSION['datapay']['fecha_inicio'])){	
			$this->fecha_inicio = $_SESSION['datapay']['fecha_inicio']; }
		if(!empty($_SESSION['datapay']['fecha_final'])){	
			$this->fecha_final  = $_SESSION['datapay']['fecha_final'];  }
	}

	//inicia vars de star area ===============
	private function iniciando_vars_star(){
		if(!empty($_SESSION['datapay']['anuncio'])){		
			$this->anuncio 		= $_SESSION['datapay']['anuncio'];	}
		if(!empty($_SESSION['datapay']['zona'])){			
			$this->provincia 	= $_SESSION['datapay']['zona'];		}
	}

	//inicia vars de paqueteria===============
	private function iniciando_vars_paqueteria(){
		if(!empty($_SESSION['datapay']['paquete'])){		
			$this->paquete_tipo = $_SESSION['datapay']['paquete'];	}
		if(!empty($_SESSION['datapay']['renew'])){		
			$this->renew 		= $_SESSION['datapay']['renew'];	}
		if(!empty($_SESSION['datapay']['rebuy'])){		
			$this->rebuy 		= $_SESSION['datapay']['rebuy'];	}
	}

	//inicia vars de banners===============
	private function iniciando_vars_banners(){
		if(!empty($_SESSION['datapay']['datos_pedido'])){		
			$this->datos_pedido 	= $_SESSION['datapay']['datos_pedido'];		}			
		if(!empty($_SESSION['datapay']['tipo_bann'])){		
			$this->tipo_bann 		= $_SESSION['datapay']['tipo_bann'];		}
		if(!empty($_SESSION['datapay']['tipo_bann_nuevo'])){	
			$this->tipo_bann_nuevo 	= $_SESSION['datapay']['tipo_bann_nuevo'];	
		}
	}

	private function iniciando_vars_promo(){
		if(!empty($_SESSION['datapay']['promocionar'])){		
			$this->anuncio 	= $_SESSION['datapay']['promocionar'];		}
	}

	//inicia vars de traducciones===============
	private function iniciando_vars_traducciones(){
		if(!empty($_SESSION['datapay']['tradd'])){	
			$this->traduccion = array();	
			$this->traduccion = $_SESSION['datapay']['tradd'];	}
	}

	//inicia vars de paypal===============
	private function iniciando_vars_paypal(){
		if(!empty($_SESSION['datapay']['transid'])){		
			$this->paytransid 	= $_SESSION['datapay']['transid'];		}
		if(!empty($_SESSION['datapay']['payerid'])){		
			$this->payerid		= $_SESSION['datapay']['payerid'];		}
		if(!empty($_SESSION['datapay']['token'])){		
			$this->paytoken 	= $_SESSION['datapay']['token'];		}
		if(!empty($_SESSION['itempay']['ItemNumber'])){
			$this->producto = $_SESSION['itempay']['ItemNumber'];		}
	}



	//compra de productos
	//===============================================================
	//compra de un paquete de anuncios. funciona pasandole el tipo de paquete (1,5,10,20)
	public function buy_paqueteria(){
		// var_dump('que pasa?');
		// var_dump($this->producto);
		$cols = array('id','nombre','precio','descripcion','cantidad','duracion');
		$this->where('id',$this->producto);
		$salida = $this->getOne('productos',$cols);
		$this->paquete_tipo = $salida['cantidad'];
		$this->periodo 		= $salida['duracion'];

		$nuevafecha = strtotime ( '+'.$this->periodo.' month' , strtotime ( $this->date ) ) ;
		$nuevafecha = date ( 'Y-m-j' , $nuevafecha );

		$cols = array('user' 		 	=> $this->user,
					  'producto'		=> $this->producto,
					  'paquete' 	 	=> $this->paquete_tipo,
					  'fecha_inicio' 	=> $this->date,
					  'fecha_final'  	=> $nuevafecha,
					  'duracion'	 	=> $this->periodo,
					  'duracion_total'	=> $this->periodo,
					  'full' 		 	=> '0',
					  'estado'			=> '1');

		if($this->last_id = $this->insert('paquetes ', $cols)){
			$actividad = 'Ha comprado un paquete de '.$this->paquete_tipo;
			$this->Perfil->movimientoStats($actividad);
			if($this->guardar_paquete_efectivo('compra') == true){
				var_dump('si pasa');
				//var_dump($this);
			}else{
				var_dump('no pasa');
			}
			//y a pagina de agradecimiento
		}else{
			var_dump($this);
		}
	}

	//renovar paquete existente
	public function renew_paquete(){
		$this->where('id',$this->renew);
		if($salida = $this->getOne('paquetes')){
			//preparacion de variables
			$nuevafecha = strtotime ('+'.$salida['duracion'].' month',strtotime($salida['fecha_final']));
			$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
			$nueva_duracion_total = $salida['duracion_total'] + $salida['duracion']; 
			$campos = array('fecha_final' 	 =>$nuevafecha,
							'duracion_total' =>$nueva_duracion_total);
			//asigno para paquete_efectibo

			$this->last_id 		= $salida['id'];
			$this->periodo 		= $salida['duracion'];
			$this->paquete_tipo = $salida['paquete'];
			//registro en tablas
			$this->where('id',$this->renew);
			if($this->update('paquetes',$campos)){
				$actividad = 'Ha renovado paquete '.$salida['id'];
				$this->Perfil->movimientoStats($actividad);
				$this->guardar_paquete_efectivo('renew');
				//a pagina de agradecimiento
			}
		}
	}

	//recomprar un paquete que ha caducado
	//===================================================
	public function rebuy_paquete(){
		$this->where('id',$this->rebuy);
		if($salida = $this->getOne('paquetes')){
			//preparacion de variables
			$nuevafecha = strtotime ('+'.$salida['duracion'].' month' , strtotime ($this->date));
			$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
			$campos = array('fecha_inicio'	 =>$this->date,			'fecha_final'  	=>$nuevafecha,
							'duracion_total' =>$salida['duracion'],	'estado' 		=>'');
			//asigno para paquete_efectibo
			$this->last_id 		= $salida['id'];
			$this->periodo 		= $salida['duracion'];
			$this->paquete_tipo = $salida['paquete'];
			//registro en tablas
			$this->where('id',$this->rebuy);
			if($this->update('paquetes',$campos)){

				$actividad = 'Ha recomprado paquete '.$salida['id'].' caducado';
				$this->Perfil->movimientoStats($actividad); //guard actividad del user
				$this->guardar_paquete_efectivo('rebuy');			//guardamos tiket
				//activamos anuncios del paquete
				$this->Usuario->where('paquete',$this->rebuy);
				$this->Usuario->where('ussr',$this->user);
				if($salida = $this->Usuario->get('anuncios')){
					foreach ($salida as $key => $value) {
						$this->Usuario->check_anuncio_apto($value['id']);
					}
				}
				//pagina de agradecimiento
			}
		}		
	}

	//promocionar anuncios
	public function buy_promo(){

		if (is_array($this->anuncio)){
			$this->fecha_final = $this->Fechas->periodo($this->date, $this->periodo);
			if($this->guardar_promo_efectiva() == true){
				foreach ($this->anuncio as $key => $value){
					//aqui meteremos tambien fecha de inicio y fecha final
					$anuncio = $this->Usuario->resolver_enigma($value);
					$cols = array('anuncio_promocionado' => '1',
								  'promo_fecha_inicio' => $this->date,
								  'promo_fecha_final' => $this->fecha_final);
					$this->Usuario->where('id',$anuncio);
					$this->Usuario->update('anuncios',$cols);
				}
			}
		}
		$this->Perfil->movimientoStats('Ha promocionado anuncios');
		//pagina de agradecimiento
	}

	//comprea de traducciones
	//========================================
	public function buy_tradd(){
		//primero guardar en reservas efectivas
		if($this->guardar_efectiva('traduccion') == true){
			$this->save_tradd_pedido();	//guardo el pedido
			$this->Perfil->movimientoStats('Compra traduccion de anuncios');
		}
	}

	//guardar cada anuncio/idioma a traducir
	//=====================================
	private function save_tradd_pedido(){

		foreach ($this->traduccion as $key => $value) {
			$e = explode('_', $value);
			$coleccion[$e[0]]=$e[1];
		}
		//por cada anuncio/idioma una fila
		foreach ($coleccion as $key => $value) {
			$cols = array('user'	=>$this->user,
						  'anuncio'	=>$key,
						  'idioma'	=>$value,
						  'estado'	=>'pendiente',
						  'num_pedido'	=>$this->last_id,
						  'fecha_pedido'=>$this->now);
			$this->insert('traducciones_pedidos',$cols);
		}
	}


	
	//comprobando siempre que no sea repeticion, guardara compra en reservas 
	//efectivas, y en su tabla correspondiente, ultima actividad de usuario
	public function buy_special(){
		//var_dump('es special');

		$tabla = 'reserva_special_'.$this->seccion;
		if($this->guardar_efectiva('special') == true){		
			$fechas = $this->reservar_fechas($this->fecha_inicio);
			$this->reservar_compra($fechas, $tabla);
			$this->is_full_fila($tabla);
			$this->Perfil->movimientoStats('Comprado special_area');	
		}
	}

	//comprobando siempre que no sea repeticion, guardara compra en reservas 
	//efectivas, y en su tabla correspondiente, ultima actividad de usuario
	public function buy_star(){
		
		//como recibiremos el anuncio en bruto hay que resolverlo
		$this->anuncio = $this->Usuario->resolver_enigma($this->anuncio);
		$tabla = 'reserva_star_'.$this->provincia;
		if($this->guardar_efectiva('star_area') == true){
			$this->update_star_anuncio();
			$fechas = $this->reservar_fechas($this->fecha_inicio);
			$this->reservar_compra($fechas, $tabla);			
			$this->is_full_fila($tabla);
			$this->Perfil->movimientoStats('Comprado star_area');
		}
	}

	//comprobando siempre que no sea repeticion, guardara compra en reservas 
	//efectivas, y en su tabla correspondiente, ultima actividad de usuario
	public function buy_banner(){
  
		if($this->guardar_efectiva('banner') == true){
			
			//si es banner nuevo hay que hacer el pedido
			if(!is_null($this->tipo_bann_nuevo)){
			 	$this->tipo_bann = $this->tipo_bann_nuevo;
			 }
			//actualizar la reserva
			 if(!is_null($this->datos_pedido)){
			 	$this->confirmar_pedido();
			 }

			$tabla  = 'reserva_banners_'.$this->tipo_bann;	
			$fechas = $this->reservar_fechas($this->fecha_inicio);
			$this->reservar_compra($fechas, $tabla);				
			$this->is_full_fila($tabla);
			$this->Perfil->movimientoStats('Ha pedido un banner');				
		}
	}


	//guardar info de reserva efectiva
	//======================================================

	private function guardar_efectiva($reserva){

		if(!is_null($this->traduccion)){		$especificacion = count($this->traduccion);	}
		if(!is_null($this->tipo_bann)){			$especificacion = $this->tipo_bann ;		}
		if(!is_null($this->tipo_bann_nuevo)){	$especificacion = $this->tipo_bann_nuevo ;	}
		if(!is_null($this->seccion)){			$especificacion = $this->seccion ;			}
		if(!is_null($this->provincia)){			$especificacion = $this->provincia ;		}

		//var_dump($this);
		//si los siguientes son nulos
		if(is_null($this->fecha_inicio)){$this->fecha_inicio = '';	}
		if(is_null($this->fecha_final)){ $this->fecha_final  = '';	}
		if(is_null($this->anuncio)){	$this->anuncio  = 0;		}

		//esto indica si la compra esta ya activa
		if($this->fecha_inicio == $this->date){$estado  = 1;}else{$estado = 0;	}
		
		
		//ahora buscaremos si existe $this->payerid con $this->paytoken
		$this->where('payerid',$this->payerid);
		$this->where('token',$this->paytoken);
		if($this->get('reservas_efectivas')){
			//var_dump('no guardar nada');
			//si hay no guardara nada
			return false;
		}else{
			//var_dump('guardar info');
			//no hay coincidencia lo que debe ser lo normal
			$cols = array('user' 	 => $this->user,	 
						  'reserva'  => $reserva,
						  'estado'	 => $estado,
						  'anuncio'  => $this->anuncio,		
						  'token'	 => $this->paytoken,
						  'transid'	 => $this->paytransid, 
						  'payerid'	 => $this->payerid,
						  'producto' => $this->producto,
						  'fecha_final' 	=> $this->fecha_final, 
						  'fecha_inicio' 	=> $this->fecha_inicio,  
						  'especificacion' 	=> $especificacion);
			
			if($this->last_id = $this->insert('reservas_efectivas',$cols)){
				return true;
			}
		}
	}


	//guardar info de paquete (alomejor para factura)
	//===================================================
	private function guardar_paquete_efectivo($operacion){

		$cols = array('paquete_id' 	=> $this->last_id,
					  'fecha_accion'=> $this->date,
					  'anuncios' 	=> $this->paquete_tipo,
					  'periodo' 	=> $this->periodo,
					  'token'	 	=> $this->paytoken,
					  'transid'	 	=> $this->paytransid, 
					  'payerid'	 	=> $this->payerid,
					  'producto' 	=> $this->producto,					  
					  'operacion' 	=> $operacion);
		if($this->insert('paquetes_efectivos',$cols)){
			return true;
		}
	}


	private function guardar_promo_efectiva(){

		//ahora buscaremos si existe $this->payerid con $this->paytoken
		$this->where('payerid',$this->payerid);
		$this->where('token',$this->paytoken);
		if($this->get('reservas_efectivas')){
			//si hay no guardara nada
			return false;
		}else{
			$especificacion = count($this->anuncio);
			//var_dump('guardar info');
			//no hay coincidencia lo que debe ser lo normal
			$cols = array('user' 	 => $this->user,	 
						  'reserva'  => 'promo',
						  'estado'	 => 1,	
						  'token'	 => $this->paytoken,
						  'transid'	 => $this->paytransid, 
						  'payerid'	 => $this->payerid,
						  'producto' => $this->producto,
						  'fecha_final' 	=> $this->fecha_final, 
						  'fecha_inicio' 	=> $this->date,  
						  'especificacion' 	=> $especificacion);
			
			if($this->last_id = $this->insert('reservas_efectivas',$cols)){
				return true;
			}
		}

	}




	//realiza el pedido de un banner a fabrica (nueva)
	//===============================================================
	public function banner_pedido(){
		//dia anterior a la fecha de inicio

		$fechitas = explode("-",$this->fecha_inicio);
		$fecha_limite  = date("Y-m-d", mktime(0, 0, 0, $fechitas['1'], $fechitas['2']-1, $fechitas['0']));	
		$cols = array('user' 	=> $this->user,
					  'tipo' 	=> $this->tipo_bann_nuevo,
					  'imagen' 	=> $this->nuevo_bann_data['imagen'],
					  'texto' 	=> $this->nuevo_bann_data['texto_bann'],
					  'confirmacion'	=> 'Pendiente',
					  'fecha_pedido'	=> $this->now,
					  'fecha_limite' 	=> $fecha_limite,
					  'caracteristicas' => $this->nuevo_bann_data['detalles_bann']);

		if($last_id = $this->insert('banners_pedido',$cols)){
			return $last_id;
		}else{
			//var_dump($cols);
			return false;
		}
	}

	//confirmacion del pedido una vez pagado
	//========================================
	private function confirmar_pedido(){
		//var_dump($this->num_pedido);
		$cols = array('confirmacion' => 'Pagado',
					  'num_pedido' 	 => $this->last_id);
		$this->where('id',$this->datos_pedido);
		if($this->update('banners_pedido',$cols)){
			//var_dump('actualiza');
		}
	}

	//actualiza en tabla anuncios, ese anuncio es estrella (anuncio_e)
	private function update_star_anuncio(){
		
		$cols = array('anuncio_e' => '1');
		$this->Usuario->where('id', $this->anuncio);
		$this->Usuario->update('anuncios', $cols);
	}


	//devuelve un array con las fechas reservadas
	private function reservar_fechas($finicio){
		$fechitas = explode("-", $finicio);
		for($i=0; $this->periodo > $i ;$i++){
			//agrego cada dia a un array
			$fechas[$i]= date("Y-m-j", mktime(0, 0, 0, $fechitas['1'], $fechitas['2']+$i, $fechitas['0']));
		}
		return $fechas;
	}


	//devolvera el primer campo disponible 
	//============================================================
	private function comprobar_critico($array,$tabla){
		//solo debe recibir los campos a comparar
		foreach ($array as $key => $value) {
			unset($value['full']);
			foreach ($value as $key2 => $value2) {
				if($value2 == 0){	return $key2;	}
			}
		}
	}



	//reservar special, star y banners
	//ya recibe una fecha correcta ( libre y no ocupada por el usuario )
	//========================================================================
	private function reservar_compra($fechas, $tabla){
		//si hay anuncio reg anuncio sino reg user
		if($this->anuncio != ''){	$reg = $this->anuncio;
		}else{						$reg = $this->user;		}
		// var_dump($reg);
		// die;
		foreach($fechas as $value){
			$this->where('date',$value);
			if($salida = $this->get($tabla)){
				//si existe el dia busca campo libre
				$campo_libre = $this->comprobar_critico($salida,$tabla);
				$cols = array($campo_libre => $reg);
				$this->where('date',$value);
				$this->update($tabla,$cols);
			}else{
				//si no existe aun el dia hay que crearlo, y reservar uno
				$this->reservar_siguiente_dia($value, $tabla, $reg, true);
			}
		}//foreach por cada dia que tengo
	}//fin de reservar especial


	//filas sin hueco seran actualizadas a full
	//=======================================================
	private function is_full_fila($tabla){
		$campos = $this->campos_correspondientes($tabla);
		array_push($campos,'date');
		$zeros = array();
		$this->where('full',0);
		//sacamos todas las reservas de la tabla
		if($salida = $this->get($tabla,NULL,$campos)){
			foreach ($salida as $key => $value) {
				//guardamos fecha y comprobamos cuantos huecos hay por fila
				$fecha = $value['date'];
				unset($value['date']);
				$zeros[$fecha]= 0;

				foreach ($value as $key2 => $value2) {
					if($value2 == 0){$zeros[$fecha]++;}
				}
			}

			//si no quedan huecos en fila actualizamos a full
			foreach ($zeros as $key => $value) {
				if($value == 0){
					//var_dump('esta full');
					$cols = array('full' =>'1');
					$this->where('date',$key);
					$this->update($tabla,$cols);
				}
			}
		}
		//var_dump('no hubo resultado en tabla');
	}






}//fin de la clase

?>