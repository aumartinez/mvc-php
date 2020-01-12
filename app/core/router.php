<?php

class Router {
  
  # Initial states
  protected $default_controller = DEFAULT_CONTROLLER;
  protected $default_method = DEFAULT_METHOD;
  protected $params;

  # Route handler method
  public function route($url) {
    
    # Split url using "/" as separator
    $url_array = array();
    $url_array = explode("/", trim($url, "/"));
    
    # Remove app folder value
    array_shift($url_array);
    
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
    
    # To pass parameters to the controller, check if a given method
    # will handle these
    if (method_exists($controller, $method)) {
      call_user_func_array(array($dispatch, $method), $params);
    }
  } 
}

?>