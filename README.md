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

### "mvc-php" can be either removed or replaced by a folder of your choide

```
