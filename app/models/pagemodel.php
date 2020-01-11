<?php

class Pagemodel extends Dbmodel {
  
  private $html;
  
  public function __construct() {
    
  }
    
  public function get_page($page_name) {        
    if(file_exists(HTML . DS . $page_name . ".html")){
      $this->html = file_get_contents(HTML . DS . $page_name . ".html");
    }            
    return $this->html;
  }
  
}

?>