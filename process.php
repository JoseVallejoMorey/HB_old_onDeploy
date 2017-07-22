<?php

//process.php es el archivo mas importante del sistema de paypal
//el analizara las variables y enviara y recibira codigo


include_once 'inc/paypal_config.php';
include_once 'inc/clases/procesos/paypal.class.php';
include_once 'inc/clases/perfil/usuarios.class.php';
require_once 'inc/clases/procesos/productos.class.php';
require_once 'inc/clases/procesos/tienda.class.php';
include_once 'inc/clases/uploadimg.class.php';

$newbann = '';          //de momento vacio 
$new_arguments = '';    // vacio (hasta que haya nuevo banner)

$paypalmode = ($PayPalMode=='sandbox') ? '.sandbox' : '';


$Prodd  = new Productos();



//Post Data received from product list page.
if(isset($_POST['tienda'])) {

    //Mainly we need 4 variables from product page ItemName, ItemPrice, ItemNumber and ItemQuantity
    //obtendremos el precio del producto por db (crear un id de producto)
    $_SESSION['itempay'] = array(); //contiene informacion para paypal
    $_SESSION['datapay'] = $_POST; //contiene informacion para la db
    


    //extraigo datos segun producto
    if(isset($_POST["itemnumber"])){
        $ItemNumber  = $_POST["itemnumber"];            //Item Number
        $datos_buenos = $Prodd->get_producto_by_id($_POST["itemnumber"]);
    } 
    //datos procedentes de db
    if($datos_buenos != false){
        $ItemName    = $datos_buenos["nombre"];         //Item Name
        $ItemPrice   = $datos_buenos["precio"];         //Item Price
        $ItemDesc    = $datos_buenos["descripcion"];    //Item description
    }

    if(isset($_POST["itemQty"])){   $ItemQty     = $_POST["itemQty"];   
    }else{                          $ItemQty     = 1;                   } //Item Quantity
 
    $ItemTotalPrice = ($ItemPrice*$ItemQty); //(Price x Quantity = Total) total amount of product;

    //Hay banner nuevo
    if(isset($_POST["new_bann"])){  
        $Tienda   = new Tienda();
        $Upimg    = new Upimg();

        $newbann        = $Prodd->es_banner_nuevo($Tienda, $Upimg, $ItemTotalPrice);
        $new_arguments  = $newbann['argument'];
        $ItemTotalPrice = $newbann['totalprice'];        
    }

    //estudiar si añadir iva
    $GrandTotal = $ItemTotalPrice;
   
    //Parameters for SetExpressCheckout, which will be sent to PayPal
    $padata =   '&METHOD=SetExpressCheckout'.
                '&RETURNURL='.urlencode($PayPalReturnURL ).
                '&CANCELURL='.urlencode($PayPalCancelURL).
                '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
               
                '&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
                '&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
                '&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
                '&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
                '&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).
                //si hay banner nuevo aqui es donde le pasa la info
                $new_arguments.
       
                '&NOSHIPPING=1'. //set 1 to hide buyer's shipping address, in-case products that do not require shipping
               
                '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
                '&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
                '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
                '&LOCALECODE=ES'. //PayPal pages to match the language on your website.
                '&LOGOIMG=http://www.sanwebe.com/wp-content/themes/sanwebe/img/logo.png'. //site logo
                '&CARTBORDERCOLOR=FFFFFF'. //border color of cart
                '&ALLOWNOTE=1';
        
    ############# set session variable we need later for "DoExpressCheckoutPayment" #######

    $_SESSION['itempay']['ItemNumber']  =  $ItemNumber; //Item Number
    $_SESSION['itempay']['ItemQty']     =  $ItemQty; // Item Quantity

    if(is_array($newbann)){
        $_SESSION['itempay']['ItemName1']    =  $newbann['producto']['nombre'];//Item Name
        $_SESSION['itempay']['ItemPrice1']   =  $newbann['producto']['precio']; //Item Price
        $_SESSION['itempay']['ItemNumber1']  =  $newbann['producto']['id']; //Item Number
        $_SESSION['itempay']['ItemDesc1']    =  $newbann['producto']['descripcion']; //Item description
        $_SESSION['itempay']['ItemQty1']     =  1; // Item Quantity
    }

    $_SESSION['itempay']['ItemTotalPrice']  =  $ItemTotalPrice; //total amount of product;
    $_SESSION['itempay']['GrandTotal']      =  $GrandTotal;

    //We need to execute the "SetExpressCheckOut" method to obtain paypal token
    $Paypal= new MyPayPal();
    $httpParsedResponseAr = $Paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
       
    //Respond according to message we receive from Paypal
    if( "SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
        "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){

        //Redirect user to PayPal store with Token received.
        $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
        header('Location: '.$paypalurl);
        exit;
             
    }else{
        //Show error message
        $Prodd->error_en_compra($_SESSION['user_id'],$httpParsedResponseAr);
    }
}


//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_GET["token"]) && isset($_GET["PayerID"])){
    //we will be using these two variables to execute the "DoExpressCheckoutPayment"
    //Note: we haven't received any payment yet.
   
    //variables que vienen por GET
    $token = $_GET["token"];
    $payer_id = $_GET["PayerID"];
   
    //get session variables
    $ItemNumber    = $_SESSION['itempay']['ItemNumber']; //Item Number
    $ItemQty       = $_SESSION['itempay']['ItemQty']; // Item Quantity

    //extraigo datos segun producto
    if(isset($_SESSION['itempay']['ItemNumber'])){
        $ItemNumber   = $_SESSION['itempay']['ItemNumber'];            //Item Number
        $datos_buenos = $Prodd->get_producto_by_id($_SESSION['itempay']['ItemNumber']);
    } 
    //datos procedentes de db
    if($datos_buenos != false){
        $ItemName    = $datos_buenos["nombre"];         //Item Name
        $ItemPrice   = $datos_buenos["precio"];         //Item Price
        $ItemDesc    = $datos_buenos["descripcion"];    //Item description
    }

    //si hay nuevo banner creo el codigo con sus valores
    if(isset($_SESSION['itempay']['ItemName1'])){
        $newbann = $Prodd->newbann_for_paypal();
    }

    $ItemTotalPrice     = $_SESSION['itempay']['ItemTotalPrice']; //total amount of product;
    $GrandTotal         = $_SESSION['itempay']['GrandTotal'];

    $padata =   '&TOKEN='.urlencode($token).
                '&PAYERID='.urlencode($payer_id).
                '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
               
                //set item info here, otherwise we won't see product details later 
                '&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
                '&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
                '&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
                '&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
                '&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).

                //si hay new banner
                $newbann.
                '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
                '&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
                '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);
   
    // We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
    // HPRA = httpParsedResponseAr
    $paypal= new MyPayPal();
    $HPRA = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
   
    //Check if everything went ok..
    if("SUCCESS" == strtoupper($HPRA["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($HPRA["ACK"])){

        //echo 'Your Transaction ID : '.urldecode($HPRA["PAYMENTINFO_0_TRANSACTIONID"]);
        $_SESSION['datapay']['token'] = urldecode($HPRA['TOKEN']);
        $_SESSION['datapay']['transid'] = urldecode($HPRA["PAYMENTINFO_0_TRANSACTIONID"]);  
        $_SESSION['datapay']['payerid'] = urldecode($HPRA['PAYMENTINFO_0_SECUREMERCHANTACCOUNTID']);
        $transStatus = $HPRA["PAYMENTINFO_0_PAYMENTSTATUS"];

            /*
            //Sometimes Payment are kept pending even when transaction is complete.
            //hence we need to notify user about it and ask him manually approve the transiction
            */
        //ESTO ESTA CORRECTO PERO LO OCULTO POR MOMENTO 
        if('Completed' == $HPRA["PAYMENTINFO_0_PAYMENTSTATUS"]){
            echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
        }else if('Pending' == $HPRA["PAYMENTINFO_0_PAYMENTSTATUS"]){
            echo '<div style="color:red">Transaction Complete, but payment is still pending! '.
                'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
        }

        // we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
        // GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
        $padata =   '&TOKEN='.urlencode($token);
        $paypal= new MyPayPal();
        $HPRA = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

        if( "SUCCESS" == strtoupper($HPRA["ACK"]) || 
            "SUCCESSWITHWARNING" == strtoupper($HPRA["ACK"])){
               
            //echo '<br /><b>Stuff to store in database :</b><br /><pre>';
            //cualquier compra pasara por aqui
            $Tienda   = new Tienda();
            $Prodd->tienda_correspondiente($Tienda);

        }else{
            $Prodd->error_en_compra($_SESSION['user_id'],$HPRA);
        }
    }else{
            $Prodd->error_en_compra($_SESSION['user_id'],$HPRA);
    }
}

?>