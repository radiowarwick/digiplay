<?php
class Errors{
	protected static $errors = array();
	public static function start(){
		self::start_handler();
	}
	private static function set_reporting(){
		if(Session::is_developer()){
			error_reporting(E_ALL | E_STRICT);
		}else{
			error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);
		}
	}
	public static function start_handler(){
		set_error_handler(array(__CLASS__,"error_handler"),E_ALL | E_STRICT);
		self::set_reporting();
	}
	public static function stop_handler(){
		restore_error_handler();
		error_reporting(E_ALL ^ E_NOTICE);
	}
	public static function error_handler($type, $string, $file, $line){
		if (error_reporting() == 0) return;
		self::log_error($type, $string, $file, $line);
		if($type == E_USER_ERROR || $type == E_ERROR)
			Output::fatal_error();
	}
	public static function occured(){
		return (self::total()>0);
	}
	public static function total(){
		return count(self::$errors);
	}
	public static function clear(){
		self::$errors = array();
	}
	private static function log_error($type, $string, $file, $line){
		self::$errors[] = new Error($type, $string, $file, $line);
	}
	public static function report(){
		return implode("\n\n",self::$errors);
	}
}
class Error{
	protected $type;
	protected $string;
	protected $file;
	protected $line;
	public function __construct($type,$string,$file,$line){
		$this->type		= $type;
		$this->string		= $string;
		$this->file		= $file;
		$this->line		= $line;
	}
	public function __toString(){
		return "[".$this->error_type()."] Error on line ".$this->line." in file ".$this->file.":\n".$this->string;
	}
	private function error_type(){
		switch($this->type){
			case E_ERROR:			return "Error";
			case E_WARNING:			return "Warning";
			case E_NOTICE:			return "Notice";
			case E_USER_ERROR:		return "User Error";
			case E_USER_WARNING:	return "User Warning";
			case E_USER_NOTICE:		return "User Notice";
			case E_STRICT:			return "Runtime Notice";
		}
	}
}
?>
