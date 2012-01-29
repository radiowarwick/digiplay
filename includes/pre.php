<?php
function __autoload($class_name) {
	require_once (SITE_FILE_PATH ."includes/" .$class_name . ".php");
}

ini_set("html_errors",false);
ini_set("session.use_only_cookies",true);
ini_set("date.timezone","Europe/London");

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
	//Definitions
	//root:	Operating System root
	//file:	File requested by user. eg index.php
	//install:	Installation of template manager. Sometimes at site root, sometimes not.
	//domain:	Document root of site as seen by user
	
	$root_to_file		= preg_split("%[/\\\]%",$_SERVER['SCRIPT_FILENAME'],null,PREG_SPLIT_NO_EMPTY);
	$root_to_install	= array_slice(preg_split("%[/\\\]%",dirname(__FILE__),null,PREG_SPLIT_NO_EMPTY),0,-1);
	$docroot_to_file	= preg_split("%[/\\\]%",$_SERVER['PHP_SELF'],null,PREG_SPLIT_NO_EMPTY);
			
	$install_to_file	= array_slice($root_to_file, count($root_to_install));

	$request			= preg_split("%[/\\\]%",current(explode("?",$_SERVER['REQUEST_URI'])));
	$domain_to_install	= array_slice($request, 1, count($docroot_to_file) - count($install_to_file));
	
	//Path on server to installation root
	define("SITE_FILE_PATH", (substr(__FILE__,0,1)== DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:"").implode(DIRECTORY_SEPARATOR,$root_to_install).DIRECTORY_SEPARATOR);
	//Path from installation root
	define("SITE_PATH",	implode("/",array_slice($install_to_file,0,-1)));
	//Page from installation root
	define("SITE_PAGE",	implode("/",$install_to_file));
	
	//Actual path in URL from document root
	define("SITE_LINK_INSTALL_PATH", implode("/",$domain_to_install).(count($domain_to_install)>0?"/":""));
	//Absolute link to installation root
	define("SITE_LINK_ABS",	(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=="on"?"https":"http")."://" . $_SERVER['HTTP_HOST'] . "/" .SITE_LINK_INSTALL_PATH);
	//Absolute link to installation root (Force http)
	define("SITE_LINK_ABS_HTTP",	"http://" . $_SERVER['HTTP_HOST'] . "/" . SITE_LINK_INSTALL_PATH);
	//Absolute link to installation root (Force https)
	define("SITE_LINK_ABS_HTTPS",	"https://" . $_SERVER['HTTP_HOST'] . "/" . SITE_LINK_INSTALL_PATH);
	//Relative link to site root
	define("SITE_LINK_REL",	str_repeat("../",count($request) - count($domain_to_install) - 2));

	unset($root_to_file,$root_to_install,$docroot_to_file,$install_to_file,$request,$domain_to_install);

	// Include config file
	if(file_exists(SITE_FILE_PATH."digiplay.conf")) {
		$local_config = parse_ini_file(SITE_FILE_PATH."digiplay.conf");
	} elseif(file_exists("/etc/digiplay.conf")) {
		$local_config = parse_ini_file("/etc/digiplay.conf");
	} else {
		die("Fatal error: Could not open ".SITE_FILE_PATH."digiplay.conf or /etc/digiplay.conf.  Cannot continue.");
	}

	define("DATABASE_DPS_HOST", $local_config["DB_HOST"]);
	define("DATABASE_DPS_PORT", $local_config["DB_PORT"]);
	define("DATABASE_DPS_NAME", $local_config["DB_NAME"]);
	define("DATABASE_DPS_USER", $local_config["DB_USER"]);
	define("DATABASE_DPS_PASS", $local_config["DB_PASS"]);
}

session_start();

if((!Session::is_user()) && ((substr(SITE_PAGE,0,4) == "ajax") && (SITE_PAGE != "ajax/login.php"))) { exit("Your session has timed out, or you have logged out in another tab. Please log in again."); }
if((SITE_PAGE != "index.php") && (SITE_PAGE != "ajax/login.php")) { Output::require_user(); }

if(substr(SITE_PAGE,0,4) != "ajax") Output::set_template("MainTemplate");
?>
