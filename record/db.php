<?php
if(file_exists(DIRNAME(__FILE__)."/config.php")) {
	require_once(DIRNAME(__FILE__)."/config.php");
}
else {
	die("Veresite bestanden niet gevonden!");
}

class db {
	private static $instance;
	
	public function __construct() {
		
	}
	
	public function __clone() {}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			$dsn = "mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DB.";charset=".MYSQL_CHARSET;
			$opt = [
				PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE 	=> PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES		=> false,
				PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
			];
			self::$instance = new PDO($dsn, MYSQL_USER, MYSQL_PASS, $opt);
		}
		return self::$instance;
	}
}