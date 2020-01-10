<?php

abstract class Dbabstractmodel {
    
  protected $sql;
  protected $rows = array();
  
  private $conx;
  
  # Abstract methods for DB queries
  abstract protected function get();
  abstract protected function set();
  abstract protected function edit();
  abstract protected function delete();
  
  # Open link to DB
  private function open_link() {
    $this->conx = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
  }
  
  # Close link to DB
  private function close_link() {
    $this->conx->close();
  }
  
  # Submit SQL query for INSERT, UPDATE or DELETE
  protected function run_query() {
    $this->open_link();
    $this->conx->query($this->sql);
    $this->close_link();
  }
  
  protected function fetch_query() {
    $this->open_link();
    $result = $this->conx->query($this->sql);
    while ($this->rows[] = $result->fetch_assoc());    
    $result->free();
    $this->close_link();
    array_pop($this->rows);
  }
  
}

?>