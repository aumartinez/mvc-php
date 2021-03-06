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
  
  # Each method will request the model to present the local resource
  public function home() {
    $this->get_model("Pagemodel")->page_title = "Home";
    $this->build_page($this->method);
  }
  
  public function about() {
    $this->get_model("Pagemodel")->page_title = "About Us";
    $this->build_page($this->method);
  }
  
  public function contact() {
    $this->get_model("Pagemodel")->page_title = "Contact Us";
    $this->build_page($this->method);
  }
  
  public function not_found() {
    $this->build_page("404");
  }
  
  # Controller/Model/View link
  protected function build_page($page_name) {    
    $htm_src = $this->get_model("Pagemodel")->get_page($page_name);    
    $html = $this->output->replace_localizations($htm_src);
    
    $this->get_view()->render($html);
  }
  
}

?>
