<?php

class Showplans {
	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM showplans WHERE id = ".$id);
		return pg_fetch_object($result, NULL, "Showplan");
	}

	public function get_by_name($name) {
		$result = DigiplayDB::query("SELECT * FROM showplans WHERE name = '".pg_escape_string($name)."';");
		if(pg_num_rows($result) > 1) {
			$showplans = array();
			while($object = pg_fetch_object($result,NULL,"Showplan")) $showplans[] = $object;
			return $showplans;
		} else {
			return pg_fetch_object($result,NULL,"Showplan");
		}
	}
}

?>