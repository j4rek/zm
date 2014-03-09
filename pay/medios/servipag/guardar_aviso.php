<?php
require_once("./classes/API/class_API.php"); 
$api=new j4son();
/************** plantilla ****************/
$t=$api->crear_template(RUTA_PLANTILLAS);
$t->set_file("plantilla","pagar.html");
######## RECEPCION #####################
$variables = $api->dameParametros($_SERVER['REQUEST_URI']);
$IDAVISO=(array_key_exists('ID',$variables))?$variables["ID"]:mysql_real_escape_string($_GET["ID"]);
$KEY=(array_key_exists('CODE',$variables))?$variables["CODE"]:mysql_real_escape_string($_GET["CODE"]);
######## VALIDACION DE AVISO #####################
$api->valida_edicion_aviso($IDAVISO,$KEY);
######## OBTENER DATOS  #####################
$resDatos=$api->qry("select * from avisos where idAviso='$IDAVISO' limit 1;");
$datos=mysql_fetch_array($resDatos);

$plan=$api->dame_datos_plan($datos["idPlan"]);

$dataAviso=array("nombre"=>$datos["nombres"],"monto"=>"$ ".number_format($plan["precio"], 0, ",", "."),"nombres"=>$datos["nombres"],
					"apellidos"=>$datos["apellidos"],"RUT"=>$datos["RUT"]."-".$datos["RUTDV"],"Fono"=>$datos["telefono"],"mail"=>$datos["correo"],
					"vehiculo"=>$api->strVehiculo($datos["idTipoVehiculo"])." ".$api->strMarca($datos["idMarca"])." ".$datos["modelo"]);
$api->set_data($t,$dataAviso);

/************** seteo de datos para servipag *************************/
	$CodigoCanaldePago = CDGCANAL_SVP;
	$FechaPago =date("Ymd");
	$FechaPago_DB =date("Y-m-d H:i:s");
	$NumeroBoletas = NUMBOLETAS_SVP;
	$IdSubTrx = CDGSUBTRX_SVP;
	$Monto = $plan["precio"];	
	$MontoTotalDeuda = $Monto;
	$FechaVencimiento = $FechaPago;
/***************************************/
/************** PROCESO SERVIPAG ****************/
/************** ingreso TRANSACCION en DB ****************/
	if($api->qry("insert into servipagTx set idCustom='$IDAVISO', monto='$Monto', fecha='$FechaPago_DB'; "))
	{
		$res=$api->qry("select * from servipagTx where idCustom='$IDAVISO' and monto='$Monto' and fecha='$FechaPago_DB' limit 1;");
		$row=mysql_fetch_array($res);
		$CodigoIdentificador=$row["idTransaccion"];
		$IdTxCliente=$row["idCustom"];
		$Boleta=$row["idTransaccion"];
		$upd=$api->qry("update servipagTx set boleta='$Boleta' where idCustom='$IDAVISO' and monto='$Monto'  and fecha='$FechaPago_DB' limit 1;");
	}	
/************** XML SERVIPAG ****************/
	$matriz_ini = parse_ini_file("./classes/API/config.ini", true);
	//Instancio la clase
	$BotonPago = $api->crear_pago();

	//estableco las llaves
	$BotonPago->setRutaLlaves($matriz_ini['Config_Llaves']['privada'], $matriz_ini['Config_Llaves']['publica']);
	$BotonPago->setArrayOrdenamiento($matriz_ini['Config_Nodo']);
	$xml = $BotonPago->GeneraXML($CodigoCanaldePago, $IdTxCliente, $FechaPago, $MontoTotalDeuda, $NumeroBoletas, $IdSubTrx, $CodigoIdentificador, $Boleta, $Monto, $FechaVencimiento);

/************** FIN PROCESO SERVIPAG ****************/

/************** variables plantilla ****************/
$data=array("HEADER"=>$api->make_header(),"FOOTER"=>$api->get_footer(),"xml"=>$xml,"urlpago"=>URL_PAGO_SVP,"URLBASE"=>URLAUTOS);
$api->set_data($t,$data);
$api->cerrar($t,"plantilla");
?>