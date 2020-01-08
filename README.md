# mvc-php
An MVC implementation using PHP

## The MVC Pattern

As you would be aware, the MVC (Model-View-Controller) is a widely used pattern in software architecture for web applications. The core on the pattern is to divide the application into components and define the relationship between them.

## The Model

Usually represents the database logic, or the behind the scenes processes used to communicate with the database. The model will return to the controller or views information required from the user or any information necessary to build the view.

## The Controller

Represents the application logic and define the relationship between the model or models and the views. The controller will also route the user requests appropriately, either to the model or view accordingly.

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

The index.php will call for a core handler at: root > core > core.php.

```php

<?php

# Load config
require_once(ROOT . DS . "config" . DS . "config.php");
require_once(ROOT . DS. "core" . DS . "functions.php");

# Autoloader
spl_autoload_register(function ($className) {
  if (file_exists(ROOT . DS . "core" . DS . strtolower($className) . ".php")) {
    require_once(ROOT . DS . "core" . DS . strtolower($className) . ".php");
  }
  else if (file_exists(ROOT . DS . "controllers" . DS . strtolower($className) . ".php")) {
    require_once (ROOT . DS . "controllers" . DS . strtolower($className) . ".php");
  }
  else if (file_exists(ROOT . DS . "models" . DS . strtolower($className) . ".php")) {
    require_once(ROOT . DS . "models" . DS . strtolower($className) . ".php");
  }
  else if (file_exists(ROOT . DS . "views" . DS . strtolower($className) . ".php")) {
    require_once(ROOT . DS . "views" . DS . strtolower($className) . ".php");
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
3. Set up a class autoloader function, following to the logic, for any new class or component, these will be divided into php chunks or separated files that should be placed in their corresponding folders, new controllers should go to ROOT_site > mvc-php > app > controllers, and so on with new models, and views.
4. Route the request from a Router class, if can foresee the modulation logic, a file called "router" should be created and stored in the ROOT_site > mvc-php > app > core folder. The file of course will include the "Routher" class to be instantiated here.
