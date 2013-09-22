<?php
class DigiplayDB{
	protected static $pgresource;
	protected static $querycount;
	protected static $querytime;

	public static function connect() {;
		self::$pgresource = pg_connect("host=". DATABASE_DPS_HOST ." port=". DATABASE_DPS_PORT ." dbname=". DATABASE_DPS_NAME ." user=". DATABASE_DPS_USER);
		self::is_connected();
		return self::$pgresource;
	}

	protected static function is_connected() {
		if(!self::$pgresource) {
				trigger_error("No Connection to database", E_USER_ERROR);
				return false;
		} else if (pg_connection_status(self::$pgresource) == PGSQL_CONNECTION_BAD) {
				trigger_error("Database connection bad",E_USER_ERROR);
				return false;
		}
		return true;
	}

	public static function get_querycount() { return self::$querycount; }
	public static function get_querytime() { return self::$querytime; }

	public static function query($query) {
		if(self::is_connected()) { 
			$time1 = microtime(true);
			$result = pg_query(self::$pgresource,$query);
			self::$querytime += microtime(true) - $time1;
			self::$querycount++;
			return $result;
		}
	}

	public static function select($query, $return_class = NULL, $as_array = false) {
		$results = self::query("SELECT ".$query);
		if(pg_num_rows($results) == 0) return NULL;
		if(pg_num_rows($results) == 1 && $as_array == false) {
			if($return_class == NULL) {
				if(pg_num_fields($results) == 1) return pg_fetch_result($results,0,0);
				else return pg_fetch_assoc($results,0);
			}
			else return pg_fetch_object($results,0,$return_class);
		}

		$return = array();
		while ($item = ($return_class? pg_fetch_object($results,NULL,$return_class) : pg_fetch_assoc($results,NULL))) $return[] = $item;
		return $return;
	}

	public static function insert($table, $data, $return_field = NULL) {
		foreach($data as $key => $val) {
			$fields .= "\"".$key."\", ";
			if(isset($val) && (is_bool($val) || (strlen($val) > 0))) {
				if(is_bool($val)) $vars .= "'".($val? "t" : "f")."', ";
				else $vars .= "'".pg_escape_string($val)."', ";
			} else {
				$vars .= "NULL, ";
			}
		}

		$sql = "INSERT INTO \"".$table."\" (".rtrim($fields,", ").") VALUES(".rtrim($vars,", ").")".(isset($return_field)? " RETURNING \"".$return_field."\"" : "");
		$result = self::query($sql);
		if(isset($return_field)) return pg_fetch_result($result, 0, 0);
		else return (bool) $result;
	}

	public static function update($table, $data, $where) {
		foreach($data as $key => $val) {
			if(isset($val) && (is_bool($val) || (strlen($val) > 0))) {
				$fields .= "\"".$key."\" = ";
				if(is_bool($val)) $fields .= "'".($val? "t" : "f")."', ";
				else $fields .= "'".pg_escape_string($val)."', ";
			}
		}

		$sql = "UPDATE \"".$table."\" SET ".rtrim($fields,", ")." WHERE ".$where;
		return self::query($sql);
	}

	public function delete($table, $where) {
		$query = self::query("DELETE FROM \"".$table."\" WHERE ".$where);
		return ($query? true : false);
	}

}
DigiplayDB::connect();
?>
