<?php


//clase que obtiene y contiene informacion de los productos, hallados en db
include_once 'reserva_builder.class.php';
//require_once 'inc/clases/reserva/reserva_builder.class.php';
class productos extends reserva_builder{

	public function __construct(){
		parent::__construct();
	}


	//obtiene precio del producto mediante tipo y duracion
	public function get_price($tipo, $duracion){
		$this->where('tipo',$tipo);
		$this->where('duracion',$duracion);
		$salida = $this->getOne('productos','precio');
		return $salida['precio'];
	}

	//obtiene informacion del producto mediante tipo y duracion
	//(id, nombre, precio, descripcion)
	public function get_product($tipo, $duracion){
		$cols = array('id','nombre','precio','descripcion','cantidad','duracion');
		$this->where('tipo',$tipo);
		$this->where('duracion',$duracion);
		$salida = $this->getOne('productos',$cols);
		return $salida;
	}

	//obtiene un producto de tipo banner
	public function get_banner($tipo,$duracion){
		$rest = substr($tipo, 0, 3); // devuelve tres letras
		$producto = 'bann_'.$rest.'_'.$duracion; //nombre de producto
		$cols = array('id','nombre','precio','descripcion','cantidad','duracion');
		$this->where('nombre',$producto);
		$salida = $this->getOne('productos',$cols);
		return $salida;
	}

	//datos de producto paquete (param nombre o id)
	public function get_paquete($tipo,$param = null){
		if(is_null($param)){$param = 'nombre';}
		$cols = array('id','nombre','precio','descripcion','cantidad','duracion');
		$this->where($param,$tipo);
		$salida = $this->getOne('productos',$cols);
		return $salida;
	}

	//datos de producto promocion
	public function get_promo($cantidad, $duracion){
		$producto = 'promo'.$cantidad.'_'.$duracion; //nombre de producto
		$cols = array('id','nombre','precio','descripcion','cantidad','duracion');
		$this->where('nombre',$producto);
		$salida = $this->getOne('productos',$cols);
		return $salida;
	}

	public function get_sumable($tipo){
		//tipo dira si es traduccion o promocion
		$posibles = array('traduccion','promocion');
		if(in_array($tipo, $posibles)){
			$cols = array('id','nombre','precio','descripcion','cantidad','duracion');
			$this->where('nombre',$tipo);
			$salida = $this->getOne('productos',$cols);
			return $salida;
		}else{
			return 'Producto no valido';
		}
	}

	public function get_producto_by_id($producto){
		$cols = array('nombre','precio','descripcion');
		$this->where('id',$producto);
		if($salida = $this->getOne('productos',$cols)){
			return $salida;
		}else{
			return false;
		}
	}


	//añadimos solo en numero de producto que el user quiere adquirir
	public function inputs_for_paypal($producto, $cant = NULL){
		//$return = '';
		//$return .='<input type="hidden" name="itemname"   value="'.$producto['nombre'].'" />';
		//$return .='<input type="hidden" name="itemprice"  value="'.$producto['precio'].'" />';
		$return ='<input type="hidden" name="itemnumber" value="'.$producto['id'].'" />';
		//$return .='<input type="hidden" name="itemdesc"   value="'.$producto['descripcion'].'" />';
		if(!is_null($cant)){
			$return .='<input type="hidden" name="itemQty"   value="'.$cant.'" />';
		}
		return $return;
	}

	//compra de banner nuevo, devuelve la info
	public function newbann_for_paypal(){
		$cols = array('id','nombre','precio','descripcion');
		$this->where('nombre','bann_new');
		$producto = $this->getOne('productos',$cols);
		$return = '&L_PAYMENTREQUEST_0_NAME1='.urlencode($producto['nombre']).
                  '&L_PAYMENTREQUEST_0_NUMBER1='.urlencode($producto['id']).
                  '&L_PAYMENTREQUEST_0_DESC1='.urlencode($producto['descripcion']).
                  '&L_PAYMENTREQUEST_0_AMT1='.urlencode($producto['precio']).
                  '&L_PAYMENTREQUEST_0_QTY1=1';
		return $return;
	}


	//es un banner nuevo hay que guardar datos para despues
	public function es_banner_nuevo($Tienda, $Upimg, $ItemTotalPrice){

		$newbann = array();
        $num_pedido 		 = false;
        $newbann['argument'] = $this->newbann_for_paypal();
        $newbann['producto'] = $this->get_product('banner',0);
        $newbann['totalprice'] = $ItemTotalPrice+$newbann['producto']['precio']; 	
        //$ItemTotalPrice 	 = $ItemTotalPrice+$newbann['producto']['precio'];

        //como es nuevo hay que realizar el pedido (subiendo imagenes), antes de
        //pasar datos a paypal, despues la confirmaremos o descartaremos 
        if($_FILES) {
            //verificamos si sube imaquen que lo sea
            $imagenes = $Upimg->check_if_files('banners_pedido');
            $Tienda->nuevo_bann_data['imagen'] = $imagenes['imagen'];

            if(isset($_POST['tipo_bann_nuevo'])){ 
                $Tienda->tipo_bann_nuevo = $_POST['tipo_bann_nuevo']; }
            if(isset($_POST['fecha_inicio'])){    
                $Tienda->fecha_inicio = $_POST['fecha_inicio']; }               
            if(isset($_POST['texto_bann'])){
                $Tienda->nuevo_bann_data['texto_bann']    = $_POST['texto_bann'];       }
            if(isset($_POST['detalles_bann'])){
                $Tienda->nuevo_bann_data['detalles_bann'] = $_POST['detalles_bann'];    }

            $datos_pedido = $Tienda->banner_pedido();                      
        }else{
            var_dump('no file');
        }

        if(is_numeric($datos_pedido)){
            $_SESSION['datapay']['datos_pedido'] = $datos_pedido; //contiene numero de pedido
        }
        //en este punto debera regisrar el pedido (como pendiente de resolucion)
        //si finalmente el producto es adquirido se actualizara dicha fila con pagado
        //si se sabe cancelada, con cancelado. solo realizaremos las pagadas

        return $newbann;

	}


	//segun producto continua como corresponda
	public function tienda_correspondiente($Tienda){

        //special area          
        if($_SESSION['datapay']['tienda'] == 'special_area'){
            $Tienda->buy_special();
        //star area     
        }else if($_SESSION['datapay']['tienda'] == 'star_area'){
            $Tienda->buy_star();
        //banners       
        }else if($_SESSION['datapay']['tienda'] == 'banner'){
            $Tienda->buy_banner();
        //promocionar anuncios
        }else if($_SESSION['datapay']['tienda'] == 'promocionar'){
            $Tienda->buy_promo();
        //traduccion de anuncios    
        }else if($_SESSION['datapay']['tienda'] == 'traduccion'){
            $Tienda->buy_tradd(); 
        //paquetes de anuncios      
        }else if($_SESSION['datapay']['tienda'] == 'paqueteria'){
            if(!empty($_SESSION['datapay']['renew'])){
                //renovando paquete existente
                $Tienda->renew_paquete();
            }else if(!empty($_SESSION['datapay']['rebuy'])){
                //recomptrando un paquete caducado
                $Tienda->rebuy_paquete();
            }else{
                //comprando paquete nuevo
                $Tienda->buy_paqueteria();        
            }
        }
        //end
	}






	public function error_en_compra($user,$array){

		//guardara el error en la compra
		$campos = array('user' 		=> $user,
						'error_code' 	=> $array['L_ERRORCODE0'],
						'short_msg' 	=> str_replace('%20', ' ', $array['L_SHORTMESSAGE0']),
						'severity_code' => $array['L_SEVERITYCODE0']);

		$this->insert('errores_en_compra',$campos);

		header('location : index.php?perfil=19');
		exit;

	}


}





?>