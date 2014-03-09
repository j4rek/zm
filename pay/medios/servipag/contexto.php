<?php
require_once("./classes/API/class_API.php");
$api=new j4son();
$matriz_ini = parse_ini_file("./classes/API/config.ini", true);
$nodo = $matriz_ini['Config_Nodo_XML4'];
$Xml_out=$_POST['xml'];

$BotonDePago = $api->crear_pago();

//estableco las rutas de las llaves
$BotonDePago->setRutaLlaves($matriz_ini['Config_Llaves']['privada'], $matriz_ini['Config_Llaves']['publica']);

//realizo la comprobación del XML4
$result = $BotonDePago->ValidaXml4($Xml_out, $nodo);

//genero codigo y mensaje para  el xml4
$codigo = '1';
$mensaje= 'Transaccion Mala';
if($result){
	$codigo = '0';
	$mensaje= 'Transaccion OK 10-4';
	$IDCUSTOM_SVP = $api->getValorTag("<IdTxCliente>",$Xml_out);
	$ESTADO_SVP = $api->getValorTag("<EstadoPago>",$Xml_out);
	$TX_SVP = $api->getValorTag("<IdTrxServipag>",$Xml_out);
	$MENSAJE_SVP = $api->getValorTag("<Mensaje>",$Xml_out);
	if($api->qry("update servipagTx set xml4='$Xml_out' where txServipag='$TX_SVP' and idCustom='$IDCUSTOM_SVP' limit 1;"))
	{
		$BotonDePago->GeneraLog("Z","Cambios en registro transaccion(CDGSVP:$TX_SVP) zoomautomotriz");
			$resCorreo=$api->qry("select * from avisos where idAviso='$IDCUSTOM_SVP' limit 1;");
			$datos=mysql_fetch_array($resCorreo);
		$mensaje_correo="
		<html>
		<head>
		<title>Contacto ZoomAutomotriz.com</title>
		</head>
		<body style='margin:0px;padding:0px;background-color:#efefef;'>
		<div style='width:488px; height:auto;padding:0px;text-align:center;'>
		<center>
		<div style='width:488px;height:auto;margin: 0px 0px 0px 0px;text-align:center;border: 1px solid #000000;'>
		<div style='width:488px;height:auto;margin:0px auto 0px auto; padding:0px;position:relative;text-align:center;top:0px;'>
		<img src='".URLBASE."img/mail_header.jpg' border='0' width='488' height='83' alt='ZoomAutomotriz.com' /></div>
		<div style='background-color:#ffffff;width:488px;margin:0px auto 0px auto;padding:0px;top:0px; position:relative;'>
		<div style='width:400px;margin:0px auto 0px auto;padding:0px;position:relative;text-align:left;'><br />
		<span style='font: normal 12px Tahoma, Arial, Verdana, sans-serif;color:#666; text-align:justify;'>
		<p><strong>&iexcl;Felicitaciones!</strong></p>
		<p>Usted ha finalizado con &eacute;xito el proceso publicaci&oacute;n de su aviso en ZoomAutomotriz.com.</p>
		<p>No olvide revisar peri&oacute;dicamente su casilla de email por contactos a su aviso y visitar nuestro sitio ZoomAutomotriz.com.</p>
		<p>Si lo desea, puede modificar su aviso totalmente gratis, haciendo <a href=\"".URLBASE."editar_2.html/ID/".$datos["idAviso"]."/CODE/".$datos["contrasena"]."\" style=\"text-decoration:none;\">click aqu&iacute;</a>.
		</p>
		</span>
		<span style='font: normal 12px Tahoma, Arial, Verdana, sans-serif;color:#666; text-align:justify;'>
		<br /><br />
		Atentamente,<br /><span style='color:black;'><b>Equipo ZoomAutomotriz.com</b></span>
		<br />&nbsp;<br />
		</span>
		</div>
		</div>
		<div style='width:488px;height:42px;margin:0px auto 0px auto; padding:0px;position:relative;text-align:center;'>
		<img src='".URLBASE."img/mail_footer.jpg' border='0' width='488' height='42' alt='ZoomAutomotriz.com' /></div>
		</div></center>
		</div>
		</body>
		</html>";

		$SMTPMail = $api->crea_mail (SMTP_SERVER, SMTP_PORT, SMTP_USER, SMTP_PASS, "info@zoomautomotriz.com", $datos["correo"], "[ZoomAutomotriz.com] Información aviso", $mensaje_correo);
		$SMTPChat = $SMTPMail->SendMail();
		header("location: ./termino.php?TX=".base64_encode($datos["idAviso"])."&TXSVP=".base64_encode($TX_SVP));
		exit();
	}
	else
	{
		$BotonDePago->GeneraLog("Z","Fallo al actualizar transaccion($TX_SVP)");
	}
}

?>