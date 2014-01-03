<?php

class DBDirectories {

	public static function get_by_id($id) { return DigiplayDB::select("* FROM dir WHERE id = :id", "DBDirectory", false, $id); }
	public static function get_by_parent($parent) { return DigiplayDB::select("* FROM dir WHERE parent = :parent", "DBDirectory", false, $parent->get_id()); }

	public static function get_user_folder($user) { return DigiplayDB::select("* FROM dir WHERE parent = 7 AND name = :username", "DBDirectory", false, $user->get_username()); }

	public static function find_in($dir, $name) { return DigiplayDB::select("* FROM dir WHERE parent = :parent AND name = :name", "DBDirectory", false, array(":parent" => $dir->get_id(), ":name" => $name)); }
}

?>