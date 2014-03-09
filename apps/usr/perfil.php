<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

## valida al usuario logeado ##
lib::MISC()->valUsuario(4);

$t=lib::TMPL("perfil.html");

$t->cerrar();
?>