<?php
require_once("../../lib/autoload.php");

use misc\misc;

##-- recepcion de parametros --##
$params=misc::checkVars($_GET);
$calle=$params["calle"];
$numero=$params["numero"];
$comuna=$params["comuna"];

$str_direccion = "";
$str_direccion .= stripslashes(urlencode(utf8_encode(ucwords(strtolower(trim( $calle ." ". $numero )))))) . ",+";
$str_direccion .= stripslashes(urlencode(utf8_encode(ucwords(strtolower(trim( $comuna )))))) . ",+";
$str_direccion .= "chile";

mensajes\mensajes::msg($str_direccion);
#exit;
$json_maps = json_decode(misc::obtiene_geolocalizacion( $str_direccion ));


if($json_maps["status"]=="OK"){//"OK" indica que no se ha producido ningún error; la dirección se ha analizado correctamente y se ha devuelto al menos un código geográfico.

#$json_maps["results"]["geometry"]["bounds"]["location"];

}elseif($json_maps["status"]=="ZERO_RESULTS"){//"ZERO_RESULTS" indica que la codificación geográfica se ha realizado correctamente pero no ha devuelto ningún resultado. Esto puede ocurrir si en la codificación geográfica se incluye una dirección (address) inexistente o un valor latlng en una ubicación remota.



}elseif($json_maps["status"]=="OVER_QUERY_LIMIT"){//"OVER_QUERY_LIMIT" indica que se ha excedido el cupo de solicitudes.



}elseif($json_maps["status"]=="REQUEST_DENIED"){//"REQUEST_DENIED" indica que la solicitud se ha denegado; normalmente se debe a la ausencia de un parámetro sensor.



}elseif($json_maps["INVALID_REQUEST"]){//"INVALID_REQUEST" normalmente indica que no se ha especificado la solicitud (address o latlng).


} 
  
?>