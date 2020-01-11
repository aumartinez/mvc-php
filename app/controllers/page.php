<?php

class Page extends Controller {
  
  protected $output;
  protected $local_method;
    
  public function __construct($controller, $method) {
    parent::__construct($controller, $method);
    
    $this->local_method = $method;

    # Any models required to interact with this controller should be loaded here    
    $this->load_model("Pagemodel");    
    
    # Instantiate custom view output
    $this->output = new Pageview();
  }
  
  public function home() {
    $this->build_page($this->local_method);
  }
  
  public function about() {
    $this->build_page($this->local_method);
  }
  
  public function contact() {
    $this->build_page($this->local_method);
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
