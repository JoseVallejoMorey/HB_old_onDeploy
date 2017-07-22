<?php
//variables de vendedor de paypal

$PayPalMode 			= 'sandbox'; // sandbox or live
$PayPalApiUsername 		= 'vendor-mox_api1.gmail.com'; 	//PayPal API Username
$PayPalApiPassword 		= 'VKVS9QDZZKL8SU62'; 			//Paypal API password
$PayPalApiSignature 	= 'AJnUsC4OREQHvidJKhvYI5Dv0wFtAWgZt6baov5Vg4EOQwGuNMh1iwyw'; //API Signature
$PayPalCurrencyCode 	= 'EUR'; //Paypal Currency Code
$PayPalProcess 			= 'http://localhost:8888/base13.8/index.php?perfil=9&'; //proceso
$PayPalReturnURL 		= $PayPalProcess.'payprocess=1'; //Point to process.php page
$PayPalCancelURL 		= $PayPalProcess.'paycancel=2'; //Cancel URL if user clicks cancel

//http://localhost:8888/base12.9.3/index.php?perfil=12

?>