<?php

//Helper functions

function is_controller($str) {
  if (file_exists(ROOT . DS . "controllers" . DS . strtolower($str) . ".php")){
    return true;
  }
  else {
    return false;
  }
}

function snake_case($str) {
  return str_replace("-", "_", $str);
}

?>
