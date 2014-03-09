<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

$t=lib::TMPL("index.html");

$t->cerrar();
?>