<?php

class Showplans {
	public static function get_by_id($id) { return DigiplayDB::select("* FROM showplans WHERE id = ".$id, "Showplan"); }

	public static function get_by_name($name) { return DigiplayDB::select("* FROM showplans WHERE name = '".$name."';", "Showplan"); }
}

?>