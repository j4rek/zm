<?php

$realtime =  "./logs/log_pagos_".date("Ymd").".log"; 
$ddf = fopen($realtime,'a');
fwrite($ddf,"[".date("r")."]     paso 1 : INICIO \r\n");

require("API/webpay.php");
error_reporting(0);
/*INICIALIZACION DE COMPONENETES*/
$webpay = new webpay();
/*RECEPCION DE VARIABLES*/
$trs_transaccion 		= ($_POST['TBK_TIPO_TRANSACCION']);
$trs_respuesta 			= ($_POST['TBK_RESPUESTA']);
$trs_orden_compra 		= ($_POST['TBK_ORDEN_COMPRA']);
$trs_id_session 		= ($_POST['TBK_ID_SESION']);
$trs_cod_autorizacion 	= ($_POST['TBK_CODIGO_AUTORIZACION']);
$trs_monto 				= (substr($_POST['TBK_MONTO'],0,-2).".00");
$trs_nro_final_tarjeta 	= ($_POST['TBK_FINAL_NUMERO_TARJETA']);
$trs_fecha_expiracion 	= ($_POST['TBK_FECHA_EXPIRACION']);
$trs_fecha_contable 	= ($_POST['TBK_FECHA_CONTABLE']);
$trs_fecha_transaccion 	= ($_POST['TBK_FECHA_TRANSACCION']);
$trs_hora_transaccion 	= ($_POST['TBK_HORA_TRANSACCION']);
$trs_id_transaccion 	= ($_POST['TBK_ID_TRANSACCION']);
$trs_tipo_pago 			= ($_POST['TBK_TIPO_PAGO']);
$trs_nro_cuotas 		= ($_POST['TBK_NUMERO_CUOTAS']);
$trs_mac 				= ($_POST['TBK_MAC']);
$trs_monto_cuota 		= ($_POST['TBK_MONTO_CUOTA']);
$trs_tasa_interes_max 	= ($_POST['TBK_TASA_INTERES_MAX']);
/*FIN DE RECEPCION DE VARIABLES*/
/* Graba en base de datos */
$webpay->gurdarDatosWebpay(	
	$trs_transaccion,
	$trs_respuesta,
	$trs_orden_compra,
	$trs_id_session,
	$trs_cod_autorizacion,
	$trs_monto,
	$trs_nro_final_tarjeta,
	$trs_fecha_expiracion,
	$trs_fecha_contable,
	$trs_fecha_transaccion,
	$trs_hora_transaccion,
	$trs_id_transaccion,
	$trs_tipo_pago,
	$trs_nro_cuotas,
	$trs_mac,
	$trs_tasa_interes_max
);
$webpay->guardarDebugWebpay("LOG DATOS WEBPAY PASO 1 : ", "OCC : ".$trs_orden_compra." ID_TRS : ".$trs_id_transaccion, "log para chekeo de transacciones PASO 1");
fwrite($ddf,"[".date("r")."]     paso 2 : guardar  \r\n");

/* finde grabar en base */

/**** inicio de pagina de cierre_compra.php***/
if($trs_respuesta == 0){
	//**** validacion de mac ****/***
	$temporal = "../../cgi-bin/log/temporal.txt";
	if($fp = fopen($temporal, "w")){
		fwrite($fp, $trs_cod_autorizacion);
		fclose($fp);
	}
	
	/*Abrir archivo y guardar variables POST recibidas */
	$filename = "../../cgi-bin/log/log".$trs_id_transaccion.".txt";
	$fp = fopen($filename,"w");
	reset($_POST);
	while (list($key,$val) = each($_POST)){
		fwrite($fp,"$key=$val&");
	}
	fclose($fp);
	
	/*Invocar a tbk_check_mac.cgi usando como parámetro el archivo generado */
	$url_cgi 		= "http://www.zoomchile.cl/cgi-bin/exec_check_mac.cgi?filename=log".$trs_id_transaccion.".txt";
    $resultado_curl = $webpay->getDatosCurl($url_cgi);
	fwrite($ddf,"[".date("r")."]     paso 3 : $resultado_curl  \r\n");
	
	/*Si el resultado es "CORRECTO", entonces mac válido*/
	$webpay->guardarDebugWebpay("resultado_curl", $resultado_curl, "log de curl -> log".$trs_id_transaccion.".txt");
	
	#gurdar resultado de curl -> en debug
	if($resultado_curl == "CORRECTO"){
		#echo "ACEPTADO 1<br />";
		
		/**** Comprobacion de Orden de Compra ****/
		$comprobar_orden = $webpay->getComprobacionOrden($trs_orden_compra);
		fwrite($ddf,"[".date("r")."]     paso 4 : $comprobar_orden  \r\n");
		if ($comprobar_orden == "ACEPTADO"){
			
			/**** Comprobacion de Monto ****/
			$comprobar_monto = $webpay->getComprobacionMonto($trs_orden_compra, $trs_monto);
			fwrite($ddf,"[".date("r")."]     paso 5 : $comprobar_monto  \r\n");
			if ($comprobar_monto == "ACEPTADO"){
				
				$webpay->updateDatosWebpay($trs_orden_compra, $trs_id_session, "Aceptada");
				$webpay->guardarDebugWebpay("Debug Aviso publicado : ", $trs_orden_compra." | ".$trs_id_session, "Codigo Orden de Compra : ".$trs_orden_compra." Codigo Aviso : ".$trs_id_session);
				
				##------ ACTIVACION DE SERVICIO EN EL COMERCIO -----------------------##
				$idrow=$webpay->qry("select * from webpay where Tbk_Orden_Compra='$trs_orden_compra' limit 1;",0,1);
				$vars = array(
				   'IDTRX'=>$idrow["Tbk_idAviso"]
				);
				
				$content = http_build_query($vars);
				
				$fp = fsockopen("190.98.227.146", 80, $errno, $errstr, 30);
				if (!$fp) {
					echo "$errstr ($errno)<br />\n";
				} else {
					$out = "POST /servicios/activarServicio.php HTTP/1.1\r\n";
					$out .= "Host: www.autoalaventa.cl\r\n";
					$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
					$out .= 'Content-Length: ' . strlen($content) . "\r\n\r\n";
					
					fwrite($fp, $out);
					fwrite($fp, $content);
					while (!feof($fp)) {
						$_cont=fgets($fp, 128);
					}
					fclose($fp);
				}
				
				$_tag="<estado>";
				$_pos=strpos($_cont,$_tag) + strlen($_tag);
				$_fpos=strpos($_cont,str_replace("<","</",$_tag));
				$_len=($_fpos-$_pos);
				$estado=trim(substr($_cont,$_pos,$_len));
				
				fwrite($ddf,"[".date("r")."]     paso 6 : activando $estado  \r\n");
				##-----------------------------------------------------------------##
				
				$webpay->qry("update transacciones set idEstado='2' where ordenCompra='$trs_orden_compra' limit 1;");
				
				echo "ACEPTADO"; #" 2<br />";
			}else{
				$webpay->updateEstadoAviso($trs_id_session, 12);
				echo "RECHAZADO"; #"1<br />";
			}
			/**** fin Comprobacion de Montos ****/
		}else{
			$webpay->updateEstadoAviso($trs_id_session, 12);
			echo "RECHAZADO"; #"2<br />";
		}
	/*** fin Comprobacion de Orden de Compra ****/
	}else{
		$webpay->updateEstadoAviso($trs_id_session, 12);
		echo "RECHAZADO"; # "3<br />";
	}
	/****fin Validacion MAC ****/
}else{
	echo "ACEPTADO"; #" 3<br />";
}

fclose($ddf);
/**** Fin de pagina de Cierre ****/
?>