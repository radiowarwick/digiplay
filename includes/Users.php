<?php
class Users{
	public static function get($username) { return self::get_by_username($username); }
	
	public static function get_by_username($username) { return DigiplayDB::select("* FROM users WHERE username = '".$username."';", "User"); }
	public static function get_by_id($id) { return DigiplayDB::select("* FROM users WHERE id = ".$id, "User"); }

	public static function get_all() { return DigiplayDB::select("* FROM users WHERE id > 4", "User", true); }
	public static function get_enabled() { return DigiplayDB::select("* FROM users WHERE id > 4 AND enabled = 't'", "User", true); }
}	
?>
