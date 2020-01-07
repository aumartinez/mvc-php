<?php
//Load config
require_once(ROOT . DS . "config" . DS . "config.php");
require_once(ROOT . DS. "core" . DS . "functions.php");

//Autoloader
spl_autoload_register(function ($className) {
  if (file_exists(ROOT . DS . "core" . DS . strtolower($className) . ".php")) {
    require_once (ROOT . DS . "core" . DS . strtolower($className) . ".php");
  }
  else if (file_exists(ROOT . DS . "controllers" . DS . strtolower($className) . ".php")) {
    require_once (ROOT . DS . "controllers" . DS . strtolower($className) . ".php");
  }
  else if (file_exists(ROOT . DS . "models" . DS . strtolower($className) . ".php")) {
    require_once (ROOT . DS . "models" . DS . strtolower($className) . ".php");
  }  
});

//Route request
$router = new Router();
$router->route($url);
?>
