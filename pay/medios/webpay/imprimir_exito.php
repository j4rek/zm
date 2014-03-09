<?php
require("./API/API.php");
/*INICIALIZACION DE COMPONENETES*/
$api	 = new j4son();
$t		 = new Template("template");
$webpay  = new webpay();
$t->set_file("plantilla","imprimir_exito.html");
/*FIN DE INICIALIZACION DE COMPONENTES*/
/*RECEPCION DE VARIABLES*/
$trs_orden_compra = $_GET['OC'];
/*FIN DE RECEPCION DE VARIABLES*/
/*QUERYS*/
/*FIN QUERYS*/
########################## <CONTENIDO> ###############################
/* PREPARACION DE CONTENIDOS*/
$data=array(
			"HEADER"=>$api->make_header(),
			"footer"=>$api->get_footer()
			);
$api->set_data($t,$data);
$t->set_var("orden_compra", $trs_orden_compra);
$t->set_var("datos_pagos", $webpay->getDatosPagoWebpay($trs_orden_compra));
$t->parse("out","plantilla",true);
$t->p("out");
########################## <FIN CONTENIDO> ###############################
?>