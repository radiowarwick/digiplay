<?php
class Groups{
	public static function all() { return DigiplayDB::select("* FROM groups ORDER BY id", "Group"); }
	public static function get($id) { return self::get_by_id($id); }

	public static function get_by_id($id) { return DigiplayDB::select("* FROM groups WHERE id = ".$id, "Group"); }
	public static function get_by_name($name) { return DigiplayDB::select("* FROM groups WHERE name = '".$name."'", "Group"); }
	public static function get_by_user($user) { return DigiplayDB::select("groups.* FROM groups INNER JOIN usersgroups ON usersgroups.groupid = groups.id WHERE usersgroups.userid = ".$user->get_id()." ORDER BY groups.id", "Group", true); }
	public static function get_by_parent($parent) { return DigiplayDB::select("* FROM groups WHERE parentid = ".$parent->get_id(), "Group", true); }
}
