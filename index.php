<?php
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__FILE__) . DS . "app");

$url = $_SERVER["REQUEST_URI"];

require_once(ROOT . DS . "core" . DS . "core.php");
?>
