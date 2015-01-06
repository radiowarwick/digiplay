<?php
class DigiplayDB{
	protected static $connection;
	protected static $querycount;
	protected static $querytime;

	public static function connect() {
		try {
			self::$connection = new PDO("pgsql:host=". DATABASE_DPS_HOST .";port=". DATABASE_DPS_PORT .";dbname=". DATABASE_DPS_NAME .";user=". DATABASE_DPS_USER);
			self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			trigger_error("Database error: ".$e->getMessage(), E_USER_ERROR);
		}
	}

	public static function get_querycount() { return self::$querycount; }
	public static function get_querytime() { return self::$querytime; }

	public static function query($query, $parameters = NULL) {
		$time1 = microtime(true);
		try {
			$result = self::$connection->prepare($query);
			$result->execute($parameters);
		} catch(PDOException $e) {
			trigger_error("Database error: ".$e->getMessage(). "\n". $query, E_USER_WARNING);
		}
		self::$querytime += microtime(true) - $time1;
		self::$querycount++;
		return $result;
	}

	public static function select($query, $return_class = NULL, $as_array = false, $parameters = NULL) {
		if(isset($parameters) && !is_array($parameters )) $parameters = (array) $parameters;
		$results = self::query("SELECT ".$query, $parameters);
		if($results->rowCount() == 0 && $as_array == false) return NULL;
		if($results->rowCount() == 0 && $as_array == true) return array();
		if($results->rowCount() == 1 && $as_array == false) {
			if($return_class == NULL) {
				if($results->columnCount() == 1) return $results->fetchColumn(0);
				else return $results->fetch(PDO::FETCH_ASSOC);
			}
			else return $results->fetchAll(PDO::FETCH_CLASS,$return_class)[0];
		}
		
		if(isset($return_class)) return $results->fetchAll(PDO::FETCH_CLASS, $return_class);
		else return $results->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function insert($table, $data, $return_field = NULL) {
		foreach($data as $key => $val) {
			if($key == "id" && $val == NULL) unset($data[$key]);
			else if(!(isset($val) && (is_bool($val) || (strlen($val) > 0)))) $data[$key] = NULL;
		}

		$sql = "INSERT INTO \"".$table."\" (\"".implode("\", \"",array_keys($data))."\") VALUES(:".implode(", :", array_keys($data)).")".(isset($return_field)? " RETURNING \"".$return_field."\"" : "");

		foreach($data as $key => $val) {
			$data[":".$key] = $val;
			unset($data[$key]);
		}

		$result = self::query($sql, $data);
		if(isset($return_field)) return $result->fetchColumn(0);
		else return (bool) $result;
	}

	public static function update($table, $data, $where) {
		$fields = "";
		foreach($data as $key => $val) {
			$fields .= "\"".$key."\" = :".$key.", ";
		}

		$sql = "UPDATE \"".$table."\" SET ".rtrim($fields,", ")." WHERE ".$where;
		foreach($data as $key => $val) {
			$data[":".$key] = $val;
			unset($data[$key]);
		}

		$result = self::query($sql, $data);
		return (bool) $result;
	}

	public static function delete($table, $where) {
		return (bool) self::query("DELETE FROM \"".$table."\" WHERE ".$where);
	}

}
DigiplayDB::connect();
?>
