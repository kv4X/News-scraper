<?php
ini_set("display_errors", true);
date_default_timezone_set("Europe/Sarajevo");

/* GENERAL */
define("APP_NAME", "NEWS SCRAPER");

/* DATABASE */
define("DB_SERVER", "localhost");
define("DB_DATABASE", "");
define("DB_USERNAME", "");
define("DB_PASSWORD", "");

/* Classes Autoloader */
function autoload($class){
	// classes dir 
	$dir = dirname(__FILE__)."/classes/";
	// note : All classes file have a lowercase name. the class name must be lowercase
	$classFile = $dir.strtolower($class).'.php';
	
	// Check file existence before including the if 
	if(file_exists($classFile)) {
		require_once $classFile;
	}
}
spl_autoload_register('autoload');

require_once "classes/scraperPost.php";
$db = new Db(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$wp = new Wp("http://yourwebsite.com/xmlrpc.php", "admin", "admin");
