<?php
require_once("./classes/API/class_API.php");
$api=new j4son();
$t=$api->crear_template(RUTA_PLANTILLAS);
$t->set_file("plantilla","publicacion_finalizada.html");
/************** recepcion de datos ****************/
$ID=base64_decode($_GET["TX"]);
$TX_SVP=base64_decode($_GET["TXSVP"]);
/************** validacion ****************/
if(!$api->emptyVal($ID))
{
	$t->set_file("plantilla","termino.html");
	$t->set_var("HEADER",$api->make_header());
	$t->set_var("footer",$api->get_footer());
	$api->cerrar($t,"plantilla");
	exit();
}
/************** variables plantilla ****************/
$resAviso=$api->qry("select * from avisos where idAviso='$ID' limit 1;");
$dataAviso=mysql_fetch_array($resAviso);
$resTrx=$api->qry("select * from servipagTx where idCustom='$ID' and txServipag='$TX_SVP' and xml2<>'' limit 1;");
$dataTrx=mysql_fetch_array($resTrx);

$data=array("HEADER"=>$api->make_header(),"FOOTER"=>$api->get_footer(),"vehiculo"=>$api->strVehiculo($dataAviso["idTipoVehiculo"])." ".$api->strMarca($dataAviso["idMarca"])." ".$dataAviso["modelo"],"id"=>$dataAviso["idAviso"],
					"friendly"=>$api->traduceTextoaFriendlyURL($api->strVehiculo($dataAviso["idTipoVehiculo"])." ".$api->strMarca($dataAviso["idMarca"])." ".$dataAviso["modelo"]),"codigo"=>$dataTrx["txServipag"]);
$api->set_data($t,$data);

$api->cerrar($t,"plantilla");
?>