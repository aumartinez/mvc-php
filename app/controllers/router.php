<?php

class Router {
  protected $default_controller = DEFAULT_CONTROLLER;
  protected $default_method = DEFAULT_METHOD;
  protected $params = [];

  public function route($url) {
    $url_array = array();
    $url_array = explode("/", $url);
    
    if (in_array(PATH, $url_array)) {
      while($url_array[0] != PATH) {
        array_shift($url_array);
      }
    }
    if ($url_array[0] == PATH) {
      array_shift($url_array);
    }
    
    $controller = isset($url_array[0]) ? array_shift($url_array) : "";    
    $method = isset($url_array[0]) ? array_shift($url_array) : "";
    $params = isset($url_array[0]) ? array_shift($url_array) : "";
    
    if (empty($controller)) {
      $controller = $this->default_controller;
    }
    
    if (empty($method)) {
      $method = $this->default_method;
    }
    
    $params = $url_array;
    
    $controller_name = $controller;
    $controller = ucfirst($controller);
    $dispatch = new $controller($controller_name, $method);
    
    if (method_exists($controller, $method)) {
      call_user_func_array(array($dispatch, $method), $params);
    }
    else {
      /* Error message */
    }
    
  } 
}

?>
