<?php
class Users{
	public static function get($username) { return self::get_by_username($username); }
	
	public static function get_by_username($username) { return DigiplayDB::select("* FROM users WHERE username = '".$username."';", "User"); }
	public static function get_by_id($id) { return DigiplayDB::select("* FROM users WHERE id = ".$id, "User"); }
}	
?>
