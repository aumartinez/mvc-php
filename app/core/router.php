<?php

class Router {
  
  # Initial states
  protected $default_controller = DEFAULT_CONTROLLER;
  protected $default_method = DEFAULT_METHOD;
  protected $params = array();

  # Route handler method
  public function route($url) {
    
    # Split url using "/" as separator
    $url_array = array();
    $url_array = explode("/", $url);
    
    # Remove domain and app folder from url
    if (in_array(PATH, $url_array)) {
      while($url_array[0] != PATH) {
        array_shift($url_array);
      }
      array_shift($url_array);
    }
    
    # If any, pass the corresponding controller, method and parameters
    $controller = isset($url_array[0]) ? array_shift($url_array) : "";    
    $method = isset($url_array[0]) ? array_shift($url_array) : "";
    $params = isset($url_array[0]) ? array_shift($url_array) : "";
    
    # If controller is not found or not exists as a class handler
    # set default controller and not found method
    if (empty($controller)) {
      $controller = $this->default_controller;
    }
    else if (!(class_exists($controller))) {
      $controller = $this->default_controller;
      $method = NOT_FOUND;      
    }    
    
    if (empty($method)) {
      $method = $this->default_method;
    }
    
    # Pull URL query parameters if any
    $params = $url_array;
    
    # Instantiate controller class and call to appropriate method
    $controller_name = $controller;
    $controller = ucfirst($controller);
    $dispatch = new $controller($controller_name, $method);
    
    if (method_exists($controller, $method)) {
      call_user_func_array(array($dispatch, $method), $params);
    }
    else {
      # Error handler not found method
      call_user_func_array(array($dispatch, NOT_FOUND), $params);      
    }
    
  } 
}

?>
