<?php

class DBDirectories {

	public static function get_by_id($id) { return DigiplayDB::select("* FROM dir WHERE id = :id", "DBDirectory", false, $id); }
	public static function get_by_parent($parent) { return DigiplayDB::select("* FROM dir WHERE parent = :parent", "DBDirectory", false, $parent->get_id()); }
}

?>