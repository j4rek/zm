<?php
require_once("../../lib/autoload.php");

use config\config;

unset($_SESSION["usr"]);
unset($_SESSION["REDIRECT"]);

header("location: ".config::URLBASE);
exit;
?>