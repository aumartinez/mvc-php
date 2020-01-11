<?php

class Pagemodel extends Dbmodel {
  
  private $html_str;
  
  public $site_title = WEB_TITLE;
  public $page_title = "";
      
  public function get_page($page_name) {        
    if(file_exists(HTML . DS . $page_name . ".html")){
      $this->html_str = file_get_contents(HTML . DS . $page_name . ".html");
    }            
    return $this->html_str;
  }
  
  protected function get_htmlhead() {
    $html = "";
    $html .= $this->get_doctype();
    $html .= $this->get_openhtml();
    
  }
  
  protected function get_doctype($doctype = "html5") {
    $dtd = "";
    
    if ($doctype == "html5") {
      $dtd .= "<!doctype html>";
      $dtd .= "\n";
    }
    
    return $dtd;
  }
  
  protected function get_openhtml($lang = "en-us") {
    $html = "";
    
    if ($lang = "en-us") {
      $html .= "<!html lang=\"en\">";
      $html .= "\n";
    }
    
    return $html;
  }
  
  protected function get_head() {
    $html = "";
    $html .= " <head>\n";
    
    if (file_exists(HTML . DS . "temp" . DS . "meta.html")) {
      $html .= file_get_contents(HTML . DS . "temp" . DS . "meta.html");
      $html .= "\n";
    }
    
    if ($this->page_title != "") {
      $title = $this->page_title . " | " . $this->site_title;
    }
    else{
      $title = $this->site_title;
    }
    
    $html .= "  <title>" . $title . "</title>";
    $html .= "\n";
    
    if (file_exists(HTML . DS . "temp" . DS . "resources.html")) {
      $html .= file_get_contents(HTML . DS . "temp" . DS . "resources.html");
      $html .= "\n";
    }
    
    $html .= " </head>";
    $html .= "\n";
    
    return $html;
  }
  
  protected function get_openbody($page_name = "index") {
    $html = "";
    $html .= " <body id=\"" . $page_name . "\">";
    $html .= "\n";
  }
  
  protected function get_header() {
    $html = "";
    
    if (file_exists(HTML . DS . "temp" . DS . "header.html")) {
      $html .= file_get_contents(HTML . DS . "temp" . DS . "header.html");
      $html .= "\n";
    }
    
    return $html;
  }
  
  protected function get_bodycont($page_name) {
    $html = "";
    
    if (file_exists(HTML . DS . "page" . DS . $page_name . ".html")) {
      $html .= file_get_contents(HTML . DS . "page" . DS . $page_name . ".html");
      $html .= "\n";
    }
    
    return $html;
  }
  
  protected function get_footer() {
    $html = "";
    
    if (file_exists(HTML . DS . "temp" . DS . "footer.html")) {
      $html .= file_get_contents(HTML . DS . "temp" . DS . "footer.html");
      $html .= "\n";
    }
    
    return $html;
  }
  
  protected function get_scripts() {
    $html = "";
    
    if (file_exists(HTML . DS . "temp" . DS . "scripts.html")) {
      $html .= file_get_contents(HTML . DS . "temp" . DS . "scripts.html");
      $html .= "\n";
    }
    
    return $html;
  }
  
  protected function get_closebody() {
    $html = "";
    $html .= " </body>\n";
    $html .= "</html>";
    $html .= "\n";
    
    return $html;
  }
  
}

?>