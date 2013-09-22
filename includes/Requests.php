<?php
class Requests {
	public function get($id) { return self::get_by_id($id); }
	public function get_by_id($id) { return DigiplayDB::select("* FROM requests WHERE id = ".$id, "Request"); }
	public function get_by_user($user) { return DigiplayDB::select("* FROM requests WHERE userid = ".$user->get_id(), "Request", true); }

	public function get_all() { return DigiplayDB::select("* FROM requests", "Request", true); }
	public function get_latest($count = 5) { return DigiplayDB::select("* FROM requests ORDER BY date DESC LIMIT ".$count, "Request", true); }

	public function count() { return DigiplayDB::select("COUNT(id) FROM requests"); }
}