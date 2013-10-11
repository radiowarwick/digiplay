<?php
class Requests {
	public static function get($id) { return self::get_by_id($id); }
	public static function get_by_id($id) { return DigiplayDB::select("* FROM requests WHERE id = ".$id, "Request"); }
	public static function get_by_user($user) { return DigiplayDB::select("* FROM requests WHERE userid = ".$user->get_id(), "Request", true); }

	public static function get_all() { return DigiplayDB::select("* FROM requests", "Request", true); }
	public static function get_latest($count = 5) { return DigiplayDB::select("* FROM requests ORDER BY date DESC LIMIT ".$count, "Request", true); }

	public static function count() { return DigiplayDB::select("COUNT(id) FROM requests"); }
}