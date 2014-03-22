<?php
namespace config;
date_default_timezone_set("America/Santiago");

class config {
 
 ## -- Constantes para conexion MySql -- ##
 //const myHost="localhost";
 //const myDbase="ZoomSeptiembre";
 //const myUser="root";
 //const myPass="kkpura";
 const myHost="localhost";
 const myDbase="Zoomv2";
 const myUser="Zoom2011db";
 const myPass="ZoomDBsa";
 
 ## -- Constantes para conexion msSql -- ##
 const msHost="";
 const msDbase="";
 const msUser="";
 const msPass="";
 
 ## -- Constantes para envio de correo -- ##
 const SMTP_SERVER="mail.zoomchile.com";
 const SMTP_PORT="25";
 const SMTP_USER="envios@zoomchile.com";
 const SMTP_PASS="zoom2012";

 ## -- Constantes URLS del sitio -- ##
 const URLBASE="http://www.zoominmobiliario.com/";
 const URLWEB="http://172.16.17.45/zoomv2/";
 const DOMADMIN="http://bo.zoominmobiliario.com/";
 const DOMSTATIC="http://static.zoominmobiliario.com/";
 #const DOMSTATIC="http://static.zoominmobiliario.com/";
 const DOMEXTERNO="http://externo.zoominmobiliario.com/";
 const DOMFEEDS="http://feeds.zoominmobiliario.com/";
 const DOMCLOUD="http://cloud.zoominmobiliario.com/";
 
 ## -- Constantes RUTAS de carpetas -- ##
 const repoFonts="fonts/";
 const repoPlantillas="plantillas/";
 const repo="uploadzoom/";
 const repoUbic="/home/Zoom/sites/www/";
 const repoImagenesPropiedades="propiedades/";
 const repoImagenesProyectos="proyectos/";
 const repoImagenesEmpresas="empresas/";
 const log="/home/Zoom/sites/www/lib/logs/";
 const BOF="/tmkt/ZI/bof/";
 const ZM="zoom/";
 const IN="inmobiliario/";
 const CO="corredor/";
 const APPS="apps/";
 const APP_PROPIEDADES="apps/propiedades/";
 const APP_COMPARADOR="apps/comparador/";
 const APP_FAVORITOS="apps/favoritos/";
 const APP_LOGIN="apps/login/";
 const APP_USUARIO="apps/usr/";
 const BO_PROYECTOS="subdominios/bo/zoom/";
 
 ## -- Caracteristicas APPS -- ##
 const VIGENCIA_PROPIEDAD_VENTA=150; // DIAS
 const VIGENCIA_PROPIEDAD_ARRIENDO=90; // DIAS
 const VIGENCIA_PROPIEDAD_ARRIENDO_TEMP=60; // DIAS
 const TOTAL_PROPIEDADES_PERMITIDAS=5;
 const TOTAL_IMAGENES_PERMITIDAS=12;
 const TOTAL_IMAGENES_PERMITIDAS_CORREDOR=20;
 
 ## -- Caracteristicas para imagenes -- ##
 const anchoMax=800; //PIXELES
 const altoMax=600; //PIXELES
 const calidad=60; //PIXELES
 const thAncho=80; //PIXELES
 const thAlto=50; //PIXELES
 public $fpermitidos=array("jpg","jpeg","gif","png");
 
 ## -- CODIGOS DE ERRORES -- ##
 const ERROR_PERMISO_PUBLICAR="E64";
 const ERROR_TIPO_PROPIEDAD="E65";
 const ERROR_ID_NULO="E66";
 const ERROR_NO_PROPIETARIO="E67";
 
 ## -- GOOGLE MAPS -- ##
 const GMAPS_URL="http://maps.google.com/maps/geo?q=";
 const GMAPS_URL_COMPLEMENTO="&output=csv&oe=iso-8859-1&sensor=false&key=";
 const GMAPS_KEY="ABQIAAAAYhVDImV1lArnJp1ry0b_LBQQ1xygVf_ahWfDK9VXR42vR0EMbxRUoXpkTl-Iudg71x5ASrOk151PMQ";
 
 ## -- Plataforma desarrollo o producción -- ##
 const debug=true; // true = desarrollo - false = produccion
 
 function __construct(){}
 
 ##------------------------##
 public static function mostrarErrores($bool=self::debug){
  error_reporting(E_ALL);
  ini_set("display_errors",$bool);
 }
}
?>