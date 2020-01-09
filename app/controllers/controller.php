<?php

# Implement sanitize methods first
class Controller extends Application {
  
  protected $controller;
  protected $method;
  protected $model;
  protected $view;
  
  public function __construct($controller, $method) {
    parent::__construct();
    
    $this->controller = $controller;
    $this->method = $method;
    $this->view = new View();
  }
  
  # Load model specific for this controller
  protected function load_model($model) {
    if (class_exists($model)) {
      $this->model[$model] = new $model();
    }
    else {
      return false;
    }
  }
  
  # Implement/instantiate model methods
  protected function get_model($model) {
    if (is_object($model)) {
      return $this->model[$model];
    }
    else {
      return false;
    }
  }
  
  # Return output view
  protected function get_view() {
    return $this->view;
  }
  
}

?>