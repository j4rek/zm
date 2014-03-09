<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

##-- valida usuario --##
lib::MISC()->valUsuario(4);

##-- recepcion de parametros --##
$params=lib::MISC()->checkVars($_GET);

if(is_numeric($params["ID"])){
  $res=lib::DB()->myQuery("update propiedades set idEstado='2' where idPropiedad='".$params["ID"]."' and idUsuario='".$_SESSION["usr"]["id"]."' limit 1;");
}

header("location: propiedades.php");
exit();
?>