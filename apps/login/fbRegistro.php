<?php
require_once("../../lib/autoload.php");
require_once("../../lib/fb/tmkt.fb.php");

use integrador\integrador as lib;

#lib::mostrarErrores();

##-- Crear links para FB --##
$tmktFB=new tmktLoginFb();
$urlFB=$tmktFB->fb_login();

//usuario logeado FB
$usr=$tmktFB->fb_user();
lib::dump($usr);

##-- plantilla --##
$t=lib::TMPL("fbRegistro.html");

$t->set_var("login",$urlFB["LOGIN"]);
$t->set_var("logout",$urlFB["LOGOUT"]);

$t->cerrar();
?>