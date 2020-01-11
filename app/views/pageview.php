<?php

class Pageview extends View {
  private $localizations = array();
  
  public function build_locales() {
    $this->localizations = array(
      "PAGE_TITLE" => WEB_TITLE,
      "MEDIA" => MEDIA,
      "PAGE_HEADING" => "Home page title"
    );
    
    return $this->localizations;
  }
  
  public function replace_localizations($html) {
    $this->build_locales();
    
    foreach ($this->localizations as $key => $value) {
      $html = str_replace("{\$" . $key . "\$}", $value, $html);
    }
    
    return $html;
  }
  
}

?>