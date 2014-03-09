<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

##-- validar usuario --##
lib::MISC()->valUsuario(4);

##-- plantilla --##
$t=lib::TMPL("propiedades.html");
##-- listado de propiedades --##
$resP=lib::DB()->myQuery("select * from propiedades_copy where idUsuario='".$_SESSION["usr"]["id"]."' order by idPropiedad desc;",0);
$t->set_block("plantilla","pendientes","pendientes_");
$t->set_block("plantilla","activas","activas_");
$t->set_block("plantilla","caducadas","caducadas_");
$t->set_block("plantilla","borradas","borradas_");
while($rowp=mysql_fetch_array($resP)){
  $_pendiente=false;
  $_activa=false;
  $_caducada=false;
  $_borrada=false;
  if($rowp["idEstado"]==1){
    $t->set_var("p_IDPROPIEDAD",$rowp["idPropiedad"]);
    $t->set_var("p_tipo",lib::MISC()->dame_tipoPropiedad($rowp["idTipo"]));
    $t->set_var("p_precio",(($rowp["precioIdDivisa"]==1)?"$ ".$rowp["precioPesos"]:(($rowp["precioIdDivisa"]==2)?"UF ".$rowp["precioUF"]:"")));
    $t->set_var("p_operacion",lib::MISC()->dame_tipo_operacion($rowp["idOperacion"]));
    $t->set_var("p_fechaFin",$rowp["fechaFin"]);
    #$t->set_var("p_editar","<a href='".lib::CFG(array("URLBASE","APP_PROPIEDADES"))."publicar.editar.php?ID=".$rowp["idPropiedad"]."'>Editar</a>");
    $t->set_var("p_eliminar","<a href='".lib::CFG(array("URLBASE","APP_PROPIEDADES"))."propiedades.php?ID=".$rowp["idPropiedad"]."&b=1'>Eliminar</a>");
    $_pendiente=true;
  }
  
  if($rowp["idEstado"]==3){
    $t->set_var("a_IDPROPIEDAD",$rowp["idPropiedad"]);
    $t->set_var("a_tipo",lib::MISC()->dame_tipoPropiedad($rowp["idTipo"]));
    $t->set_var("a_precio",(($rowp["precioIdDivisa"]==1)?"$ ".$rowp["precioPesos"]:(($rowp["precioIdDivisa"]==2)?"UF ".$rowp["precioUF"]:"")));
    $t->set_var("a_operacion",lib::MISC()->dame_tipo_operacion($rowp["idOperacion"]));
    $t->set_var("a_fechaFin",$rowp["fechaFin"]);
    #$t->set_var("a_editar","<a href='".lib::CFG(array("URLBASE","APP_PROPIEDADES"))."publicar.editar.php?ID=".$rowp["idPropiedad"]."'>Editar</a>");
    $t->set_var("a_eliminar","<a href='".lib::CFG(array("URLBASE","APP_PROPIEDADES"))."propiedades.php?ID=".$rowp["idPropiedad"]."&b=1'>Eliminar</a>");
    $_activa=true;
  }
  
  if($rowp["idEstado"]==11){
    $t->set_var("c_IDPROPIEDAD",$rowp["idPropiedad"]);
    $t->set_var("c_tipo",lib::MISC()->dame_tipoPropiedad($rowp["idTipo"]));
    $t->set_var("c_precio",(($rowp["precioIdDivisa"]==1)?"$ ".$rowp["precioPesos"]:(($rowp["precioIdDivisa"]==2)?"UF ".$rowp["precioUF"]:"")));
    $t->set_var("c_operacion",lib::MISC()->dame_tipo_operacion($rowp["idOperacion"]));
    $t->set_var("c_fechaFin",$rowp["fechaFin"]);
    #$t->set_var("c_editar","<a href='".lib::CFG(array("URLBASE","APP_PROPIEDADES"))."publicar.editar.php?ID=".$rowp["idPropiedad"]."'>Editar</a>");
    $t->set_var("c_eliminar","<a href='".lib::CFG(array("URLBASE","APP_PROPIEDADES"))."propiedades.php?ID=".$rowp["idPropiedad"]."&b=1'>Eliminar</a>");
    $_caducada=true;
  }
  
  if($rowp["idEstado"]==2){
    $t->set_var("b_IDPROPIEDAD",$rowp["idPropiedad"]);
    $t->set_var("b_tipo",lib::MISC()->dame_tipoPropiedad($rowp["idTipo"]));
    $t->set_var("b_precio",(($rowp["precioIdDivisa"]==1)?"$ ".$rowp["precioPesos"]:(($rowp["precioIdDivisa"]==2)?"UF ".$rowp["precioUF"]:"")));
    $t->set_var("b_operacion",lib::MISC()->dame_tipo_operacion($rowp["idOperacion"]));
    $t->set_var("b_fechaFin",$rowp["fechaFin"]);
    #$t->set_var("b_editar","<a href='".lib::CFG(array("URLBASE","APP_PROPIEDADES"))."publicar.editar.php?ID=".$rowp["idPropiedad"]."'>Editar</a>");
    $t->set_var("b_eliminar","<a href='".lib::CFG(array("URLBASE","APP_PROPIEDADES"))."propiedades.php?ID=".$rowp["idPropiedad"]."&b=1'>Eliminar</a>");
    $_borrada=true;
  }
  
  if($_pendiente)
    $t->parse("pendientes_","pendientes",true);
  
  if($_activa)
    $t->parse("activas_","activas",true);
  
  if($_caducada)
    $t->parse("caducadas_","caducadas",true);
  
  if($_borrada)
    $t->parse("borradas_","borradas",true);
}

##-- datos de la plantilla --##
$dataPlantilla=array("HEADER"=>lib::HTML()->apps_header(),
                     "FOOTER"=>lib::HTML()->apps_footer(),
                     "static"=>lib::CFG("DOMSTATIC"),
                     "APPS"=>lib::CFG(array("URLBASE","APP_PROPIEDADES")));
$t->set_var($dataPlantilla);

$t->cerrar();
?>