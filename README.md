# mvc-php
An MVC implementation using PHP

## The MVC Pattern

As you would be aware, the MVC (Model-View-Controller) is a widely used pattern in software architecture for web applications. The core on the pattern is to divide the application into components and define the relationship between them.

## The Model

Usually represents the database logic, or the behind the scenes processes used to communicate with the database. The model will return to the controller or views information required from the user or any information necessary to build the view.

## The Controller

Represents the application logic and define the relationship between the model or models and the views. The controller will also route the user requests appropriately, either to the model or view accordingly.

## The View

The controller will submit a resource request to the model, and upon response will submit this output to the view. The view will take the provided information from the controller and build the view or output visible to the user.

## Directory structure

A proposed directory structure for a given project could be as below,

ROOT sub-folder (mvc-php) for this example:

- mvc php
  - app
    - config
    - controllers
    - core
    - models
    - views
  - common
    - css
    - html
    - img
    - js

![Screenshot](/screenshots/screenshot-00.PNG)

## Initial preparations

### Blank index files

For each folder, a blank index.html file will help to minimize undesired indexing access to them.

```html


<!doctype html>
<html>
  <head>
    <title>403 Forbidden</title>
  </head>
  <body>
    <h1>
      Directory access is forbidden.
    </h1>
  </body>
</html>

```

### URL redirecting

Our approach will have all users requests sent to a single PHP file which will then route the request to the appropriate resource. Then a sort of redirect or URL rewriting method should be used.

For webservers running apache, this can be achieved with the help of an .htaccess file

```apache

RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /mvc-php/index.php [L,QSA]

### "mvc-php" can be either removed or replaced by a folder of your choice

```

This .htaccess file will be located in the ROOT_site > mvc-php folder.

## MVC core

The index.php file will then call for the core classes and methods that will handle the request.

The index.php file in the "mvc-php" folder will look like the below:

```php

<?php

# Use DIRECTORY_SEPARATOR for multiplatform compatibility
define("DS", DIRECTORY_SEPARATOR);
# Path to app folder
define("ROOT", dirname(__FILE__) . DS . "app");

# Get URL request from server
$url = $_SERVER["REQUEST_URI"];

# Call the request handler
require_once(ROOT . DS . "core" . DS . "core.php");

?>

```

### Handling the requests

The index.php will call for a core handler at: SITE_root > mvc-php > core > core.php.

```php

<?php

# Load config
require_once(ROOT . DS . "config" . DS . "config.php");
require_once(ROOT . DS. "core" . DS . "functions.php");

# Autoloader
spl_autoload_register(function ($class_name) {
  if (file_exists(ROOT . DS . "core" . DS . strtolower($class_name) . ".php")) {
    require_once (ROOT . DS . "core" . DS . strtolower($class_name) . ".php");
  }
  else if (file_exists(ROOT . DS . "models" . DS . strtolower($class_name) . ".php")) {
    require_once (ROOT . DS . "models" . DS . strtolower($class_name) . ".php");
  }
  else if (file_exists(ROOT . DS . "views" . DS . strtolower($class_name) . ".php")) {
    require_once (ROOT . DS . "views" . DS . strtolower($class_name) . ".php");
  }    
  else if (file_exists(ROOT . DS . "controllers" . DS . strtolower($class_name) . ".php")) {
    require_once (ROOT . DS . "controllers" . DS . strtolower($class_name) . ".php");
  }
});

# Route request
$router = new Router();
$router->route($url);

?>


```

What does this file do?  

1. Call for a global configuration file (config.php)
2. Load any global helper functions (functions.php)
3. Set up a class autoloader function, following the logic, that for any new class or component, these will be divided into php chunks or separated files that should be placed in their corresponding folders, new controllers should go to ROOT_site > mvc-php > app > controllers, and so on with new models, and views.
4. Route the request from a Router class, if you can foresee the modulation logic, a file called "router.php" with the "router class" should be created and stored in the ROOT_site > mvc-php > app > core folder. The file of course will include the "Router" class to be instantiated here.

### The config.php file

This file will be located in the config folder and will contain all the global constants and any database credentials that will be used throughout the application.

```php

<?php

# Database link credentials
define ("DBNAME", "webapp");
define ("DBUSER", "root");
define ("DBPASS", "");
define ("DBHOST", "localhost");

# PATH to app
define ("PATH", "mvc-php");
define ("WEB_TITLE", "Web app");

# PATH to media files
define ("SITE_ROOT", "/" . PATH);
define ("MEDIA", SITE_ROOT . "/" . "common");
define ("HTML", "common" . DS . "html");

# Default states
define ("DEFAULT_CONTROLLER", "page");
define ("DEFAULT_METHOD", "home");
define ("NOT_FOUND", "not_found");

?>

```

Notice the DBNAME global, this means a database with the name "webapp" should be setup prior to try running a db query.

### The functions.php file

Errr, this one will be placed in the core folder and will be left empty for now.

```php

<?php

# Helper functions here

?>

```

### The router

All HTTP requests should be routed to the corresponding controller, the request will be then divided in the following format the url: http://www.mysite.com/mvc-php/home/welcome, converted to an array -> ["www.mysite.com", "mvc-php", "home", "welcome"], in the proposed framework logic, the first 2 ones will be skipped/ignored and will extract only "home" and "welcome", this means the router will call for a controller named "home" that will execute a method named "welcome".

```php

<?php

class Router {
  
  # Initial states
  protected $default_controller = DEFAULT_CONTROLLER;
  protected $default_method = DEFAULT_METHOD;
  protected $params = [];

  # Route handler method
  public function route($url) {
    
    # Split url using "/" as separator
    $url_array = array();
    $url_array = explode("/", $url);
    
    # Remove domain and app folder from url
    if (in_array(PATH, $url_array)) {
      while($url_array[0] != PATH) {
        array_shift($url_array);
      }
      array_shift($url_array);
    }
    
    # If any, pass the corresponding controller, method and parameters
    $controller = isset($url_array[0]) ? array_shift($url_array) : "";    
    $method = isset($url_array[0]) ? array_shift($url_array) : "";
    $params = isset($url_array[0]) ? array_shift($url_array) : "";
    
    if (empty($controller)) {
      $controller = $this->default_controller;
    }
    
    if (empty($method)) {
      $method = $this->default_method;
    }
    
    $params = $url_array;
    
    # Instantiate controller class and call to appropriate method
    $controller_name = $controller;
    $controller = ucfirst($controller);
    $dispatch = new $controller($controller_name, $method);
    
    if (method_exists($controller, $method)) {
      call_user_func_array(array($dispatch, $method), $params);
    }
    else {
      /* Error message */
    }
    
  } 
}

?>

```

### The controllers

Since controllers will be called upon the HTTP request and will define the relationship between the model and the view, these would be the next to code. Since there are some security concerns when an application is built using PHP and MySQL (or any database connection), some actions need to be taken to minimize undesired hacks.

A first action will be to create a general class that will execute some sanitize methods, as below, this will be the core controller class that will inherit a few security measures.

```php

<?php

class Application {
  
  function __construct() {
    $this->sanitize_data();
    $this->unregister_globals();
  }  
  
  # Remove slashes from a given string
  private function stripslashes_deep($value) {    
    $value = is_array($value) ? array_map(array($this, "stripslashes_deep"), $value) : stripslashes($value);
    
    return $value;
  }
  
  # Remove slashes from input data from GET, POST and COOKIE
  private function sanitize_data() {
    $_GET = $this->stripslashes_deep($_GET);
    $_POST = $this->stripslashes_deep($_POST);
    $_COOKIE = $this->stripslashes_deep($_COOKIE);
  }
  
  # If set, unregister any global constant
  private function unregister_globals() {
    if (ini_get("register_globals")) {
      $array = array("_SESSION", "_POST", "_GET", "_REQUEST", "_SERVER", "_ENV", "_FILES");
      
      foreach ($array as $value) {
        foreach ($GLOBALS[$value] as $key => $var) {
          if ($var === $GLOBALS[$key]) {
            unset($GLOBALS[$key]);
          }
        }
      }
    }
  }
  
}

?>

```

If you are curious about what this does, mostly it is to prevent MySQL injection through user input data. Of course additional method should be added to any user input, but not as a global class/method, but upon demand on runtime, in plain English, use sanitize methods, only when you will need to clear any user input. Rule of thumb, ALWAYS sanitize any user input.

Then the second thought would be to setup a generic Controller handler.

```php

<?php

# Implement sanitize methods first
class Controller extends Application {
  
  protected $controller;
  protected $method;
  protected $model;
  protected $view;
  
  public function __construct($controller, $method) {
    parent::__construct();
    
    $this->controller = $controller;
    $this->method = $method;
    $this->view = new View();
  }
  
  # Load and instantiate model specific for this controller
  protected function load_model($model) {
    if (class_exists($model)) {
      $this->model[$model] = new $model();
    }
    else {
      return false;
    }
  }
  
  # Implement instantiated model methods
  protected function get_model($model) {
    if (isset($this->model[$model]) && is_object($this->model[$model])) {
      return $this->model[$model];
    }
    else {      
      return false;      
    }
  }
  
  # Return output view
  protected function get_view() {
    return $this->view;
  }
  
}

?>

```

### The models

A generic Model will depend on what resources the application will use to build the View. If it is a PHP application, usually it will connect to a MySQL database, then a generic Model class to interact with this DB could be used as below.

```php

<?php

class Dbmodel {
    
  protected $sql;
  protected $rows = array();
  
  private $conx;
  
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

```

### The view

Finally, the View will "render" or fetch the DB to present data or information to the user. It will contain only the variables and methods to print the output to the user. For example if the controller and model retrieve static content from the server. The View method will just print this to the screen.

```php

<?php

class View {
      
  public function render($view_name) {    
    echo $view_name;
  }
  
}

?>

```

## Building it all together

