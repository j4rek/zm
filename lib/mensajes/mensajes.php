<?php
namespace mensajes;

class mensajes {
 
 const format_alerta      ="<div style='margin-top:1px;z-index:200000;position:relative;background-color:#fdea60;font-size:14px;color:black;font-family:arial;padding:5px;border-radius:4px;'>{msg}</div>";
 const format_confirmar   ="<div style='margin-top:1px;z-index:200000;position:relative;background-color:#aac34e;font-size:14px;color:white;font-family:arial;padding:5px;border-radius:4px;'>{msg}</div>";
 const format_error_script="<div style='margin-top:1px;z-index:200000;position:relative;background-color:#ca1212;font-size:14px;color:white;font-family:arial;padding:5px;border-radius:4px;'>{msg}</div>";
 const format_error_db    ="<div style='margin-top:1px;z-index:200000;position:relative;background-color:#0085b0;font-size:14px;color:white;font-family:arial;padding:5px;border-radius:4px;'>{msg}</div>";
 const format_estandar    ="<div style='margin-top:1px;z-index:200000;position:relative;background-color:#e28903;font-size:14px;color:white;font-family:arial;padding:5px;border-radius:4px;'>{msg}</div>";
 
 static public $_errores=array("e100"=>"Error query: <br/>",
                          "e101"=>"Error al momento de seleccionar la Base de Datos! <br />",
                          "e102"=>"Error en la conexion con el Servidor! <br />",
                          "e103"=>"Error al momento de cerrar la conexion con el Servidor! <br />",
                          "e104"=>"Error en parametro!.<br />");
 
 function __construct(){
   
 }
 
 ##------------------------------##
 public static function alerta($mensaje="",$adicional=""){
  if($mensaje){
   if(is_numeric($mensaje)){
   $mensaje="e".$mensaje;   
   echo str_replace("{msg}",self::$_errores[$mensaje]." ".$adicional,self::format_alerta);
   }else{
    echo str_replace("{msg}",$mensaje,self::format_alerta);
   }
  }
 }
 
 ##------------------------------##
 public static function confirmar($mensaje="",$adicional=""){
  if($mensaje){
   if(is_numeric($mensaje)){
   $mensaje="e".$mensaje;
   echo str_replace("{msg}",self::$_errores[$mensaje]." ".$adicional,self::format_confirmar);
   }
   else{
    echo str_replace("{msg}",$mensaje,self::format_confirmar);
   }
  }
 }
 
 ##------------------------------##
 public static function error_script($mensaje="",$adicional=""){
  if($mensaje){
   if(is_numeric($mensaje)){
   $mensaje="e".$mensaje;
   echo str_replace("{msg}",self::$_errores[$mensaje]." ".$adicional,self::format_error_script);
   }else{
    echo str_replace("{msg}",$mensaje,self::format_error_script);
   }
  }
 }
 
 ##------------------------------##
 public static function error_db($mensaje=false,$adicional=""){
  if($mensaje){
   if(is_numeric($mensaje)){
   $mensaje="e".$mensaje;
   echo str_replace("{msg}",self::$_errores[$mensaje]." ".$adicional,self::format_error_db);
   }else{
    echo str_replace("{msg}",$mensaje." ".$adicional,self::format_error_db);
   }
  }
 }
 
 ##------------------------------##
 public static function msg($mensaje=false){
  if($mensaje){
   echo str_replace("{msg}",$mensaje,self::format_estandar);
  }
  else{
   self::alerta("Error en parametro");
  }
 }
 
}
?>