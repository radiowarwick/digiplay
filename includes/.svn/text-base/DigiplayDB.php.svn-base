<?php
class DigiplayDB{
	protected static $pgresource;
	protected static $log = array();
	public static function connect(){
		self::$pgresource = pg_connect("host=". DATABASE_DPS_HOST ." port=". DATABASE_DPS_PORT ." dbname=". DATABASE_DPS_NAME ." user=". DATABASE_DPS_USER);
		self::is_connected();
		return self::$pgresource;
	}
	public static function resource(){
		return self::$pgresource;
	}
	protected static function is_connected(){
		if(!self::$pgresource){
			trigger_error("No Connection to database", E_USER_ERROR);
			return false;
		}else if (pg_connection_status(self::$pgresource)==PGSQL_CONNECTION_BAD){
			trigger_error("Database connection bad",E_USER_ERROR);
			return false;
		}
		return true;
	}
	public static function query($query){
		if(self::is_connected()){
			self::$log[] = $query;
			return pg_query(self::$pgresource, $query);
		}
		
	}
	public static function get_log(){
		return self::$log;
	}
}
DigiplayDB::connect();
?>
