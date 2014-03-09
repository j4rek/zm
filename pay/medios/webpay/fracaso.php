<?php
require("./API/API.php");
/*INICIALIZACION DE COMPONENETES*/
$api		= new j4son();
$t		 	= new Template("template");
$t->set_file("plantilla","fracaso.html");
$webpay		= new webpay();
/*FIN DE INICIALIZACION DE COMPONENTES*/
/*RECEPCION DE VARIABLES*/
$trs_orden_compra = $_POST['TBK_ORDEN_COMPRA'];
$trs_id_session   = $_POST['TBK_ID_SESION'];
$webpay->updateDatosWebpay($trs_orden_compra, $trs_id_session, "Rechazada");
/*FIN DE RECEPCION DE VARIABLES*/
/*QUERYS*/
/*FIN QUERYS*/
########################## <CONTENIDO> ###############################
/* PREPARACION DE CONTENIDOS*/
$data = array(
	"HEADER"  => $api->make_header(),
	"FOOTER"  => $api->get_footer(),
	"URLBASE" => URLAUTOS
);
$api->set_data($t,$data);
$t->set_var("orden_compra", $trs_orden_compra);
$t->parse("out","plantilla",true);
$t->p("out");
########################## <FIN CONTENIDO> ###############################
?>