<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

##-- recepcion de parametros --##
$params=lib::MISC()->checkVars($_POST);

##-- validación de datos y cookie --##
if(count($params)>0){
  $_SESSION["usr"]="";
  $resUsr=lib::DB()->myQuery("select * from usuarios where correo='".$params["usr"]."' and clave='".$params["pass"]."' limit 1;",0,1);
  if(is_array($resUsr) && count($resUsr)>0){
    $_SESSION["usr"]["id"]=$resUsr["idUsuario"];
    $_SESSION["usr"]["rol"]=$resUsr["idRol"];
    $_SESSION["usr"]["login"]="r";
    $_SESSION["usr"]["key"]=md5($resUsr["clave"]);
    
    switch($resUsr["idRol"]){
      case "1":
        if(lib::MISC()->is_empty($_SESSION["REDIRECT"])){
          header("location: ".lib::CFG(array("DOMADMIN","ZM")));
        }else{
          header("location: ".$_SESSION["REDIRECT"]);
        }
        break;
      case "2":
        if(lib::MISC()->is_empty($_SESSION["REDIRECT"])){
          header("location: ".lib::CFG(array("DOMADMIN","CO")));
        }else{
          header("location: ".$_SESSION["REDIRECT"]);
        }
        break;
      case "3":
        if(lib::MISC()->is_empty($_SESSION["REDIRECT"])){
          header("location: ".lib::CFG(array("DOMADMIN","IN")));
        }else{
          header("location: ".$_SESSION["REDIRECT"]);
        }
        break;
      case "4":
        if(lib::MISC()->is_empty($_SESSION["REDIRECT"])){
          header("location: ".lib::CFG(array("URLBASE","APP_PROPIEDADES"))."propiedades.php");
        }else{
          header("location: ".$_SESSION["REDIRECT"]);
        }
        break;
      default:
        header("location: ".lib::CFG("URLBASE"));
        break;
    }
    exit();
  }
}

##-- plantilla --##
$t= lib::TMPL("ziLogin.html");

$t->cerrar();
?>