<?php

class Page extends Controller {
    
  public function __construct($controller, $method) {
    parent::__construct($controller, $method);
    
    # Any models required to interact with this controller should be loaded here    
    $this->load_model("Pagemodel");
  }
  
  public function index() {
    $this->build_page("home");
  }
  
  public function build_page($page_name) {    
    $page_src = $this->get_model("Pagemodel")->get_page($page_name);
    $this->get_view();
    $this->view->render($page_src);
  }
  
}

?>
