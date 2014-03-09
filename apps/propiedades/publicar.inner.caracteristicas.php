<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

## -- validacion usuario -- ##
lib::MISC()->valUsuario(4);

## -- recepcion de variables -- ##
$params=lib::MISC()->checkVars($_GET);

## -- html -- ##
echo lib::HTML()->check_caracteristicas($params["tp"],$params["idp"]);
?>