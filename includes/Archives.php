<?php
class Archives {
	public static function get($id) { return self::get_by_id($id); }

	public static function get_by_id($id) { return DigiplayDB::select("* FROM archives WHERE id = '".$id."'", "Archive"); }
	public static function get_by_name($name) { return DigiplayDB::select("* FROM archives WHERE name = '".$name."'", "Archive"); }
	public static function get_all() { return DigiplayDB::select("* FROM archives;", "Archive", true); }

	public static function get_playin() { return Archives::get_by_name(Configs::get_system_param("playin_archive")); }
}
?>