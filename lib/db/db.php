<?php
namespace db;

use config\config as config;
use mensajes\mensajes as mensajes;

class db{
 
 var $conexion=null;

 function __construct($tipoDb="mysql"){
  
  switch($tipoDb){
   case "mysql":
    $conexion=self::myConectar();
   break;
   case "mssql":
    $conexion=self::msConectar();
   break;
  } 
  
 }
 
 ##------------------------------##
 static function myConectar(){
  $resultado	= NULL;
  try{
      if($con = mysql_connect(config::myHost,config::myUser,config::myPass)){
          if(mysql_select_db(config::myDbase,$con)){
             #mysql_set_charset('utf8',$con);
              return $con;
          }else{
              mensajes::error_db(101);
          }
      }else{
         mensajes::error_db(102);
      }
  }catch(\Exception $e){
      echo "Error: ".$e->getMessage()."";
  }
 }
 
 ##------------------------------##
 static function myDesconectar(){
  $resultado	= NULL;
   try{
       if(!mysql_close($conexion)){
         mensajes::error_db(103);
       }
   }catch(\Exception $e){
       echo "Error: ".$e->getMessage()."";
   }
 }
 
 ##------------------------------##
 static function myQuery($sqlstr,$mostrar=0,$return=0){
  
  if($conexion==null){
   $conexion=self::myConectar();
  }
  
  $res = mysql_query($sqlstr,$conexion);
  
  if($mostrar==1){
      echo mensajes::msg($sqlstr,true);
  }

  if(!$res) {
   if(config::debug){
    $_arr=debug_backtrace();
    die(mensajes::error_db(100,$sqlstr."<br>".mysql_error()."<br><br>Arc:".$_arr[0]["file"]."<br>linea:".$_arr[0]["line"]));
   }else{
    header("location: ".config::URLBASE);
    exit();
   }
  }
  
  if($return==1){ // retorna el fetch_array de la consulta, SOLO UTILIZAR PARA RESULTADOS DE 1 FILA
   $res=mysql_fetch_array($res);
  }
  
  return $res;
  #$this->cerrar_conexion($conx);
  
 }
 
 ##------------------------------##
 static function msConectar(){
  $resultado	= NULL;
  try{
      if($con = mssql_connect(config::msHost,config::msUser,config::msPass)){
          if(mysql_select_db(config::msDbase,$con)){
              return $con;
          }else{
              mensajes::error_db(101);
          }
      }else{
          mensajes::error_db(102);			
      }
  }catch(Exception $e){
      echo "Error: ".$e->getMessage()."";
  }
 }
 
 ##------------------------------##
 private function msDesconectar(){
  $resultado	= NULL;
   try{
       if(!@mssql_close($conexion)){
           mensajes::error_db(103);
       }
   }catch(Exception $e){
       echo "Error: ".$e->getMessage()."";
   }
 }
 
 ##------------------------------##
 static function msQuery($sqlstr,$mostrar=0){
  
  if($conexion==null){
   $conexion=self::msConectar();
  }
  
  $res = mssql_query($sqlstr,$conexion);
  if($mostrar==1)
      echo mensajes::msg($sqlstr);

  if(!$res) {
   if(config::debug){
    die(mensajes::error_db(100,$sqlstr."<br>".mysql_error()));
   }
   else{
    header("location: ./");
    exit();
   }
  }
  return $res;
  #$this->cerrar_conexion($conx);
  
 }
 
}
?>