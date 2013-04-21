<?php
function __autoload($class_name) { require_once (SITE_FILE_PATH ."includes/" .$class_name . ".php"); }

ini_set("session.use_only_cookies",true);

if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value){
        $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
        return $value;
    }

    $_POST		= array_map('stripslashes_deep', $_POST);
    $_GET		= array_map('stripslashes_deep', $_GET);
    $_COOKIE	= array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST	= array_map('stripslashes_deep', $_REQUEST);
}

{
	define('SITE_FILE_BASE', str_replace('\\', '/', dirname(dirname(__FILE__))) . '/');

	$tempPath1 = explode('/', str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])));
	$tempPath2 = explode('/', substr(SITE_FILE_BASE, 0, -1));
	$tempPath3 = explode('/', str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])));
	$tempPath4 = array();
	$tempPath5 = explode('/', str_replace('\\', '/', $_SERVER['PHP_SELF']));

	for ($i = count($tempPath2); $i < count($tempPath1); $i++) $tempPath4[] = array_pop ($tempPath3);
	$tempPath4[] = "";

	$urladdr = implode('/', $tempPath3);
	$reladdr = implode('/', array_reverse($tempPath4));

	if ($urladdr{strlen($urladdr) - 1}== '/') define('SITE_URL_ROOT', $urladdr);
	else define('SITE_URL_ROOT', $urladdr . '/');
	if ($urladdr{strlen($reladdr) - 1}== '/') define('SITE_URL_REL', $reladdr);
	else define('SITE_URL_REL', $reladdr . '/');
	define('SITE_FILE_REL', implode('/',array_diff($tempPath5,$tempPath3)));

	// Legacy definitions
	define('SITE_LINK_REL',SITE_URL_ROOT);
	define('SITE_LINK_ABS',SITE_URL_REL);
	define('SITE_FILE_PATH',SITE_FILE_BASE);
	define('SITE_PATH',SITE_FILE_REL);

	unset($tempPath1, $tempPath2, $tempPath3, $tempPath4, $urladdr, $reladdr);

	// Include config file
	if(file_exists(SITE_FILE_PATH."digiplay.conf")) {
		$local_config = @parse_ini_file(SITE_FILE_PATH."digiplay.conf");
	} elseif(file_exists("/etc/digiplay.conf")) {
		$local_config = @parse_ini_file("/etc/digiplay.conf");
	} else {
		die("Fatal error: Could not open ".SITE_FILE_PATH."digiplay.conf or /etc/digiplay.conf.  Cannot continue.");
	}

	define("DATABASE_DPS_HOST", $local_config["DB_HOST"]);
	define("DATABASE_DPS_PORT", $local_config["DB_PORT"]);
	define("DATABASE_DPS_NAME", $local_config["DB_NAME"]);
	define("DATABASE_DPS_USER", $local_config["DB_USER"]);
	@define("DATABASE_DPS_PASS", $local_config["DB_PASS"]);

	if (!function_exists('http_response_code')) {
		function http_response_code($code = NULL) {
			header(':', true, $code);
		}
	}
}

session_start();

if((!Session::is_user()) && ((substr(SITE_PATH,0,4) == "ajax") && (SITE_PATH != "ajax/login.php"))) { 
	http_response_code(403); 
	exit(
		json_encode(
			array(
				"error" => "Your session has timed out, or you have logged out in another tab. Please log in again."
			)
		)
	);
}

if((SITE_PATH != "index.php") && (SITE_PATH != "ajax/login.php")) Output::require_user();

if (Session::is_developer()) {
    ini_set ( "display_errors", "1");
    ini_set ( "display_startup_errors", "1");
    ini_set ( "html_errors", "1");
    ini_set ( "docref_root", "http://www.php.net/");
    ini_set ( "error_prepend_string", "<div class=\"alert alert-error\">");
    ini_set ( "error_append_string", "</div>");
}

if(substr(SITE_PATH,0,4) != "ajax") Output::set_template("MainTemplate");
?>
