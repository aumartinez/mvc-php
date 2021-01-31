<?php

class DbModel {

  protected $rows = array();  
  protected $conx;
  protected $new_id;
  
  private $sql;
  private $columns = array();  

  public function __construct() {
    $this->sql = "";
    $this->columns = array();
    $this->values = array();
  }  
  
  # Test if DBNAME exists
  public function test_db() {
    $this->conx = new mysqli(DBHOST, DBUSER, DBPASS);
    if ($this->conx->connect_errno) {
      error_log("Database test failed: " . $this->conx->connect_error );
      echo "Failed to connect to MySQL: " . $this->conx->connect_error;      
      exit();
    }
    
    return $this->conx->select_db(DBNAME);
  }  
  
  # Open DB link
  public function open_link() {  
    $this->conx = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
    if ($this->conx->connect_errno) {
      error_log("Connection failed" . $this->conx->connect_error);
      $_SESSION["error"][] = "Failed to connect to MySQL: " . $this->conx->connect_error;
      exit();
    }
        
    return $this->conx;
  }
    
  # Close DB link
  public function close_link() {
    $this->conx->close();
  }
  
  # Submit SQL query for INSERT, UPDATE or DELETE
  public function set_query($sql) {
    $this->open_link();
    $result = $this->conx->query($sql);
    
    if (!$result) {
      error_log("Query failed: " . $sql);
      $_SESSION["error"][] = "Query error: " . $this->conx->error;
    }
    
    $this->new_id = $this->conx->insert_id;    
    $this->close_link();
  }
  
  public function set_multyquery($sql) {
    $this->open_link();
    $result = $this->conx->multi_query($sql);
    
    if (!$result) {
      error_log("Query failed: " . $sql);
      $_SESSION["error"][] = "Query error: " . $this->conx->error;      
    }
    
    $this->close_link();
  }
    
  # Submit SELECT SQL query
  public function get_query($sql) {
    $this->rows = array();
    $this->open_link();
    $result = $this->conx->query($sql);     
    
    if (!$result) {
      error_log("Query failed: " . $sql);
      $_SESSION["error"][] = "Query error: " . $this->conx->error;
      return false;
    }
    
    while ($this->rows[] = $result->fetch_assoc());    
    $result->free();
    $this->close_link();
    
    array_pop($this->rows);
    
    $allowed = array(
      "&lt;br /&gt;",
      "&amp;",
    );
    
    $replace_with = array(
      "<br />",
      "&",
    );
    
    # If script was injected to the DB remove any html entity on query submit
    if (!empty($this->rows)){
      foreach($this->rows[0] as $key => $value){
        $str = htmlentities($value);
        $str = str_replace($allowed, $replace_with, $str);
        $this->rows[0][$key] = $str;
      } 
    }    
    return $this->rows;
  }
  
  # Submit SELECT SQL query - get row count if matches found
  public function get_rows($sql) {
    $this->open_link();
    $result = $this->conx->query($sql);
    
    if (!$result) {
      error_log("Query failed: " . $sql);
      $_SESSION["error"][] = "Query error: " . $this->conx->error;
      return false;
    }
    
    $rows = $result->num_rows;    
    return $rows;    
  }
}

?>
