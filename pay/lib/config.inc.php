<?php
/********* CONFIG TIME ZONE **********************/
date_default_timezone_set('America/Santiago');

/********* DATOS BASE DE DATOS *******************/
$dbuser     = "zoomchil_upagos";
$dbpassword = "^JKETB!#yCMZ";
$dbase      = "zoomchil_pagos";
$dbserver   = "localhost";
define("DBUSER",$dbuser);
define("DBPASSWD",$dbpassword);
define("DBASE",$dbase);
define("DBSERVER",$dbserver);
// datos CORREO clase SMTP para autentificacion de login mail
define ("SMTP_SERVER", 				"localhost");
define ("SMTP_PORT", 				25);
define ("SMTP_SEGIRIDAD", 			"");
define ("SMTP_USER", 				"contacto@buscatupega.cl");
define ("SMTP_PASS", 				"contact2012");
#paginas de exito y fracaso webpay
define ("URL_DOMINIO",	"http://usados.zoomautomotriz.com");
define ("URL_EXITO",	"http://usados.zoomautomotriz.com/pagos/webpay/exito.php");
define ("URL_FRACASO",	"http://usados.zoomautomotriz.com/pagos/webpay/fracaso.php");
/************** CONFIG RUTAS ****************/
define("RUTA_BASE", "./API/class/");
define("RUTA_COOKIE", "/");
define("RUTA_PLANTILLAS", "./template/");
#rutas de archivos
define("ARCHIVOS", "./archivos/"); // global
define("IMAGENES", "imagenes/"); // imagenes
define("LOGOS", "logos/"); // logos empresas
define("CV", "cv/");  // curriculums, en caso que se implemente
/************** URLS ****************/
define("URLBASE","http://www.zoomchile.cl/");
define("URLPAGO","http://www.zoomchile.cl/medios/{MEDIO}/ventanaPago.html/TRX/{IDTRX}/TITULO/{TIT}");


/***************************************/
/***************************************/
/************** PARAMETROS FRIENDLY URL ****************/

// esto es por tema de "friendly url's", debido a que el sitio en qa esta en subcarpeta
// y la forma de recibir los parametros se ve alterada.

// numero de subcarpetas en que se encuentra sitio, en qa es 1, debido que esta dentro
// de /buscatupega/ 
// Esto es importante, debido al tema de las friendlyUrl's. En produccion deberia ser 0
define("SUBCARPETA_HOME", "0");
#valores de avisos
define("VALOR_AVISO_NORMAL",1190);
define("VALOR_AVISO_DESTACADO",3570);
define("VALOR_IVA",19);
/************** MENSAJES DE ERROR PRE-DEFINIDOS ****************/
// si, es un arreglo, no una constante...

// codigo 0, falta algun parametro... probablemente alguien intentando pasar datos a mano.
$errores[0] = "Si est&aacute;s viendo esto es porque alguien cometi&oacute; un error. No queremos culpar a nadie, pero probablemente fuiste T&Uacute;!";
?>