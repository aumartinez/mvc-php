<?php

class Home extends Controller {
  
  public function __construct($controller, $method) {
    parent::__construct($controller, $method);
    echo "Here";
  }
  
  public function index() {
    //echo "Index method";
  }
  
}

?>
