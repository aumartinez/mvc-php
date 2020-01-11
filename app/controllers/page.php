<?php

class Page extends Controller {
  
  protected $output;
    
  public function __construct($controller, $method) {
    parent::__construct($controller, $method);
           
    # Any models required to interact with this controller should be loaded here    
    $this->load_model("Pagemodel");    
    
    # Instantiate custom view output
    $this->output = new Pageview();
  }
  
  public function index() {
    $this->build_page("home");
  }
  
  public function about() {
    $this->build_page("about");
  }
  
  public function not_found() {
    $this->build_page("404");
  }
  
  public function build_page($page_name) {    
    $htm_src = $this->get_model("Pagemodel")->get_page($page_name);    
    $html = $this->output->replace_localizations($htm_src);
    
    $this->get_view()->render($html);
  }
  
}

?>
