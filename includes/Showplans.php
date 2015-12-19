<?php

class Showplans {
	public static function get_by_id($id) { return DigiplayDB::select("* FROM showplans WHERE id = ".$id, "Showplan"); }

	public static function get_by_name($name) { return DigiplayDB::select("* FROM showplans WHERE name = '".$name."';", "Showplan"); }

	public static function count() { return DigiplayDB::select("COUNT(id) FROM showplans", NULL, false); }
}

?>