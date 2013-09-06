<?php
class AudioTypes {
	public function get($name) {
		return self::get_by_name($name);
	}

	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM audiotypes WHERE id = ".$id);
		if(pg_num_rows($result)) return pg_fetch_object($result,NULL,"AudioType");
		else return false;
	}

	public function get_by_name($name) {
		$result = DigiplayDB::query("SELECT * FROM audiotypes WHERE name = '".ucwords($name)."'");
		if(pg_num_rows($result)) return pg_fetch_object($result,NULL,"AudioType");
		else return false;
	}
}
?>