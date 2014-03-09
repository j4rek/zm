<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

## valida usuario logeado ##
lib::MISC()->valUsuario(4);

$t=lib::TMPL("publicar.imagenes.html");

##-- recepcion de parametros --##
$params=lib::MISC()->checkVars($_GET);

##-- contenido --##

## extraer datos de la propipedad para completar el formulario ##
## valida al usuario contra propiedad ##
$prop=lib::DB()->myQuery("select * from propiedades_copy where idPropiedad='".$params["ID"]."' and idUsuario='".$_SESSION["usr"]["id"]."' limit 1;",0,1);

if(!$prop){
  header("location: ".(lib::CFG(array("URLBASE","APP_PROPIEDADES")))."propiedades.php?".(lib::CFG("ERROR_NO_PROPIETARIO")));
  exit();
}

$t->set_block("plantilla","imgs","_imgs");
$cont=0;
$_imagenes=explode(";",$prop["imagenes"]);

#lib::dump($_imagenes);
##-- listado de imagenes --##
if($_imagenes[0]!=""){
  foreach($_imagenes as $p => $img){
   $cont++;
   $t->set_var("src",lib::CFG(array("URLBASE","repo","repoImagenesPropiedades"))."usuarios/".$img);
   $t->set_var("IDIM",$img);
   $t->parse("_imgs","imgs",true);
  }
}

$dataPlantilla=array("ID"=>$params["ID"],
                     "_MAX"=>lib::CFG("TOTAL_IMAGENES_PERMITIDAS"),
                     "_TOTAL"=>$cont,
                     "hide"=>(($cont>0)?"":"style=\"display:none;\""),
                     "static"=>lib::CFG("DOMSTATIC"),
                     "HEADER"=>lib::HTML()->apps_header(),
                     "FOOTER"=>lib::HTML()->apps_footer());
$t->set_var($dataPlantilla);

$t->cerrar();
?>