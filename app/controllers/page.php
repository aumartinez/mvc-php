<?php

class Page extends Controller {
  
  public function __construct($controller, $method) {
    parent::__construct($controller, $method);
    echo "Here page";
  }
  
  public function index() {
    //echo "Index method";
  }
  
}

?>
