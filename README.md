# MVC and PHP

An MVC implementation example using PHP

## The MVC Pattern

As you would be aware, the MVC (Model-View-Controller) is a widely used pattern in software architecture for web applications. The core on the pattern is to divide the application into components and define the relationships between them.

## The Model

Usually represents the database logic, or the behind the scenes processes used to communicate with the database. The model will return to the controller or views any information from the server required by the user, this information will then be used to build the view.

## The Controller

Represents the application logic and define the relationships between the model or models and the views. The controller will also route the user requests appropriately, either to the model or view accordingly.

## The View

The controller will submit a resource request to the model, and upon response will submit this output to the view. The view will take the provided information from the controller and build the view or output visible to the user.

## Suggested conventions

Some suggested conventions/practices are used in the example.

### Class names

Class names will have the initial letter uppercased and used as "class Classname", and instantiated as "new Classname()", class name files will lowercased though. Each class is stored in a single php script with the class name as the file name and saved in the corresponding location.

### Constant names

Constant names are all uppercased, example: MY_CONSTANT.

### Function and variables names

Function and variable names are lowercased and can follow if desired a "camel case" format, or separated by a valid character like "_".

Name examples: $myMethod or $my_method, $myVariable or $my_variable.

## Directory structure

A proposed directory structure for a given project could be as below,

ROOT sub-folder (mvc-php) for this example:

- mvc-php
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
    
Notice, you may change "mvc-php" for something more fancy like "framework" or remove it at all.

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

For webservers running apache, this can be achieved with the help of an .htaccess file.

```apache

RewriteEngine on
RewriteBase /mvc-php/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /mvc-php/index.php [L,QSA]

### "mvc-php" can be either removed or replaced by a folder of your choice

```

This .htaccess file will be located in the ROOT_site folder.

## MVC core

### The index handler

The index.php file will then call for the core classes and methods that will handle the request.

The index.php file in the "mvc-php" folder will look like the below:

```php

<?php

# Define current directory
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__FILE__) . DS . "app");

# Get URL from server and Sanitize URL
$url = filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL);

# Load core
require_once(ROOT . DS . "core" . DS . "core.php");

?>

```

### Handling the requests, the core.php file

The index.php will call for a core handler at: SITE_root > mvc-php > core > core.php. This core file will look like the below:

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

What does this script do?  

1. Call for a global configuration file (config.php).
2. Load any global helper functions (functions.php).
3. Set up a class autoloader function, following the logic, that for any new class or component, these will be divided into php chunks or separated files that should be dropped in their corresponding folders, new controllers should go to ROOT_site > mvc-php > app > controllers, and so on with new models, and views.
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

# PATH to app and app name
define ("PATH", "mvc-php");
define ("WEB_TITLE", "Web app");

# PATH to media files and site root constants
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

Errr, this one will be placed in the core folder and will be left empty for now. The name is self-explanatory, this file should contain any required global function.

```php

<?php

# Helper functions here

?>

```

### The router

All HTTP requests should be routed to the corresponding controller, the URL requested by the user will be divided to define the controller and methods. For example an URL like: http://www.mysite.com/mvc-php/home/welcome, will be converted to an array like -> ["www.mysite.com", "mvc-php", "home", "welcome"].

In the proposed framework logic, the first 2 ones will be skipped/ignored and will extract only "home" and "welcome", this means the router will call for a controller named "home" that will execute a method named "welcome".

```php

<?php

class Router {
  
  # Initial states
  protected $default_controller = DEFAULT_CONTROLLER;
  protected $default_method = DEFAULT_METHOD;
  protected $params = array();

  # Route handler method
  public function route($url) {
    
    # Split url using "/" as separator
    $url_array = array();
    $url_array = explode("/", trim($url, "/"));
    
    # Remove app folder value
    if(in_array(PATH, $url_array)){
      while ($url_array[0] != PATH) {
        array_shift($url_array);
      }
      
      array_shift($url_array);
    }
        
    # If any, pass the corresponding controller, method and parameters
    $controller = isset($url_array[0]) ? array_shift($url_array) : "";    
    $method = isset($url_array[0]) ? array_shift($url_array) : "";
    $params = isset($url_array[0]) ? $url_array : "";
    
    # If controller is not found or not exists as a class handler
    # set default controller and not found method
    if (empty($controller)) {
      $controller = $this->default_controller;
    }
    else if (!(class_exists($controller))) {
      $controller = $this->default_controller;
      $method = NOT_FOUND;      
    }    
    
    if (empty($method)) {
      $method = $this->default_method;
    }
    
    # Instantiate controller class and call to appropriate method
    $controller_name = $controller;
    $controller = ucfirst($controller);
    $dispatch = new $controller($controller_name, $method);
    
    if (method_exists($controller, $method)) {
      call_user_func_array(array($dispatch, $method), $params);
    }
    else {
      # Error handler not found method
      call_user_func_array(array($dispatch, NOT_FOUND), $params);      
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
  
  public function __construct() {
    $this->sanitize_data();
    $this->unregister_globals();
  }  
  
  # Remove slashes from a given string array
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

If you are curious about what this does, mostly it is to prevent MySQL injection through user input data. Of course additional methods should be added to sanitize any user input, but not as a global class/method, but upon demand on runtime, in plain English, use sanitize methods, only when you will need to clear any user input. Rule of thumb, ALWAYS sanitize any user input.

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
    
    $this->method = $method;
    $this->controller = $controller;    
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
  
  # Return view instance
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
  
  # Submit SELECT SQL query
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

Finally, the View will "render" or fetch the DB information to the user. It will contain only the variables and methods to print the output to the user. For example if the controller and model retrieve static content from the server. The View method will just print this to the screen.

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

Okay, nothing exciting happened yet. Lets put the pattern to work, using a static site as an example.

Let's start with the below:
- index/landing page
- an about us page
- a contact us page
- an error/not found handler page

What's cool about the MVC pattern is that you can foresee any project is scalable, but let's start with something basic.

### The Page controller

This is what follows, somebody types in an URL (request) to our site, and will build a controller to handle it. Since the final result will be a HTML page, let's call it "page" (smart no?).

```php

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

```

Look at the page controller methods, there will be one for each page view request and if you return to the config.php file, a default state is defined for the controller with this name.

### The Pagemodel model

So, the controller get the user request to the resource, and use the model to fetch the resource from the server, it could be with a DB query or to look up for a predefined static element in the server.

Then before building the Pagemodel, lets review the scenario.

1. The View will output the model result.
2. The View are also all HTML results presented to the browser upon the user request.
3. To present a landing page, the HTML code to build the view should be fetched somehow.

In this example, no DB query is run, but all the HTML is stored as is in a server location (guess where), without any PHP statement on it. Thinking on efficiency, HTML elements that could be templated were divided into HTML chunks and the model will put them together to send these to the controller which will then submit the request to the view to print this to the screen.

Not every HTML element can be templated, but a common template system could have the below:

1. Top HTML meta scripts, call to CSS files and JS scripts.
2. Top header sections 
    - Top header section with site branding (logo) and social links
    - Navigation items
3. Page body content section
4. Page footer content section

Additionally, new folders will be used to store the HTML codes for each template element and content section in the HTML folder.

- mvc-php
  - app
  - common
    - html
      - temp
      - page
  
Being "temp" the location for templated elements and "page" for each section content. This practice ensures, that all HTML keeps separated from the application logic, and any changes to it, should not break the application. Besides, front-end developers can freely work on the HTML and JS scripts without worry and back-end developers can take a deep breath knowing nothing from the core scripts is changed.

That said, lets get to the Pagemodel.

```php

<?php

class Pagemodel extends Dbmodel {
  
  private $html_str;
  
  public $site_title = WEB_TITLE;
  public $page_title = "";
  
  # With page name locate resources, put them together and return it 
  # to the controller
  public function get_page($page_name) {        
    $this->html_str = "";
    $this->html_str .= $this->get_htmlhead();
    $this->html_str .= $this->get_htmlbody($page_name);
    $this->html_str .= $this->get_htmlclose();
    
    return $this->html_str;
  }
  
  # Build head template
  protected function get_htmlhead() {
    $html = "";
    $html .= $this->get_doctype();
    $html .= $this->get_openhtml();
    $html .= $this->get_head();
    
    return $html;
  }
  
  # Build body content and bottom scripts
  protected function get_htmlbody($page_name) {
    $html = "";
    $html .= $this->get_openbody($page_name);
    $html .= $this->get_header();
    $html .= $this->get_bodycont($page_name);
    $html .= $this->get_footer();
    $html .= $this->get_scripts();
    
    return $html;
  }
  
  # Close HTML document
  protected function get_htmlclose() {
    $html = "";
    $html .= $this->get_closebody();
    
    return $html;
  }
  
  # Define doctype, defaults to html5
  protected function get_doctype($doctype = "html5") {
    $dtd = "";
    
    if ($doctype == "html5") {
      $dtd .= "<!doctype html>";
      $dtd .= "\n";
    }
    
    return $dtd;
  }
  
  # Define language, defaults to english
  protected function get_openhtml($lang = "en-us") {
    $html = "";
    
    if ($lang = "en-us") {
      $html .= "<html lang=\"en\">";
      $html .= "\n";
    }
    
    return $html;
  }
  
  # Build head section
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
  
  # Open body section, define body id with page name
  protected function get_openbody($page_name) {
    $html = "";
    $html .= " <body id=\"" . $page_name . "\">";
    $html .= "\n";
    
    return $html;
  }
  
  # Build templated header (navigation, branding)
  protected function get_header() {
    $html = "";
    
    if (file_exists(HTML . DS . "temp" . DS . "header.html")) {
      $html .= file_get_contents(HTML . DS . "temp" . DS . "header.html");
      $html .= "\n";
    }
    
    return $html;
  }
  
  # Build specific content for the page
  protected function get_bodycont($page_name) {
    $html = "";
    
    if (file_exists(HTML . DS . "page" . DS . $page_name . ".html")) {
      $html .= file_get_contents(HTML . DS . "page" . DS . $page_name . ".html");
      $html .= "\n";
    }
    
    return $html;
  }
  
  # Build footer
  protected function get_footer() {
    $html = "";
    
    if (file_exists(HTML . DS . "temp" . DS . "footer.html")) {
      $html .= file_get_contents(HTML . DS . "temp" . DS . "footer.html");
      $html .= "\n";
    }
    
    return $html;
  }
  
  # Add bottom JS scripts
  protected function get_scripts() {
    $html = "";
    
    if (file_exists(HTML . DS . "temp" . DS . "scripts.html")) {
      $html .= file_get_contents(HTML . DS . "temp" . DS . "scripts.html");
      $html .= "\n";
    }
    
    return $html;
  }
  
  # Close body and html
  protected function get_closebody() {
    $html = "";
    $html .= " </body>\n";
    $html .= "</html>";
    $html .= "\n";
    
    return $html;
  }
  
}

?>

```

Hopefully the above is self-explanatory, it builds the page piece by piece, query the server resource and sends it back to the controller.

The above will look for the files:
- temp/meta.html
- temp/resources.html
- temp/header.html
- temp/footer.html
- temp/scripts.html

and will look for the corresponding content in:
- page/pagename.html

### The Pageview result

In the page controller, the main View class is called but also a "Pageview" class, the main View will output the data returned by the model, and the Pageview will dynamically update a few keywords/directives used in the HTML code to:

- Locate media resources and reference them correctly.
- Add local hyperlink references (landing page, navigation).
- Add the page title.

For example:

```html

  ...

  <div class="header logo">
    <a href="{$SITE_ROOT$}" title="Home"><img src="{$MEDIA$}/img/logo.png" alt="Logo" /></a>
              
    <button id="navbar-toggle" data-toggle="active" data-target="#navbar" type="button" 
    class="toggle-btn bg-secondary visible-xs">
      <span>+</span>
    </button>
  </div>
          
  <nav id="navbar">
    <ul class="nav-menu">
      <li><a href="{$SITE_ROOT$}">Home</a></li>
      <li><a href="{$SITE_ROOT$}/page/about">About Us</a></li>
      <li><a href="{$SITE_ROOT$}/page/contact">Contact Us</a></li>
    </ul>
  </nav>
  
  ...
  
  <h1 class="color-primary">
    {$HOME_TITLE$}
  </h1>

```

The code keeps as "static" HTML but with PHP, the Pageview will dynamically replace those keywords. To avoid any conflict or undesired side effect, keywords are used between  specific character delimiters "{$KEYWORD$}".

```php

<?php

class Pageview extends View {
  private $localizations = array();
  
  # Initialize keywords dictionary
  public function __construct(){
    $this->build_locales();    
  }
  
  # Replace keywords
  public function replace_localizations($html) {
    
    foreach ($this->localizations as $key => $value) {
      $html = str_replace("{\$" . $key . "\$}", $value, $html);
    }
    
    return $html;
  }
  
  # Keyword list
  protected function build_locales() {
    $this->localizations = array(
      "PAGE_TITLE" => WEB_TITLE,
      "SITE_ROOT" => SITE_ROOT,
      "MEDIA" => MEDIA,
      "HOME_TITLE" => "Home title",
      "ABOUT_TITLE" => "About Us title",
      "CONTACT_TITLE" => "Contact Us title"
    );
    
    return $this->localizations;
  }
  
}

?>

```

## Final considerations

Let's review the complete process one more time:

- User sends HTTP request
- Server (Apache) redirects the request to the index.php file, .htaccess redirect directive should be placed in root directory.
- index.php file captures request and loads core.php file
- core.php loads config.php constants and the helper functions.php file
- core.php autoload classes from application folders, locations are:
  - core
  - models
  - views
  - controllers
- core.php instantiate the Router class and pass the URL to it
- Router class split URL and converts it to an array
- Router class defines controller and methods returned by the URL array
- If controller could be defined then:
  - No? Use default controller and method
  - Yes? Instantiate proper controller class and method, if method does not exists in the class, use "not found" method
- Controller implements a couple of basic sanitization methods and set initial states
- Page controller is loaded and it also loads the Pagemodel and the Pageview classes
- Page controller methods are invoked by the Router
- Page controller method submit request to the Pagemodel for the requested resource
- Pagemodel looks in the local folder for the requested files, put them together and return the data to the controller
- Page controller gets data and invoke View and Pageview methods to print/return the data to the initial request

You may preview the above implementation at:

https://accedo-gps.000webhostapp.com/demo/mvc-php/

It's all about modularization! So, keep calm and happy coding!
