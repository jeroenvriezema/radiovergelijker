<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if(file_exists(DIRNAME(__FILE__)."/config.php")) {
	require_once(DIRNAME(__FILE__)."/config.php");
}
else {
	die("Veresite bestanden niet gevonden!");
}
if(file_exists(DIRNAME(__FILE__)."/db.php")) {
	require_once(DIRNAME(__FILE__)."/db.php");
}
else {
	die("Veresite bestanden niet gevonden!");
}
if(file_exists(DIRNAME(__FILE__)."/functions.php")) {
	require_once(DIRNAME(__FILE__)."/functions.php");
}
else {
	die("Veresite bestanden niet gevonden!");
}

echo "<pre>";
var_dump(station::event(11));
echo "</pre>";