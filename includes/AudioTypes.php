<?php
class AudioTypes {
	public function get($name) { return self::get_by_name($name); }

	public function get_by_id($id) { return DigiplayDB::select("* FROM audiotypes WHERE id = ".$id, "AudioType"); }
	public function get_by_name($name) { return DigiplayDB::select("* FROM audiotypes WHERE name = '".$name."'", "AudioType"); }
}
?>