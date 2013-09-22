<?php
define('START_TIME', microtime(true));
function __autoload($class_name) { require_once ($class_name . ".php"); }
ini_set("session.use_only_cookies",true);

$root = explode('/', $_SERVER['DOCUMENT_ROOT']);
$dir = explode('/', dirname(__DIR__));
$file = explode('/', $_SERVER['PHP_SELF']);
define('FILE_ROOT', implode('/',$dir).'/');
if(!array_diff($dir,$root)) define('LINK_ABS', '/');
else define('LINK_ABS', '/'.implode('/',array_diff($dir,$root)).'/');
define('LINK_PATH', implode('/',array_slice(array_diff($file,$dir), 0, -1)));
define('LINK_FILE', implode('/',array_diff($file,$dir)));
unset($root,$dir);

// Include config file
if(file_exists(FILE_ROOT."digiplay.conf")) {
	$local_config = @parse_ini_file(FILE_ROOT."digiplay.conf");
} elseif(file_exists("/etc/digiplay.conf")) {
	$local_config = @parse_ini_file("/etc/digiplay.conf");
} else {
	die("Fatal error: Could not open ".FILE_ROOT."digiplay.conf or /etc/digiplay.conf.  Cannot continue.");
}

define("DATABASE_DPS_HOST", $local_config["DB_HOST"]);
define("DATABASE_DPS_PORT", $local_config["DB_PORT"]);
define("DATABASE_DPS_NAME", $local_config["DB_NAME"]);
define("DATABASE_DPS_USER", $local_config["DB_USER"]);
@define("DATABASE_DPS_PASS", $local_config["DB_PASS"]);

session_start();

if (!function_exists('http_response_code')) {
    function http_response_code($code = NULL) {
        header(':', true, $code);
    }
}

if((!Session::is_user()) && ((substr(LINK_FILE,0,4) == "ajax") && (LINK_FILE != "ajax/login.php"))) {
	http_response_code(403);
	exit(json_encode(array("error" => "Your session has timed out, or you have logged out in another tab. Please log in again.")));
}

if(substr(LINK_FILE,0,6) == "studio") {
    MainTemplate::set_barebones(true);
    if(isset($_REQUEST["key"])) {
        if(is_null(Locations::get_by_key($_REQUEST["key"]))) exit("Sorry, you provided an invalid security key.");
    } else {
        Output::require_user();
    }
}

if((LINK_FILE != "index.php") && (LINK_FILE != "ajax/login.php") && (substr(LINK_FILE,0,6) != "studio")) Output::require_user();

if (Session::is_developer()) {
    ini_set ( "display_errors", "1");
    ini_set ( "display_startup_errors", "1");
    ini_set ( "html_errors", "1");
    ini_set ( "docref_root", "http://www.php.net/");
    ini_set ( "error_prepend_string", "<div class=\"alert alert-error\">");
    ini_set ( "error_append_string", "</div>");
}

if(substr(LINK_FILE,0,4) != "ajax") Output::set_template("MainTemplate");
?>
