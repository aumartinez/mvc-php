<?php

class View {
  
  protected $vars = array();
  
  public function __construct() {
    
  }
  
  public function set($name, $value) {
    $this->vars[$name] = value;
  }
    
  public function render($viewName) {
    extract($this->vars);
    echo $viewName;
  }
  
}

?>