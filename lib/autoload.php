<?php
// iniciar las sessiones
session_set_cookie_params(0 , '/', '.zoominmobiliario.com');
session_start();

// guarda la URL de cada pagina visitada,
// esto es para hacer el redirect desde el login en caso de requerir usuario.
if(strpos($_SERVER["REQUEST_URI"],"login")===false){
  $_SESSION["REDIRECT"]=$_SERVER["REQUEST_URI"];  
}

function __autoload($classname) {
  $classname = ltrim($classname, '\\');
  $filename  = '';
  $namespace = '';
  if ($lastnspos = strripos($classname, '\\')) {
    $namespace = substr($classname, 0, $lastnspos);
    $classname = substr($classname, $lastnspos + 1);
    $filename  = str_replace('\\', '/', $namespace) . '/';
  }
  $filename .= str_replace('_', '/', $classname) . '.php';
  
  require_once $filename;
}


?>