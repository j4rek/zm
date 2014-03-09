<?php
require_once("./classes/API/class_API.php");
$api=new j4son();
$t=$api->crear_template(RUTA_PLANTILLAS);
$t->set_file("plantilla","info.html");

$data=array("HEADER"=>$api->make_header(),"footer"=>$api->get_footer(1));
$api->set_data($t,$data);
/***************************************/
$api->cerrar($t,"plantilla");
?>