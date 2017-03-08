<?php//Toggle PHP error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);//Your MySQL host
define("MYSQL_HOST", "localhost");//Your MySQL user
define("MYSQL_USER", "YOURUSER");//Your MySQL password
define("MYSQL_PASS", "YOURPW");//Your MySQL DB
define("MYSQL_DB", "YOURDB");//Default MySQL Character set
define("MYSQL_CHARSET", "utf8");//Define the locale for time formatting
setlocale(LC_ALL, 'nl_NL');//Your Gracenote client id (For Gracenote API)
define("GRACENOTE_CLIENT_ID", '123456789');//Your Gracenote Client tag (For Gracenote API)
define("GRACENOTE_CLIENT_TAG", 'YOURGRACENOTECLIENTTAG');//Your LAST.FM API Key
define("LASTFM_API_KEY", "YOURLASTFMAPIKEY");//The default timezone
date_default_timezone_set("Europe/Amsterdam");

?>