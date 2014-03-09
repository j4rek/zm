<?php

/************** CONFIGURACION HORARIA ****************/
date_default_timezone_set('America/Santiago');

/************** CONFIG ERRORES ****************/
define("DEBUG",1);
error_reporting(E_ALL ); # tipo de errores a desplegar
ini_set('display_errors',DEBUG); # mostrar errores; 1 | 0
/***************************************/
/************** CONFIG MAIL ****************/
// datos SMTP para autentificacion
define ("SMTP_SERVER", "190.98.227.146");
define ("SMTP_PORT", "25");
define ("SMTP_USER", "info@zoomautomotriz.com");
define ("SMTP_PASS", "inf2012");
/***************************************/
/************** CONFIG DB ****************/
// $dbuser="root";
// $dbpassword="kkpura";
// $dbase="BuscaTuPega";
// $dbserver="localhost";
/************** CONFIG DB ****************/
$dbuser="zoomauto_web";
$dbpassword="hC6UT(.L}y)+";
$dbase="zoomauto_web";
$dbserver="localhost";
define("DBUSER",$dbuser);
define("DBPASSWD",$dbpassword);
define("DBASE",$dbase);
define("DBSERVER",$dbserver);
/***************************************/
/************** CONFIG RUTAS ****************/

define("RUTA_BASE", "./classes/API/");
define("RUTA_COOKIE", "/");
define("RUTA_PLANTILLAS", "./plantillas/");

 // rutas de archivos
define("ARCHIVOS", "./archivos/"); // global
	define("IMAGENES", "imagenes/"); // imagenes
	define("LOGOS", "logos/"); // logos empresas
	define("CV", "cv/");  // curriculums, en caso que se implemente

/***************************************/
/************** CONFIG URL ****************/
// url base para sitio... en produccion deberia ser "http://www.buscatupega.cl/"
// ojo, esto es importante!, se utiliza por las friendly URL's, de lo contrario se complica
// para el manejo de los css y las imagenes con rutas locales.


/***************************************/
/************** PARAMETROS FRIENDLY URL ****************/

// esto es por tema de "friendly url's", debido a que el sitio en qa esta en subcarpeta
// y la forma de recibir los parametros se ve alterada.

// numero de subcarpetas en que se encuentra sitio, en qa es 1, debido que esta dentro
// de /buscatupega/ 
// Esto es importante, debido al tema de las friendlyUrl's. En produccion deberia ser 0
define( "SUBCARPETA_HOME", "0" ); 

/***************************************/
/************** URLS ****************/
define("URLBASE","http://usados.zoomautomotriz.com/");
define("URLHOME","http://www.zoomautomotriz.com/");
define("URLAUTOS","http://usados.zoomautomotriz.com/");
define("URLTESTDRIVE","http://www.zoomautomotriz.cl/noticias.aspx?m=2");
define("URLNOTICIAS","http://www.zoomautomotriz.cl/noticias.aspx");
define("URLVIDEOS","http://www.zoomautomotriz.cl/videos.aspx");
define("URLRENTACAR","http://rentacar.zoomautomotriz.com");
define("URLBANDAS","http://usados.zoomautomotriz.com/images/bandas/");
/***************************************/
/************** CONFIGS PARA SERVIPAG ****************/
define("URL_PAGO_SVP","https://www.servipag.com/bpe/bpe_inicio.asp");
define("CDGCANAL_SVP",271);
define("NUMBOLETAS_SVP",1);
define("CDGSUBTRX_SVP",7);
/***************************************/
/************** MENSAJES DE ERROR PRE-DEFINIDOS ****************/
// si, es un arreglo, no una constante...

// codigo 0, falta algun parametro... probablemente alguien intentando pasar datos a mano.
$errores[0] = "Si est&aacute;s viendo esto es porque alguien cometi&oacute; un error. No queremos culpar a nadie, pero probablemente fuiste T&Uacute;!";









?>