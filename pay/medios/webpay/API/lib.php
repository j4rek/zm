<?php
/*
 * CLASE lib
 * =========
 * 
 * conjunto de funciones para realizar pagos web
 * 
 *
 */
require_once("API/config.inc.php");
define("DEBUG",true);
class lib {
 
 static $CONX=null;
 static $mysql_host="localhost";
 static $mysql_user="zoomchil_upagos";
 static $mysql_pass="^JKETB!#yCMZ";
 static $mysql_DB="zoomchil_pagos";
 
 function __construct(){
  self::$CONX=self::conexion_db();
 }
 
 ## -- BASE DE DATOS -- ##
 public static function conexion_db(){
     $resultado	= NULL;
     try{
         if($conexion = @mysql_connect(self::$mysql_host,self::$mysql_user,self::$mysql_pass)){
             if(@mysql_select_db(self::$mysql_DB,$conexion)){
                 return $conexion;
             }else{
                 $resultado = "Error al momento de seleccionar la Base de Datos! <br />";
                 throw new Exception($resultado);
                 echo $resultado;					
             }
         }else{
             $resultado = "Error en la conexion con el Servidor! <br />";
             throw new Exception($resultado);
             echo $resultado;				
         }
     }catch(Exception $e){
         echo "Error: ".$e->getMessage()."";
     }
 }
 
 ##---------------------------##
 public static function cerrar_conexion($conexion){
     $resultado	= NULL;
     try{
         if(!@mysql_close($conexion)){
             $resultado = "Error al momento de cerrar la conexion con el Servidor! <br />";
             throw new Exception($resultado);
             echo $resultado;
         }
     }catch(Exception $e){
         echo "Error: ".$e->getMessage()."";
     }
 }
 
 ##---------------------------##
 public static function qry($sqlstr,$mostrar=0,$return=0){
     if(self::$CONX==null){
      self::$CONX=self::conexion_db(); 
     }
     
     $res = mysql_query($sqlstr,self::$CONX);
     
     if($mostrar==1)
         echo "<span style=\"background:black;color:#55FF55;font-weight:bold;width:100%;float:left;\">".$sqlstr."</span><br>";

     if(!$res){
         if(DEBUG){
             die('Error en Consulta: </br>' . mysql_error()."</br><span style='color:#2d85d7;'>".$sqlstr."</span>");
         }else{
           header("location: ./");
         }
         exit;
     }
     
     if($return==1){
      $res=mysql_fetch_array($res);
     }
     
     return $res; 
 }
 ##---------------------------##
 
 ## -- FUNCINONES -- ##
 public static function mysql_clean_string($variable,$redirect=true){
  $palabras=array("select","update","insert","delete","drop table","drop database","alter table","alter database","sleep (","sleep(","grant");
  $found=false;
  foreach($palabras as $key => $valor){
   if(stripos($variable,$valor)!==false){
     $found=true;
   }
  }
  
  if($found==true){
    if($redirect===false){
      $variable=false;
    }else{
      self::redirect();
    }
  }
  
  return $variable; 
 }
 
 ##------------------------------##
 public static function redirect($destino=""){
  if($destino==""){
     header("location: ".URLBASE);
  }else{
    header("location: ".$destino);
  }
   
   exit();
 }
 
 ##------------------------------##
 public static function friendlyURL($texto){
     $resultado = strtolower($texto);
     $resultado = preg_replace("/[^a-z0-9\s-]/", "", $resultado);
     $resultado = trim(preg_replace("/[\s-]+/", " ", $resultado));
     $resultado = preg_replace("/\s/", "-", $resultado);
     return $resultado;
 }
 
 ##------------------------------##
 public static function set_data($obj=null,$data=array()) {
     if($obj!=null && !is_array($obj)){
         if(is_array($data)){
             foreach($data as $key =>$value){
                 $obj->set_var($key,$value);
             }
         }
     }else{		
         if(DEBUG){
           #msgs::alerta("error set_data");
         }
     }
 }
 
 ##------------------------------##
 public static function checkVars($data,$redirect=true){
  self::$CONX=self::conexion_db();
  $arreglo=array();
  foreach($data as $key =>$valor)
  {
   $_temp=self::mysql_clean_string(mysql_real_escape_string($valor),$redirect);
   if($_temp===false){
    $data="_NOVALIDO_";
    break;
   }else{
    $data[$key]=$_temp;
   }
  }
  
  if($data=="_NOVALIDO_"){
   $data=false;    
  }
   
  return $data;
 }
 
 ##------------------------------##
 public static function dameParametros( $uri ){
     $array = explode("/",$uri);
     $num = count($array);
     $arreglo_variables = array();

     $base = SUBCARPETA_HOME + 2;

     $i = $base;

     while ($i < $num){
         if (trim($array[$i]) != "")
             $arreglo_variables[ $array[$i] ] = mysql_real_escape_string($array[$i+1]);
             
         $i = $i + 2;
     }
     return $arreglo_variables;
 }
 
}
?>