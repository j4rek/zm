<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

## valida al usuario logeado ##
lib::MISC()->valUsuario(4);

$t=lib::TMPL("publicar.html");
#lib::mostrarErrores();

##-- recepcion de datos --##
$params=lib::MISC()->checkVars($_POST);
$tipo=$params["tipoProps"];
$tipoPropiedad=$params["tipoProps"];

$operacion=$params["operacion"];

$comuna=$params["comunas"];
$calle=$params["calle"];
$numPropiedad=$params["numPropiedad"];
$piso=$params["numPiso"];

$dormitorios=$params["dormitorios"];
$banos=$params["banos"];
$suites=$params["suites"];
$privados=$params["privados"];
$mtsTerreno=str_replace(",",".",$params["mTerreno"]);
$mtsConstruidos=str_replace(",",".",$params["mConstruidos"]);
$mtsTerraza=str_replace(",",".",$params["mTerraza"]);
$mtsFrente=str_replace(",",".",$params["mFrente"]);
$mtsFondo=str_replace(",",".",$params["mFondo"]);
$mtsBodega=str_replace(",",".",$params["mBodega"]);
$medida=$params["medidas"];

$precio=$params["precio"];
$gastosComunes=$params["gcomunes"];
$divisa=$params["divisas"];
$divisaGComunes=$params["divisasGComunes"];
$metros2=$params["precioxmedida"];

$calefaccion=$params["calefaccion"];
$tipoPiso=$params["tipoPisos"];
$numEstacionamientos=$params["estacionamientos"];
$numAscensores=$params["ascensores"];
$numPrivados=$params["privados"];
$orientacion=$params["orientacion"];
$altoPuerta=$params["altopuerta"];
$anchoPuerta=$params["anchopuerta"];
$altoBodega=$params["altobodega"];

$observaciones=$params["observaciones"];
$fechaIngreso=date("Y-m-d H:i:s");

##-- FECHA FIN POR OPERACION --##
switch($operacion){
  case 1:
    $fechaFin=lib::MISC()->fechaProxima(lib::CFG("VIGENCIA_PROPIEDAD_VENTA"));
    break;
  case 2:
    $fechaFin=lib::MISC()->fechaProxima(lib::CFG("VIGENCIA_PROPIEDAD_ARRIENDO"));
    break;
  case 5:
    $fechaFin=lib::MISC()->fechaProxima(lib::CFG("VIGENCIA_PROPIEDAD_ARRIENDO_TEMP"));
    break;
}

##-- Contenido --##

## elementos html ##
$dataPlantilla=array("dormitorios"=>lib::HTML()->lstNumerico("name='dormitorios'"),
                     "banos"=>lib::HTML()->lstNumerico("name='banos'"),
                     "tipos"=>lib::HTML()->tipoProps("name='tipoProps'",1),
                     "operacion"=>lib::HTML()->operaciones(),
                     "regiones"=>lib::HTML()->regiones("name='regiones'",13),
                     "comunas"=>lib::HTML()->comunas("name='comunas'",0),
                     "divisas"=>lib::HTML()->divisas("name='divisas'"),
                     "divisasc"=>lib::HTML()->divisas("name='divisasGComunes'"),
                     "medidas"=>lib::HTML()->medidas("name='medidas'"),
                     "suites"=>lib::HTML()->lstNumerico("name='suites'"),
                     "privados"=>lib::HTML()->lstNumerico("name='privados'"),
                     "pisos"=>lib::HTML()->tipoPisos("name='tipoPisos'"),
                     "estacionamientos"=>lib::HTML()->lstNumerico("name='estacionamientos'"),
                     "ascensores"=>lib::HTML()->lstNumerico("name='ascensores'"),
                     "calefaccion"=>lib::HTML()->tipoCalefaccion("name='calefaccion'"),
                     "orientacion"=>lib::HTML()->lstOrientacion("name='orientacion'"),
                     "caracteristicas"=>lib::HTML()->check_caracteristicas(),
                     "static"=>lib::CFG("DOMSTATIC"),
                     "HEADER"=>lib::HTML()->apps_header(),
                     "FOOTER"=>lib::HTML()->apps_footer());
$t->set_var($dataPlantilla);

##-- ingreso de la propiedad --##
if(count($params)>0){
  ## -- valida los permisos para registrar la propiedad -- ##
  if(!lib::MISC()->permitePublicar()){
    lib::MISC()->redirect(lib::CFG(array("URLBASE","APP_PROPIEDADES"))."propiedades.php?".lib::CFG("ERROR_PERMISO_PUBLICAR"));
  }
  
  ## -- validacion de tipo de propiedad
  if(lib::MISC()->is_empty($tipo)==false && is_numeric($tipo) && $tipo!=0){
   $tpP=$tipo;
  }elseif(lib::MISC()->is_empty($tipoPropiedad)==false && is_numeric($tipoPropiedad) && $tipoPropiedad!=0){
   $tpP=$tipoPropiedad;
  }else{
   header("location: ".(lib::CFG(array("URLBASE","APP_PROPIEDADES")))."propiedades.php?".lib::CFG("ERROR_TIPO_PROPIEDAD"));
   exit;
  }
  
  ## -- VALOR de la propiedad -- ##
  if($divisa==1){
    $_precioPESOS=str_replace(",",".",$precio);
  }elseif($divisa==2){
    $_precioUF=str_replace(",",".",$precio);
  }
  
  lib::DB()->myQuery("insert into propiedades_copy set
                    idTipo='$tpP', idComuna='$comuna', idEmpresa='', idEmpresaSant='', idUsuario='".$_SESSION["usr"]["id"]."', idOperacion='$operacion', idCalefaccionDef='$calefaccion', idTipoPisoDef='$tipoPiso',
                    calle='".addslashes($calle)."', numCalle='$numCalle', numPropiedad='$numPropiedad', precioUF='$_precioUF', precioPesos='$_precioPESOS',
                    precioxMedida='$metros2', precioIdMedida='$medida', precioIdDivisa='$divisa', gastosCom='$gastosComunes',  gastosComIdDivisa='$divisaGComunes', codExterno='', metrosTerreno='$mtsTerreno',
                    metrosConstruidos='$mtsConstruidos', metrosTerrazaBalcon='$mtsTerraza', metrosBodega='$mtsBodega', metrosFrente='$mtsFrente', metrosFondo='$mtsFondo', hectareas='', dormitorios='$dormitorios',
                    banos='$banos', suites='$suites', piso='$piso', numPisos='$numPisos', centroComercial='$centroComercial', numEstacionamientos='$numEstacionamientos', numAscensores='$numAscensores', numPrivados='$numPrivados',
                    orientacion='$orientacion', alturaPuerta='$altoPuerta', anchoPuerta='$anchoPuerta', alturaBodega='$altoBodega', observaciones='".addslashes($observaciones)."', fechaIngreso='$fechaIngreso',
                    fechaFin='$fechaFin', idEstado='1';");

  ## -- obtener el ultimo ID -- ##
  $res=lib::DB()->myQuery("select LAST_INSERT_ID() as id;",0,1);
    
  ## -- guarda las caracteristicas -- ##
  foreach($params["caracteristicas"] as $key => $val){
   lib::DB()->myQuery("insert into propiedades_caracteristicas set idPropiedad='".$res["id"]."', idCaracteristica='$val';");
  }
  
  header("location: publicar.imagenes.php?ID=".$res["id"]);
  exit();
}

$t->cerrar();
?>