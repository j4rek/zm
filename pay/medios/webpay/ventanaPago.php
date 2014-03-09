<?php
require("./API/webpay.php");
require("./API/template.php");
/*INICIALIZACION DE COMPONENETES*/
$t	 = new Template("template");
$webpay = new webpay();    

/*FIN DE INICIALIZACION DE COMPONENTES*/
$variables = $webpay->dameParametros($_SERVER['REQUEST_URI']);
$ID   = (array_key_exists('TRX',$variables))?$variables["TRX"]:mysql_real_escape_string($_GET["TRX"]);
$TITULO       = (array_key_exists('TITULO',$variables))?$variables["TITULO"]:mysql_real_escape_string($_GET["TITULO"]);
######## VALIDACION DE AVISO #####################
#$api->valida_edicion_aviso($IDAVISO,$KEY);
######## OBTENER DATOS  #####################

########################## CONTENIDO ###############################
/* PREPARACION DE CONTENIDOS*/
$resDatos = $webpay->qry("select * from transacciones where idTransaccion='".$ID."' limit 1;");
$datos    = mysql_fetch_array($resDatos);

if($datos["idComercio"]==1){
 $t->set_file("plantilla","pago_btp.html");
}elseif($datos["idComercio"]==2){
 $t->set_file("plantilla","pago_aav.html");
}elseif($datos["idComercio"]==3){
 $t->set_file("plantilla","pagar_za.html"); 
}elseif($datos["idComercio"]==4){
 $t->set_file("plantilla","pagar_zi.html"); 
}

$dataAviso = array(
	"nombre"   => $datos["nombre"],
	"monto"     => "$ ".number_format($datos["monto"], 0, ",", "."),
	"nombres"   => $datos["nombre"],
	"apellidos" => $datos["apellidos"],
	"RUT"       => number_format($datos["rut"], 0, ",", ".").substr($datos["rut"],-2),
	"Fono"      => $datos["fono"],
	"mail"      => $datos["correo"],
	"aviso"  => str_replace("-"," ",ucwords($TITULO))
);
$webpay->set_data($t,$dataAviso);

$data = array(
	"tbk_id_aviso" => $ID,
	"URLBASE"	   => URLMEDIOS
);

$webpay->set_data($t,$data);

$t->set_var("url_dominio", URLHOME);
$t->set_var("monto_aviso", $datos["monto"].".00");
$t->set_var("orden_compra", $datos["ordenCompra"]);
$t->set_var("ssesion", $webpay->getIDSSesion());
$t->set_var("url_exito", URL_EXITO);
$t->set_var("url_fracaso", URL_FRACASO);
$t->parse("out","plantilla",true);
$t->p("out");
########################## FIN CONTENIDO ###############################
?>