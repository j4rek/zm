<?php
namespace misc;

use config\config;
use db\db as database;
use mensajes\mensajes as msgs;

// se establece la conexion a la base de datos, para utilizar mysql_real_escape_string en ciertas funciones
database::myConectar(); 

class misc {
 function __construct(){
  
 }
 
 ##------------------------------##
 public static function iconv($str){
  return \iconv("ISO-8859-1","UTF-8",$str);
 }
 
 ##------------------------------##
 public static function checkVars($data){
   $arreglo=array();
   foreach($data as $key =>$valor){
    if(is_array($valor)){
     foreach($valor as $ind => $val){
      $_tmp[$ind]=self::mysql_clean_string(mysql_real_escape_string($val));
     }
     $data[$key]=$_tmp;
     unset($_tmp);
    }else{
     $data[$key]=self::mysql_clean_string(mysql_real_escape_string($valor));
    }
   }
   $arreglo=$data;
   
   
  return $arreglo;
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
 
 ##------------------------------##
 public static function generaLog($numero, $texto){ // genera un archivo .Log para eventos
   $realtime =  config::log."debug.log";
   $ddf = fopen($realtime,'a');
   fwrite($ddf,"[".date("r")."] $numero: $texto \r\n");
   fclose($ddf);
 }
 
 ##------------------------------##
 public static function log($idCustom, $tabla,$sql){
  if(!self::is_empty($_SESSION["usr"]["id"])){
   database::myQuery("insert into logs_detalle set
                     idUsuario='".$_SESSION["usr"]["id"]."',
                     idCustom='$idCustom',
                     idTipo='6',
                     seccion='bo',
                     tabla='$tabla',
                     descripcion='".addslashes(str_replace("\n","",$sql))."',
                     fecha='".date("Y-m-d H:i:s")."';");
  }
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
 public static function valnulo($cadena,$mensaje=""){ /***Retorna $mensaje si $cadena=nulo***/
   $str="";
   if(!self::emptyVal($cadena))
    $str=$mensaje;
   else
    $str=$cadena;

   return $str;
 }
 
 ##------------------------------##
 public static function emptyVal($value){/***Retorna TRUE si el valor es distinto de vacio***/
   $bool=false;
   if(isset($value) && !is_null($value) && $value!="" && $value!=" ")
       $bool= true;
   
   return $bool;
 }
 
 ##------------------------------##
 public static function mysql_clean_string($variable){ // busca palabras reservadas de sql y javascript
  $palabras=array("select","update","insert","delete","drop table","drop database","alter table","alter database","sleep (","sleep(","grant","window.","<script","< script","<iframe","< iframe","prompt(","prompt (","alert(","alert (","console.","document.");
  $found=false;
  foreach($palabras as $valor){
   if(stripos($variable,$valor)!==false){
     $found=true;
   }
  }
  if($found==true){
    if(config::debug){
      msgs::alerta("parametros no validos");    
    }else{
      self::redirect();
    }
  }else{
   return $variable; 
  }
 }
 
 ##------------------------------##
 public static function redirect($destino=""){
  if(self::is_empty($destino)){
    header("location: ".config::URLBASE);
  }else{
   header("location: ".$destino);
  }
  
   exit();
 }
 
 ##------------------------------##
 public static function valida_rut($r){
  $r=strtoupper(ereg_replace('\.|,|-','',$r));
  $sub_rut=substr($r,0,strlen($r)-1);
  $sub_dv=substr($r,-1);
  $x=2;
  $s=0;
  for ( $i=strlen($sub_rut)-1;$i>=0;$i-- ) {
      if ( $x >7 ){
          $x=2;
      }
      $s += $sub_rut[$i]*$x;
      $x++;
  }
  $dv=11-($s%11);
  if ( $dv==10 ){
      $dv='K';
  }
  if ( $dv==11 ){
      $dv='0';
  }
  if ( $dv==$sub_dv ){
      return true;
  } else {
      return false;
   }
 }

 ##------------------------------##
 public static function cuantas_props_publicadas($idUsuario){
   $res = database::myQuery("select count(*) from propiedades where idUsuario = '$idUsuario' and idEstado='3' and visible='1' and fechaFin > now();",0,1);
   
   return $res[0];
 }
 
 ##------------------------------##
 public static function cuantas_props_registradas($idUsuario){
   $res = database::myQuery("select count(*) from propiedades where idUsuario = '$idUsuario' and idEstado in (1,3);",0,1);
   
   return $res[0];
 }
 
 ##------------------------------##
 public static function permitePublicar(){
  $_bool=true;
  if(!isset($_SESSION["usr"]["id"])){
   $_bool=false;
  }else{
   if(self::cuantas_props_registradas($_SESSION["usr"]["id"])>=config::TOTAL_PROPIEDADES_PERMITIDAS){
    $_bool=false;
   }
  }
  
  return $_bool;
 }
 
 ##------------------------------##
 public static function props_xAprobar(){
  $res=database::myQuery("select count(*)as num from propiedades_copy where idEstado='1' and visible='0' and idEmpresa='0' and idUsuario<>'0' and fechaFin>='".date("Y-m-d")."';",0,1);
  
  return $res[0];
 }
 
 ##------------------------------##
 public static function ultimosDias($dias){
  $timestamp=time() - ($dias * 24 * 60 * 60);
  $dia=date("d",$timestamp);
  $mes=date("m",$timestamp);
  $año=date("Y",$timestamp);
  
  return $año."-".$mes."-".$dia;
 }
 
 ##------------------------------##
 public static function proy_vencidos($dias){
  $totales=Array();
  $fecha=self::ultimosDias($dias);
  
  while($dias>0){
   $total=database::myQuery("select count(*) from proyectos where fechaFin='$fecha';",0,1);
   array_push($totales,array("total"=>$total[0],"fecha"=>self::dia($fecha)." ".substr($fecha,8)));
   $dias--;
   $fecha=date("Y-m-d",(strtotime($fecha) + (1 * 24 * 60 * 60)));
  }
    
  return $totales;
 }
 
 ##------------------------------##
 public static function ultimas_cotizaciones($dias){
  $totales=Array();
  $fecha=self::ultimosDias($dias);

  while($dias>0){
   $total=database::myQuery("SELECT count(*) FROM contactosWeb WHERE cwFechaContacto LIKE '%$fecha%' and cwTipo='101';",0,1);
   array_push($totales,array("total"=>$total[0],"fecha"=>self::dia($fecha)." ".substr($fecha,8)));
   $dias--;
   $fecha=date("Y-m-d",(strtotime($fecha) + (1 * 24 * 60 * 60)));
  }
    
  return $totales;
 }

 ##------------------------------##
 public static function proy_mas_cotizados($fecha,$num=3){
  $totales=Array();

  $rProyMas=database::myQuery("SELECT COUNT(*)as total, cwidTipo FROM contactosWeb WHERE cwFechaContacto LIKE '%$fecha%' AND cwTipo='101' GROUP BY cwidTipo order by total desc limit $num;");
  while($row=mysql_fetch_array($rProyMas)){
   array_push($totales,array("total"=>$row["total"],"np"=>self::strProyecto($row["cwidTipo"])));
  }
  
  return $totales;
 }
 
 ##------------------------------##
 public static function proy_menos_cotizados($fecha,$num=3){
  $totales=Array();

  $rProyMenos=database::myQuery("SELECT COUNT(*)as total, cwidTipo FROM contactosWeb WHERE cwFechaContacto LIKE '%$fecha%' AND cwTipo='101' GROUP BY cwidTipo order by total limit $num;");
  while($row=mysql_fetch_array($rProyMenos)){
   array_push($totales,array("total"=>$row["total"],"np"=>self::strProyecto($row["cwidTipo"])));
  }
  
  return $totales;
 }
 
 ##------------------------------##
 public static function strProyecto($id){
  $resProy=database::myQuery("select * from proyectos where idProyecto='$id' limit 1;",0,1);
  
  return $resProy["nombre"];
 }
 
 ##------------------------------##
 public static function dame_total($id_definicion, $id_custom ){
    $res = database::myQuery("select numerototal from totales where id_definicion = '$id_definicion' and id_custom = '$id_custom' limit 1",0,1);
    
    return $res["numerototal"];
 }
 
 ##------------------------------##
 public static function valorDivisa($divisa){
  $res = database::myQuery("select valor from indicadores where id_indicador='$divisa' limit 1;",0,1);
  
  return str_replace(",",".",str_replace(".","",$res[0]));
 }
 
 ##------------------------------##
 public static function formatoNombre($nombres,$apellidos)
 {
   $nombres=explode(" ",$nombres);
   $apellidos=explode(" ",$apellidos);

   $nombre=$nombres[0]." ".$apellidos[0];
   return $nombre;
 }
 
 ##------------------------------##
 public static function dia($fecha){
  $nombre="";
  $dia=date("D",strtotime($fecha));
  
  switch(strtolower($dia)){
   case "mon":$nombre="Lunes";break;
   case "tue":$nombre="Martes";break;
   case "wed":$nombre="Miercoles";break;
   case "thu":$nombre="Jueves";break;
   case "fri":$nombre="Viernes";break;
   case "sat":$nombre="Sabado";break;
   case "sun":$nombre="Domingo";break;
  }
  
  return $nombre;
 }
 
 ##------------------------------##
 public static function mes($fecha,$op="l"){
  $nombre="";
  $mes=date("M",strtotime($fecha));
  
  switch(strtolower($mes)){
   case "jan":$nombre=($op=="l")?"Enero":"Ene";break;
   case "feb":$nombre=($op=="l")?"Febrero":"Feb";break;
   case "mar":$nombre=($op=="l")?"Marzo":"Mar";break;
   case "apr":$nombre=($op=="l")?"Abril":"Abr";break;
   case "may":$nombre=($op=="l")?"Mayo":"May";break;
   case "jun":$nombre=($op=="l")?"Junio":"Jun";break;
   case "jul":$nombre=($op=="l")?"Julio":"Jul";break;
   case "aug":$nombre=($op=="l")?"Agosto":"Ago";break;
   case "sep":$nombre=($op=="l")?"Septiembre":"Sep";break;
   case "oct":$nombre=($op=="l")?"Octubre":"Oct";break;
   case "nov":$nombre=($op=="l")?"Noviembre":"Nov";break;
   case "dec":$nombre=($op=="l")?"Diciembre":"Dic";break;
  }
  
  return $nombre;
 }
 
 ##------------------------------##
 public static function checked($stat){
   $check=null;
   if($stat==1)	
       $check="checked";
   else
       $check="";

   return $check;
 }
 
 ##------------------------------##
 public static function strSiNo($var){
     $ans="";
     if($var==1 || $var==true) $ans="SI";
     else $ans="NO";
     
     return $ans;
 }
 
 ##------------------------------##
 public static function validaNumerico($data){
  $bool=true;
  foreach($data as $key =>$value){
   if(!is_numeric($data)){
    $bool=false;
   }
  }
  
  return $bool;
 }
 
 ##------------------------------##
 public static function is_empty($value){/***Retorna TRUE si el valor es vacio***/
    if(!isset($value) || is_null($value) || trim($value)==""){
     return true;  
    }else{
     return false;
    }
 }
 
 ##------------------------------##
 public static function strTpEmpresa($tipo){
  $tipoEmpresa="";
  switch($tipo):
   case 1: $tipoEmpresa="Inmobiliaria";
    break;
   case 2: $tipoEmpresa="Corredora";
    break;
  endswitch;
 
  return $tipoEmpresa;
 }
 
 ##------------------------------##
 public static function urlExist($url){
   $url=str_replace("./","/",$url);
   $stat=get_headers($url, 1);
   
   #self::generaLog("url:",$url);
   
   if($stat[0]=="HTTP/1.1 404 Not Found"){
       return false;
   }elseif($stat[0]=="HTTP/1.1 200 OK"){
       return true;
   }
 }
 
 ##------------------------------##
 public static function dame_datos_usuario($ID,$campos=""){
  $resUsr=database::myQuery("select * from usuarios where idUsuario='$ID' limit 1;",0,1);
  
  switch($campos):
   case "nombres": $valor=$resUsr["nombre"];
    break;
   case "rut": $valor=$resUsr["rut"];
    break;
   case "correo": $valor=$resUsr["correo"];
    break;
   case "empresa": $valor=$resUsr["idEmpresa"];
    break;
   case "rol": $valor=$resUsr["idRol"];
    break;
   case "nombreCompleto": $valor=$resUsr["nombre"];
    break;
   case "nombresCorto": $valor=self::formato_nombre($resUsr["nombre"]);
    break;
   case "avatar":$valor=$resUsr["avatar"];
    break;
   default: $valor=$resUsr;
    break;
   
  endswitch;
  return $valor;
 }
 
 ##-----------------------------##
 public static function dame_datos_empresa($ID,$campos=""){
  $resUsr=database::myQuery("select * from empresas where idEmpresa='$ID' limit 1;",0,1);
    
  switch($campos):
   case "nombre": $valor=$resUsr["nombre"];
    break;
   case "rut": $valor=$resUsr["rut"];
    break;
   case "tipo": $valor=self::strTpEmpresa($resUsr["idTipo"]);
    break;
   default: $valor=$resUsr;
    break;
   
  endswitch;
  
  return $valor;
 }
 
 ##-----------------------------##
 public static function dame_datos_proyecto($ID,$campos=""){
  $resUsr=database::myQuery("select * from proyectos where idProyecto='$ID' limit 1;",0,1);
    
  switch($campos):
   case "nombre": $valor=$resUsr["nombre"];
    break;
   default: $valor=$resUsr;
    break;
   
  endswitch;
  
  return $valor;
 }
  
 ##------------------------------##
 public static function formato_nombre($_nombre){
   $nombres=explode(" ",$_nombre);

   $nombre=$nombres[0]." ".$nombres[2];
   return $nombre;
 }
 
 ##------------------------------##
 public static function checkIcon($bool){
  if($bool==1 || $bool==true){
   $ans="<img src=\"images/check.png\" class=\"check\" />";
  }else{
   $ans="<img src=\"images/checkNo.png\" class=\"check\" />";
  }
  
  return $ans;
 }
 
 ##------------------------------##
 public static function dame_tipoPropiedad( $id_tipo ){
  $resTp=database::myQuery("select tipo from propiedadesTipos where idTipo='$id_tipo' limit 1;",0,1);
  
  return $resTp[0];
 }
 
 ##------------------------------##
 public static function dame_nombre_comuna($comuna){
  $resCom=database::myQuery("select comuna from comunas where idComuna='$comuna' limit 1;",0,1);
  
  return $resCom[0];
 }
 
 ##------------------------------##
 public static function dame_tipo_operacion($propOperacion){
  $resOp=database::myQuery("select Operacion from operaciones where idOperacion='$propOperacion' limit 1;",0,1);
  
  return $resOp[0];
 }
 
 ##------------------------------##
 public static function corta_texto($texto, $largo) {
    // funcion que corta texto y agrega "..." al final, pero
    // solo si el texto a cortar es mas largo que el largo a dejar.
    $largoTotal = strlen($texto);
    if ($largoTotal > $largo) { // solo si largo de string es mayor a largo a dejar.
        $texto = substr( $texto, 0, $largo);
        $pos = strrpos($texto, " "); // busco ultimo espacio para alicar corte ahi
        $texto = substr( $texto, 0, $pos);
        $texto .= "...";
    }
    return $texto;
 }
 
 ##------------------------------##
 public static function strRol($ID){
  $resRol=database::myQuery("select rol from usuariosRoles where idRol='$ID' limit 1;",0,1);
  
  return $resRol[0];
 }
 
 ##------------------------------##
 public static function EMPRESA(){
  $_idEmpresa=self::dame_datos_usuario($_SESSION["usr"]["id"],"empresa");
  $nomEmpresa=self::dame_datos_empresa($_idEmpresa,"nombre");
  
  return $nomEmpresa."<span class=\"gentypo ico-medium\">&#9776;</span>";
 }
 
 ##------------------------------##
 public static function formatoFecha($datetime,$op="f"){
  $val="";
  $d=date("d", strtotime($datetime));
  $_d=self::dia($datetime);
  
  $m=date("m", strtotime($datetime));
  $m_=self::mes($datetime);
  $mc=self::mes($datetime,"c");
  
  $a=date("Y", strtotime($datetime));
  $_a=date("y", strtotime($datetime));
  
  $h=date("H", strtotime($datetime));
  $_m=date("i", strtotime($datetime));

  switch($op){
   case "f":
     $val=$d."-".$m."-".$_a;
    break;
   case "F":
    $val=$d."-".$m."-".$a;
    break;
   case "Fh":
    $val=$d."-".$m."-".$a." ".$h.":".$_m;
    break;
   case "fh":
    $val=$d."-".$m."-".$_a." ".$h.":".$_m;
    break;
   case "Fl":
    $val=$_d." ".$d." de ".$m_." del ".$a;
    break;
   case "fl":
    $val=$d." ".$mc." del ".$a;
    break;
  }
  
  return $val;
 }
 
 ##-------------------------------##
 public static function fechaProxima($dias){
   $fecha="";
   if(self::emptyVal($dias) && is_numeric($dias))
     $fecha=date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+$dias, date("Y") ));
   else
     msgs::alerta(104);

   return $fecha;
 }
 
 ##-------------------------------##
 public static function checkRef($referer,$string,$redirect=true ){
	$_bool=false;
  $pos = stripos( $referer, $string);
  
  if($pos === false){
    $_bool = false;
  }else{
    $_bool = true;
  }
  if($_bool===false && $redirect===true){
   self::redirect();
  }
  
  return $_bool;
 }
 
 ##-------------------------------##
 public static function valUsuario($tipoRol,$redirect=true){
  $_bool=true;
  $_valid=true;
  if(!isset($_SESSION["usr"]["id"])){
   $_bool=false;
  }else{
   $resUs=database::myQuery("select * from usuarios where idUsuario='".$_SESSION["usr"]["id"]."' and idRol='".$_SESSION["usr"]["rol"]."' and idEstado='4' limit 1;",0,1);
   
   if(is_array($tipoRol)){
    foreach($tipoRol as $key => $tipo){  
     if($tipo!=$resUs["idRol"]){
      $_bool=false;
      $_valid=false;
     }else{
      $_bool=true;
      $_valid=true;
     }
    }
   }else{
    if($tipoRol!=$resUs["idRol"]){
     $_bool=false;
     $_valid=false;
    }
   }
   
   if($_SESSION["usr"]["login"]=="r"){
    if($_SESSION["usr"]["key"]!=md5($resUs["clave"])){
     $_bool=false;
    }
   }elseif($_SESSION["usr"]["login"]=="f"){
    if($_SESSION["usr"]["key"]!=$resUs["fb_session"]){
     $_bool=false;
    }
   }
  }
  if($_bool==false){
   if($redirect){
    if($_valid){
     self::redirect(config::URLBASE.config::APP_LOGIN."ziLogin.php");
    }else{
     $_SESSION["REDIRECT"]="";
     self::redirect(config::URLBASE."?inv");
    }
    
   }else{
    exit();
   }
  }
 }
 
 ##------------------------------##
 public static function getValorTag($tag,$cadena){
     $valor="";
     $cadena=strtolower($cadena);
     $tagC=(!self::is_empty($tag))?str_replace("<","</",$tag):"fail";
     
     if(!self::is_empty($cadena)){
      if(!self::is_empty($tag)){
         $valor=substr($cadena,strrpos($cadena,$tag)+strlen($tag),(strrpos($cadena,$tagC) - (strrpos($cadena,$tag)+strlen($tag))));
      }
     }
     return $valor;
 }
 
 ##-------------------------------##
 public static function obtiene_geolocalizacion( $str_direccion ){
  // armo string de googlemaps
  $link = config::GMAPS_URL . $str_direccion . config::GMAPS_URL_COMPLEMENTO;
  // nueva forma
  $handle = @fopen($link, "rb");
  $contents = "";
  do {
      $data = @fread($handle, 18192);
      if (strlen($data) == 0) {
          break;
      }
      $contents .= $data;
  } while(true);
  @fclose ($handle);
  $texto = $contents;
  // fin nueva forma
  $largo_str = strlen($texto);
 
  $json=json_decode($texto,true);
  
  return $json;
 }
 
 ##-------------------------------##
 public static function dame_datos_planta($idPlanta,$campo=""){
  $res=database::myQuery("select * from proyectosPlantas where idPlanta='$idPlanta' limit 1;",0,1);
  
  switch($campo){
   case "nombre":
    $field=$res["nombrePlanta"];
    break;
   default:
    $field=$res;
    break;
  }
  
  return $field;
 }
 
 ##-------------------------------##
 public static function propiedad_proyecto($idProp,$campo=""){
  $res=database::myQuery("select * from proyectosPropiedades where idPlantaProp='$idProp' limit 1;",0,1);
  
  switch($campo){
   case "numero":
    $field=$res["numero"];
    break;   
   default:
    $field=$res;
    break;
  }
  
  return $field;
 }
 
}
?>