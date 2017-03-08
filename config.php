<?php
//Enable or disable PHP error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
//Define MYSQL Host
define("MYSQL_HOST", "localhost");
//Define MySQL user
define("MYSQL_USER", "YOURUSER");
//Define MySQL Password
define("MYSQL_PASS", "YOURPW");
//Define MySQL Database
define("MYSQL_DB", "YOURDB");
//Define MySQL character set
define("MYSQL_CHARSET", "utf8");
//Set locale for time formatting
setlocale(LC_ALL, 'nl_NL');
//define the base url
define("BASE_URL", "https://radiovergelijker.nl");
//Define the servers timezome
date_default_timezone_set("Europe/Amsterdam");
?>
