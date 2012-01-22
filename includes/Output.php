<?php
class Output{
	protected static $stylesheets	= array();
	protected static $less = array();
	protected static $scripts	= array();
	protected static $feeds	= array();
	protected static $title		= "Untitled Page";
	protected static $template	= null;
	
	public static function start(){
		Errors::start();
		ob_start(array('Output','stop'));
	}
	public static function stop($buffer){
		if(!is_null(self::$template)){
			return call_user_func(array(self::$template, 'print_page'),$buffer);
		}else
			return $buffer;
	}
	public static function set_template($template = null){
		if(is_null($template)) self::$template = null;
		else if((new $template) instanceof Template)
			self::$template = $template;
	}
	public static function add_stylesheet($stylesheet){
		self::$stylesheets[] = $stylesheet;
	}
	public static function get_stylesheets(){
		return self::$stylesheets;
	}
	public static function add_less_stylesheet($stylesheet){
		self::$less[] = $stylesheet;
	}
	public static function get_less_stylesheets(){
		return self::$less;
	}
	public static function add_script($script){
		self::$scripts[] = $script;
	}
	public static function get_scripts(){
		return self::$scripts;
	}
	public static function add_feed($title, $url){
		self::$feeds[] = array("title" => $title, "url" => $url);
	}
	public static function get_feeds(){
		return self::$feeds;
	}
	public static function set_title($title){
		self::$title = $title;
	}
	public static function get_title(){
		return self::$title;
	}
	private static function reset_all(){
		self::$stylesheets	= array();
		self::$scripts		= array();
		self::$title		= null;
		ob_clean();
	}
	public static function http_error($error_code){
		self::reset_all();
		switch($error_code){
			case 401:	header("HTTP/1.0 401 Unauthorized");	self::$title = "Permission Error.";	break;
			case 404:	header("HTTP/1.0 404 Not Found");	self::$title = "File not found.";	break;
			case 410:       header("HTTP/1.0 410 Gone");		self::$title = "Gone";        break;
		}
		echo call_user_func(array(self::$template, 'print_http_error'),$error_code);
		exit();
	}
	public static function fatal_error(){
		header("HTTP/1.0 503 Service Unavailable");
		ob_end_clean();
		include(SITE_FILE_PATH . "errors/fatal_error.php");
		exit();
	}
	
	public static function require_non_user(){
		if(Session::is_user()){
			header("Location: ".SITE_LINK_ABS);
			exit();
		}
	}
	public static function require_user(){
		if(!Session::is_user()){
			header("Location: ".SITE_LINK_ABS."index.php?refer=".SITE_PAGE.(count($_GET)>0?urlencode("&".http_build_query($_GET)):""));
			exit();
		}
	}
	public static function require_developer(){
		self::require_user();
		if(!Session::is_developer()){
			self::http_error(401);
		}
	}
	public static function require_group($group){
		self::require_user();
		if(!Session::is_group_user($group)){
			self::http_error(401);
		}
	}
	public static function require_group_admin($group){
		self::require_user();
	        if(!Session::is_group_admin($group)){
	        	self::http_error(401);
	        }
	}
}
Output::start();
?>
