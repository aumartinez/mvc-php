<?php

class Application {
  
  function __construct() {
    $this->set_reporting();
    $this->remove_magic_quotes();
    $this->unregister_globals();
  }
  
  private function set_reporting() {
    if (DEVELOPMENT_ENVIRONMENT == true) {
      error_reporting(E_ALL);
      ini_set ("display_errors", "on");
    }
    else {
      error_reporting(E_ALL);
      ini_set("display_errors", "off");
      ini_set("log_errors", "on");
      ini_set("error_log", ROOT . DS . "tmp" . DS . "logs" . "error.log");
    }
  }
  
  private function stripslashes_deep($value) {
    $value = is_array($value) ? array_map(array($this, "stripslashes_deep"), $value) : stripslashes($value);
    
    return $value;
  }
  
  private function remove_magic_quotes() {
    if (get_magic_quotes_gpc()) {
      $_GET = $this->stripslashes_deep($_GET);
      $_POST = $this->stripslashes_deep($_POST);
      $_COOKIE = $this->stripslashes_deep($_COOKIE);
    }
  }
  
  private function unregister_globals() {
    if (ini_get("register_globals")) {
      $array = array("_SESSION", "_POST", "_GET", "_REQUEST", "_SERVER", "_ENV", "_FILES");
      
      foreach ($array as $value) {
        foreach ($GLOBALS[$value] as $key => $var) {
          if ($var === $GLOBALS[$key]) {
            unset($GLOBALS[$key]);
          }
        }
      }
    }
  }
  
}

?>
