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
define ("MEDIA", "//" . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"]? ":" . $_SERVER["SERVER_PORT"] : "") . "/" . PATH . "/" . "common");
define ("HTML", "common" . DS . "html");

# Default states
define ("DEFAULT_CONTROLLER", "page");
define ("DEFAULT_METHOD", "index");
define ("NOT_FOUND", "not_found");

?>