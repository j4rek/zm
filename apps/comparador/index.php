<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

##-- validar usuario--##
lib::MISC()->valUsuario(4);

$t=lib::TMPL("index.html");

$t->cerrar();
?>