<?php
 require_once("./classes/API/class_API.php"); 
$api=new j4son();
/***************************************/
 $matriz_ini = parse_ini_file("./classes/API/config.ini", true);
 $Xml_out = $_POST['XML'];
 $Xml_Resultado = '';
 $nodo = $matriz_ini['Config_Nodo_XML2'];
 $BotonDePago = $api->crear_pago();

 //estableco las rutas de las llaves
 $BotonDePago->setRutaLlaves($matriz_ini['Config_Llaves']['privada'], $matriz_ini['Config_Llaves']['publica']);
 
 //realizo la comprobación del XML2
 $result =  $BotonDePago->CompruebaXML2($Xml_out, $nodo);

 //genero codigo y mensaje para  el xml3
 $codigo = '1';
 $mensaje= 'Transaccion Mala';

 if($result)
{
	$BotonDePago->GeneraLog("Z","Entro a comprobar XML2");
	$IDCUSTOM_SVP = $api->getValorTag("<IdTxCliente>",$Xml_out);
	$MONTO_SVP = $api->getValorTag("<Monto>",$Xml_out);
	$BOLETA_SVP = $api->getValorTag("<Boleta>",$Xml_out);
	$TX_SVP = $api->getValorTag("<IdTrxServipag>",$Xml_out);
	$FECHA_SVP = $api->getValorTag("<FechaPago>",$Xml_out);
 	$codigo = '0';
	$mensaje= 'Transaccion OK 10-4';

	if($api->qry("update servipagTx set txServipag='$TX_SVP', xml2='$Xml_out' where idTransaccion='$BOLETA_SVP' and idCustom='$IDCUSTOM_SVP' limit 1;"))
	{
		$BotonDePago->GeneraLog("Z","Cambios en registro transaccion($BOLETA_SVP)");
		if($api->qry("update avisos set idEstado='3' where idAviso='$IDCUSTOM_SVP' and costoAviso='$MONTO_SVP' limit 1;"))
		{
			$BotonDePago->GeneraLog("Z","Cambios en registro aviso($IDCUSTOM_SVP)");
		}
		else
		{
			$BotonDePago->GeneraLog("Z","Error update AVISOS ($IDCUSTOM_SVP)");
		}
	}
	else
	{
		$BotonDePago->GeneraLog("Z","Error update TX ($BOLETA_SVP)");
	}
}
else
{
	$BotonDePago->GeneraLog("Z","Fallo al comprobar XML2");
}

 //genero el xml3
 $Xml_Resultado = $BotonDePago->GeneraXML3($codigo,$mensaje);
 
echo($Xml_Resultado);
?>