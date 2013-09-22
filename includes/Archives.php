<?php
class Archives {
	public function get($id) { return self::get_by_id($id); }

	public function get_by_id($id) { return DigiplayDB::select("* FROM archives WHERE id = '".$id."'", "Archive"); }
	public function get_by_name($name) { return DigiplayDB::select("* FROM archives WHERE name = '".$name."'", "Archive"); }
	public function get_all() { return DigiplayDB::select("* FROM archives;", "Archive", true); }

	public function get_playin() { return Archives::get_by_name(Configs::get_system_param("playin_archive")); }
}
?>