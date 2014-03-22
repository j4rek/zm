<?php
namespace integrador;

use db\db as database;
use template\template as tmpl;
use mensajes\mensajes as msgs;
use correo\correo;

use misc\misc;
use html\html as html;
use imagenes\imagenes as img;

use config\config as cfg;

class integrador {
 
 static $oMisc=null;
 static $oDb=null;
 static $oHtml=null;
 static $oMsg=null;
 static $oCfg=null;
 static $oImg=null;
 function __construct(){
 }
 
 ##-- FUNCIONES DE INSTANCIA --##
 
 ##------------------------------##
 static function DB(){
  if(self::$oDb==null){
   self::$oDb=new database; 
  }
  return self::$oDb;
 }
 
 ##------------------------------##
 static function HTML(){
  if(self::$oHtml==null){
   self::$oHtml=new html; 
  }
  return self::$oHtml;
 }
 
 ##------------------------------##
 static function MSGBOX(){
  if(self::$oMsg==null){
   self::$oMsg=new msgs; 
  }
  return self::$oMsg;
 }
 
 ##------------------------------##
 static function TMPL($plantilla){
  $tmpl=new tmpl(cfg::repoPlantillas);  
  $tmpl->set_file("plantilla",$plantilla); 
  return $tmpl;
 }
 
 ##------------------------------##
 static function MISC(){
  if(self::$oMisc==null){
   self::$oMisc=new misc;
  }
  return self::$oMisc;
 }
 
 ##------------------------------##
 static function IMG(){
  if(self::$oImg==null){
   self::$oImg=new img;
  }
  return self::$oImg;
 }
 
 ##------------------------------##
 static function CFG($CONST=""){
  $_cfg="";
  if(self::$oCfg==null){
   self::$oCfg=new \ReflectionClass("config\config");
  }
    
  if($CONST==null){
   $_cfg=new cfg();
  }else{
   if($CONST==""){
    $_cfg=self::$oCfg->getConstants();
   }elseif(!is_array($CONST)){
    $tmp=self::$oCfg->getConstants();
    $_cfg=$tmp[$CONST];
   }elseif(is_array($CONST)){
    $tmp=self::$oCfg->getConstants();
    foreach($CONST as $key){
     $_cfg.=$tmp[$key];
    }
   } 
  }
    
  return $_cfg;
 }
 
 ##------------------------------##
 static function MAIL($from, $to, $subject, $body){
  $mail=new correo($from, $to, $subject, $body);
  $mail->SendMail();
 }
 
 ##------------------------------##
 
 ##-- FUNCTIONES ANEXAS --##
 public static function mostrarErrores(){
  cfg::mostrarErrores(true);
 }
 ##------------------------------##
 public static function dump($var){
  echo "<pre>";
  var_dump($var);
  echo "</pre>";
 }
 ##------------------------------##
}

?>