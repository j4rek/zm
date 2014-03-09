<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

## valida usuario logeado ##
lib::MISC()->valUsuario(4);

##-- recepcion de parametros --##
$params=lib::MISC()->checkVars($_POST);
##-- actualizacion imagenes --##

## imagenes eliminadas para borrar en disco ##
if(is_array($params["DE"]) && count($params["DE"])>0){
  foreach($params["DE"] as $key => $valor){
    $file=explode("-",$valor);
    if(trim($file[0])==trim($params["ID"])){
      #echo "equals<br/>";
      $result=lib::DB()->myQuery("select * from propiedades_copy where idPropiedad='".$file[0]."' and idUsuario='".$_SESSION["usr"]["id"]."' limit 1;",0,1);
      if(strpos($result["imagenes"],$valor)!==false){
        #echo "found<br/>";
        if(file_exists(lib::CFG(array("repoUbic","repo","repoImagenesPropiedades"))."usuarios/".$valor)){
          #echo "<br>a eliminar".$valor;
          #unlink(lib::CFG(array("repoUbic","repoImagenesPropiedades"))."usuarios/".$valor);
          lib::DB()->myQuery("insert into _limpiezaIMG set idUsuario='".$_SESSION["usr"]["id"]."', idPropiedad='$file[0]', ruta='".lib::CFG(array("repoUbic","repo","repoImagenesPropiedades"))."usuarios/".$valor."',fecha='".date("Y-m-d H:i:s")."';");
          lib::DB()->myQuery("insert into _limpiezaIMG set idUsuario='".$_SESSION["usr"]["id"]."', idPropiedad='$file[0]', ruta='".lib::CFG(array("repoUbic","repo","repoImagenesPropiedades"))."usuarios/th-".$valor."',fecha='".date("Y-m-d H:i:s")."';");
        }
      }
    }
  }
}

## nuevo orden imagenes ##
$updImagen="";
if(is_array($params["AC"]) && count($params["AC"])>0){
  foreach($params["AC"] as $key => $valor){
    $updImagen.=$valor.";";    
  }
  $updImagen=substr($updImagen,0,-1);
  lib::DB()->myQuery("update propiedades_copy set imagenes='$updImagen' where idPropiedad='".$params["ID"]."' limit 1;");
}elseif($params["UPD"]!=1){
  lib::DB()->myQuery("update propiedades_copy set imagenes='' where idPropiedad='".$params["ID"]."' limit 1;");
}

##-- contenido --##
$rsimgs=lib::DB()->myQuery("select * from propiedades_copy where idPropiedad='".$params["ID"]."' and idUsuario='".$_SESSION["usr"]["id"]."';",0,1);
$imagenes=explode(";",$rsimgs["imagenes"]);
if($imagenes[0]!=""){
  foreach($imagenes as $key => $img){
    $src=lib::CFG(array("URLBASE","repo","repoImagenesPropiedades"))."usuarios/".$img;
    echo "<li class=\"blkimg\" rel=\"".$img."\">
            <div>     
          <a rel=\"gal\" href=\"$src\">
          <img src=\"$src\" />
          </a></div></li>";
  }
}
?>