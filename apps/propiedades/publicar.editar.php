<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

## valida al usuario logeado ##
lib::MISC()->valUsuario(4);

$t=lib::TMPL("publicar.editar.html");
#lib::mostrarErrores();

##-- recepcion de datos --##
$datos=lib::MISC()->checkVars($_GET);
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

##-- Contenido --##
## extraer datos de la propipedad para completar el formulario ##
## valida al usuario contra propiedad ##
$prop=lib::DB()->myQuery("select * from propiedades_copy where idPropiedad='".$datos["ID"]."' and idUsuario='".$_SESSION["usr"]["id"]."' limit 1;",0,1);

if(!$prop){
  header("location: ".(lib::CFG(array("URLBASE","APP_PROPIEDADES")))."propiedades.php?".lib::CFG("ERROR_NO_PROPIETARIO"));
}

## valida ID para editar la propiedad ##
if(!lib::MISC()->is_empty($prop["idPropiedad"])){
  ##-- actualización de la propiedad --##
  if(count($params)>0){
    
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
    
    lib::DB()->myQuery("update propiedades_copy set
                      idTipo='$tpP', idComuna='$comuna', idOperacion='$operacion', idCalefaccionDef='$calefaccion', idTipoPisoDef='$tipoPiso',
                      calle='".addslashes($calle)."', numCalle='$numCalle', numPropiedad='$numPropiedad', precioUF='$_precioUF', precioPesos='$_precioPESOS',
                      precioxMedida='$metros2', precioIdMedida='$medida', precioIdDivisa='$divisa', gastosCom='$gastosComunes',  gastosComIdDivisa='$divisaGComunes', metrosTerreno='$mtsTerreno',
                      metrosConstruidos='$mtsConstruidos', metrosTerrazaBalcon='$mtsTerraza', metrosBodega='$mtsBodega', metrosFrente='$mtsFrente', metrosFondo='$mtsFondo', hectareas='', dormitorios='$dormitorios',
                      banos='$banos', suites='$suites', piso='$piso', numPisos='$numPisos', centroComercial='$centroComercial', numEstacionamientos='$numEstacionamientos', numAscensores='$numAscensores', numPrivados='$numPrivados',
                      orientacion='$orientacion', alturaPuerta='$altoPuerta', anchoPuerta='$anchoPuerta', alturaBodega='$altoBodega', observaciones='".addslashes($observaciones)."'
                      where idPropiedad='".$prop["idPropiedad"]."' and idUsuario='".$_SESSION["usr"]["id"]."' limit 1;");
    
    ##-- guardar las caracteristicas --##
    ##// resetea las caracteristicas
    lib::DB()->myQuery("delete from propiedades_caracteristicas where idPropiedad='".$prop["idPropiedad"]."';");
    
    foreach($params["caracteristicas"] as $key => $val){
     lib::DB()->myQuery("insert into propiedades_caracteristicas set idPropiedad='".$prop["idPropiedad"]."', idCaracteristica='$val';");
    }
  
    header("location: ".(lib::CFG(array("URLBASE","APP_PROPIEDADES")))."publicar.imagenes.php?ID=".$datos["ID"]);
    exit();
  }
}else{
  ## en caso de ID nulo redirect ##
  header("location: ".(lib::CFG(array("URLBASE","APP_PROPIEDADES")))."propiedades.php?".lib::CFG("ERROR_ID_NULO"));
  exit();
}

## elementos html ##
$dataPlantilla=array("dormitorios"=>lib::HTML()->lstNumerico("name='dormitorios'",$prop["dormitorios"]),
                     "banos"=>lib::HTML()->lstNumerico("name='banos'",$prop["banos"]),
                     "tipos"=>lib::HTML()->tipoProps("name='tipoProps'",$prop["idTipo"]),
                     "operacion"=>lib::HTML()->operaciones("name='operacion'",$prop["idOperacion"]),
                     "regiones"=>lib::HTML()->regiones("name='regiones'",13),
                     "comunas"=>lib::HTML()->comunas("name='comunas'",0,$prop["idComuna"]),
                     "medidas"=>lib::HTML()->medidas("name='medidas'",$prop["precioIdMedida"]),
                     "divisas"=>lib::HTML()->divisas("name='divisas'",$prop["precioIdDivisa"]),
                     "divisasc"=>lib::HTML()->divisas("name='divisasGComunes'",$prop["gastosComIdDivisa"]),
                     "suites"=>lib::HTML()->lstNumerico("name='suites'",$prop["suites"]),
                     "privados"=>lib::HTML()->lstNumerico("name='privados'",$prop["numPrivados"]),
                     "pisos"=>lib::HTML()->tipoPisos("name='tipoPisos'",$prop["idTipoPisoDef"]),
                     "estacionamientos"=>lib::HTML()->lstNumerico("name='estacionamientos'",$prop["numEstacionamientos"]),
                     "ascensores"=>lib::HTML()->lstNumerico("name='ascensores'",$prop["numAscensores"]),
                     "calefaccion"=>lib::HTML()->tipoCalefaccion("name='calefaccion'",$prop["idCalefaccionDef"]),
                     "orientacion"=>lib::HTML()->lstOrientacion("name='orientacion'",$prop["orientacion"]),
                     "caracteristicas"=>lib::HTML()->check_caracteristicas($prop["idTipo"],$prop["idPropiedad"]),
                     "chk_m2"=>lib::MISC()->checked($prop["precioxMedida"]),
                     "static"=>lib::CFG("DOMSTATIC"),
                     "HEADER"=>lib::HTML()->apps_header(),
                     "FOOTER"=>lib::HTML()->apps_footer(),
                     "calle"=>$prop["calle"],
                     "numPropiedad"=>$prop["numPropiedad"],
                     "numPiso"=>$prop["piso"],
                     "mtsTerreno"=>str_replace(".",",",$prop["metrosTerreno"]),
                     "mtsConstruidos"=>str_replace(".",",",$prop["metrosConstruidos"]),
                     "mtsTerraza"=>str_replace(".",",",$prop["metrosTerrazaBalcon"]),
                     "mtsFrente"=>str_replace(".",",",$prop["metrosFrente"]),
                     "mtsFondo"=>str_replace(".",",",$prop["metrosFondo"]),
                     "mtsBodega"=>str_replace(".",",",$prop["metrosBodega"]),
                     "precio"=>str_replace(".",",",(($prop["precioIdDivisa"]==1)?$prop["precioPesos"]:$prop["precioUF"])),
                     "gastosComunes"=>str_replace(".",",",$prop["gastosCom"]),
                     "checkm2"=>(($prop["precioxMedida"]==1)?"checked":""),
                     "alturaPuerta"=>$prop["alturaPuerta"],
                     "anchoPuerta"=>$prop["anchoPuerta"],
                     "alturaBodega"=>$prop["alturaBodega"],
                     "observaciones"=>$prop["observaciones"],
                     "ID"=>$prop["idPropiedad"]);
$t->set_var($dataPlantilla);

$t->cerrar();
?>