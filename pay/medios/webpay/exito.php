<?php
session_start();
require("./API/API.php");
/*INICIALIZACION DE COMPONENETES*/
$api	 = new j4son();
$smtp 	 = new smtp(SMTP_SERVER, SMTP_PORT, SMTP_SEGIRIDAD, SMTP_USER, SMTP_PASS);
$t		 = new Template("template");
$webpay  = new webpay();
$t->set_file("plantilla","exito.html");
/*FIN DE INICIALIZACION DE COMPONENTES*/
/*RECEPCION DE VARIABLES*/
$trs_orden_compra = $_POST['TBK_ORDEN_COMPRA'];
if(!isset($_SESSION['correo'])){
	$_SESSION['correo'] = "";
}
/*FIN DE RECEPCION DE VARIABLES*/
/*CORREO DE EXITO*/
if(isset($trs_orden_compra) && $trs_orden_compra != $_SESSION['correo']){
	$idAviso 		= $webpay->dameDatoAviso($trs_orden_compra, "idAviso");
	$e_mail 		= $webpay->dameDatoAviso($trs_orden_compra, "correo");
	$nombre_cliente = $webpay->dameDatoAviso($trs_orden_compra, "nombres")." ".$webpay->dameDatoAviso($trs_orden_compra, "apellidos");
	$mensaje_correo = $webpay->comprobantePagoHTML($idAviso, $nombre_cliente);
	$headers 		= $smtp->headers($e_mail, "contacto@zoomautomotriz.com");						
	$smtp->send("contacto@zoomautomotriz.com", $e_mail, "[ZoomAutomotriz.com]: Comprobante de Pago", $mensaje_correo, $headers);
	$_SESSION['correo'] = $trs_orden_compra;
	$_SESSION['f5']++;
	$webpay->guardarDebugWebpay("envio de mail", "hubo envio", "exito de envio a : ".$e_mail);
}else{
	$e_mail	= $webpay->dameDatoAviso($trs_orden_compra, "correo");
	$webpay->guardarDebugWebpay("envio de mail", "no hubo envio", "error de envio a : ".$e_mail." error en : ".$_SESSION['correo']." ".$trs_orden_compra);
	$_SESSION['f5']++;
	if($_SESSION['f5'] > 2){
		session_destroy();
		header("Location: http://usados.zoomautomotriz.com");  
		exit; 
	}
}
/*FIN CORREO DE EXITO*/
########################## <CONTENIDO> ###############################
/* PREPARACION DE CONTENIDOS*/
$data = array(
	"HEADER"  => $api->make_header(),
	"FOOTER"  => $api->get_footer(),
	"URLBASE" => URLAUTOS
);
$api->set_data($t,$data);
$t->set_var("orden_compra", $trs_orden_compra);
$t->set_var("datos_pagos", $webpay->getDatosPagoWebpay($trs_orden_compra));
$t->parse("out","plantilla",true);
$t->p("out");
########################## <FIN CONTENIDO> ###############################
?>